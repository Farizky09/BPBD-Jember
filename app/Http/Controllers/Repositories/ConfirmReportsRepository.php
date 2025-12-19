<?php

namespace App\Http\Controllers\Repositories;

use App\Http\Controllers\Interfaces\ConfirmReportsInterfaces;
use App\Models\ConfirmReport;
use App\Models\Reports;
use GuzzleHttp\Client;
use Illuminate\Console\View\Components\Confirm;
// use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ConfirmReportsRepository implements ConfirmReportsInterfaces
{
    private $confirmReports;
    private $reports;

    public function __construct(ConfirmReport $confirmReports, Reports $reports)
    {
        $this->confirmReports = $confirmReports;
        $this->reports = $reports;
    }

    private function haversine($lat1, $lon1, $lat2, $lon2)
    {

        $earthRadius = 6371; // Earth radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // Distance in kilometers
    }
    public function get()
    {
        return $this->confirmReports->get();
    }

    public function getById($id)
    {
        return $this->confirmReports->find($id);
    }
    public function show($id)
    {
        return $this->confirmReports->with(['report', 'admin'])->find($id);
    }
    public function store($data)
    {
        return $this->confirmReports->create($data);
    }

    public function update($id, $data)
    {
        $confirmReport = $this->confirmReports->findOrFail($id);
        $confirmReport->update($data);
    }
    public function delete($id)
    {
        $confirmReport = $this->confirmReports->find($id);
        $confirmReport->delete();
    }
    public function datatable()
    {

        $startDate = request()->start_date;
        $endDate = request()->end_date;
        $status = request()->status;
        $isUser = Auth::user()->hasRole('user');
        $isAdmin = Auth::user()->hasRole('admin');
        $isSuperAdmin = Auth::user()->hasRole('super_admin');
        $data = $this->confirmReports->with(['report.user', 'admin'])
            ->when($isUser, function ($query) {
                $query->whereHas('report', function ($subQuery) {
                    $subQuery->where('user_id', Auth::user()->id);
                });
            })
            ->when($isAdmin, function ($query) {
                $query->where('admin_id', Auth::user()->id);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                if ($startDate == $endDate) {
                    $query->whereDate('created_at', $startDate);
                } else {
                    $query->whereBetween('created_at', [
                        $startDate . ' 00:00:00',
                        $endDate . ' 23:59:59'
                    ]);
                }
            })->orderBy('created_at', 'desc')
            ->get();
        return $data;
    }
    // return ConfirmReport::with(['report', 'admin'])->when(Auth::user()->hasRole('user'), function ($query) {
    //     $query->whereHas('report', function ($subQuery) {
    //         $subQuery->where('user_id', Auth::user()->id);
    //     });
    // })->when(Auth::user()->hasRole('admin'), function ($query) {
    //     $query->where('admin_id', Auth::user()->id);
    // })->orderBy('created_at', 'desc');


    public function getSameReports(int $id, float $radius = 0.1)
    {
        $firstReport = ConfirmReport::with('report')->findOrFail($id);
        $lat = $firstReport->report->latitude;
        $long = $firstReport->report->longitude;
        $category = $firstReport->report->disasterCategory->id;
        $time = $firstReport->created_at;

        $candidates = ConfirmReport::with('report.user')
            ->where('status', 'proses')
            ->where('id', '!=', $firstReport->id)
            ->get();


        $sameReports = [];
        foreach ($candidates as $candidate) {
            $score = 0;

            $distance = $this->haversine($lat, $long, $candidate->report->latitude, $candidate->report->longitude);
            if ($distance <= $radius) {
                $score += 1;
            }
            if ($candidate->report->disasterCategory->id == $category) {
                $score += 1;
            }
            if ($candidate->created_at->diffInMinutes($time) <= 60) {
                $score += 1;
            }
            if ($score == 3) {
                $sameReports[] = $candidate;
            }
        }
        return $sameReports;
    }
    public function accepted($data, $id)
    {
        DB::beginTransaction();
        try {
            $originialReport = ConfirmReport::with('report.user')->findOrFail($id);
            $sameReports = $this->getSameReports($id);
            $originialReport->update([
                'status' => 'accepted',
                'disaster_level' => $data['disaster_level'],
                'notes' => $data['notes'],
                'confirmed_at' => now()
            ]);
            $originialReport->report->update([
                'status' => 'done',
            ]);
            $originialReport->report->user->increment('poin', 10);

            foreach ($sameReports as $sameReport) {
                $sameReport->update([
                    'status' => 'accepted',
                    'disaster_level' => $data['disaster_level'],
                    'notes' => $data['notes'],
                    'confirmed_at' => now(),
                    'main_report_id' => $originialReport->id,
                ]);
                $sameReport->report->update([
                    'status' => 'done',
                ]);
                $sameReport->report->user->increment('poin', 10);
            }

            $this->sendNotificationToMobileApps($originialReport->report->name, $originialReport->report->description);

            DB::commit();
            return;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }



    public function rejected($data, $id)
    {
        DB::beginTransaction();
        try {
            $confirmReports = ConfirmReport::with('report.user')->findOrFail($id);
            $confirmReports->update([
                'status' => 'rejected',
                'notes' => $data['notes'],
                'confirmed_at' => now(),
            ]);
            $confirmReports->report->update([
                'status' => 'done',
            ]);

            $confirmReports->report->user->decrement('poin', 20);
            // $confirmReport->report->user->decrement('poin', 10);
            DB::commit();
            return $confirmReports;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }


    public function getDataExport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');

        $data = $this->confirmReports
            ->with(['report.user', 'admin'])
            ->when($request->has('status') && $status != null, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                if ($startDate == $endDate) {
                    $query->whereDate('created_at', $startDate);
                } else {
                    $query->whereBetween('created_at', [
                        $startDate . ' 00:00:00',
                        $endDate . ' 23:59:59'
                    ]);
                }
            })
            ->orderBy('created_at', 'desc');



        return $data->get();
    }


    public function sendNotificationToMobileApps($title, $body)
    {

        $request = request()->merge([
            'title' => $title . "!",
            'body' => $body
        ]);
        $this->sendFCMNotification($request);
    }

    public function getGoogleAccessToken()
    {

        $serviceAccountPath = storage_path('app/service-account.json');
        $jsonkey = json_decode(file_get_contents($serviceAccountPath), true);

        $now = time();
        $token = [
            'iss' => $jsonkey['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => $jsonkey['token_uri'],
            'iat' => $now,
            'exp' => $now + 3600,
        ];

        $jwt = \Firebase\JWT\JWT::encode($token, $jsonkey['private_key'], 'RS256');

        $client = new Client();
        $response = $client->post($jsonkey['token_uri'], [
            'form_params' => [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ],
        ]);

        $body = json_decode($response->getBody(), true);
        return $body['access_token'];
    }

    public function sendFCMNotification(Request $request)
    {
        $accessToken = $this->getGoogleAccessToken();
        // return $accessToken;
        $projectId = env('MOBILE_APP_ID');
        $client = new Client();
        if ($request->input('token') == null) {
            $topic = ['topic' => 'all'];
        } else {
            $topic = ['token' => $request->input('token')];
        }

        $response = $client->post('https://fcm.googleapis.com/v1/projects/' . $projectId . '/messages:send', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'message' => array_merge($topic, [
                    'notification' => [
                        'title' => $request->input('title'),
                        'body' => $request->input('body'),
                    ],
                ]),
            ]
        ]);

        return response()->json(['status' => 'success', 'response' => json_decode($response->getBody(), true)]);
    }


    public function recapDataTables()
    {

        $startDate = request()->start_date;
        $endDate = request()->end_date;
        $status = request()->status;
        $subdistrict = request()->subdistrict;
        $category = request()->category;
        $isAdmin = Auth::user()->hasRole('admin');
        $isSuperAdmin = Auth::user()->hasRole('super_admin');

        $data = $this->confirmReports->with(['report.user', 'admin', 'report.disasterCategory'])
            ->when($isAdmin, function ($query) {
                $query->where('admin_id', Auth::user()->id);
            })

            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($subdistrict, function ($query) use ($subdistrict) {
                $query->whereHas('report', function ($subQuery) use ($subdistrict) {
                    $subQuery->whereRaw('LOWER(subdistrict) = ?', [strtolower($subdistrict)]);
                });
            })
            ->when($category, function ($query) use ($category) {
                $query->whereHas('report.disasterCategory', function ($subQuery) use ($category) {
                    $subQuery->where('name', $category);
                });
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                if ($startDate == $endDate) {
                    $query->whereDate('created_at', $startDate);
                } else {
                    $query->whereBetween('created_at', [
                        $startDate . ' 00:00:00',
                        $endDate . ' 23:59:59'
                    ]);
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return $data;
    }


    public function detailRecaps($id)
    {
        $data = $this->confirmReports->with(['report.user', 'admin', 'report.disasterCategory', 'disasterImpacts'])
            ->findOrFail($id);
        return $data;
    }
    public function getDataExportRecap(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');
        $subdistrict = $request->input('subdistrict');
        $category = $request->input('category');
        $isAdmin = Auth::user()->hasRole('admin');
        $isSuperAdmin = Auth::user()->hasRole('super_admin');

        $data = $this->confirmReports
            ->with(['report.user', 'admin', 'report.disasterCategory', 'disasterImpacts'])
            ->when($isAdmin, function ($query) {
                $query->where('admin_id', Auth::user()->id);
            })
            ->when($request->has('status') && $status != null, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($subdistrict, function ($query) use ($subdistrict) {
                $query->whereHas('report', function ($subQuery) use ($subdistrict) {
                    $subQuery->whereRaw('LOWER(subdistrict) = ?', [strtolower($subdistrict)]);
                });
            })
            ->when($request->has('category') && $category != null, function ($query) use ($category) {
                $query->whereHas('report.disasterCategory', function ($subQuery) use ($category) {
                    $subQuery->where('name', $category);
                });
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                if ($startDate == $endDate) {
                    $query->whereDate('created_at', $startDate);
                } else {
                    $query->whereBetween('created_at', [
                        $startDate . ' 00:00:00',
                        $endDate . ' 23:59:59'
                    ]);
                }
            })
            ->orderBy('created_at', 'desc');

        return $data->get();
    }
}

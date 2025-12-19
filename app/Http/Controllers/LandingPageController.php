<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\ConfirmReport;
use App\Models\Consultation;
use App\Models\DisasterCategory;
use App\Models\Infografis;
use App\Models\News;
use App\Models\Reports;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LandingPageController extends Controller
{

    function home()
    {
        $news = News::with(['imageNews'])->where('status', 'published')->orderByDesc('created_at')->take(8)->get();
        // dd($news);
        $consultation = Consultation::all();
        $informasiBPBD = Infografis::where('category_image', 'head_image')->get();
        $infografisJember = Infografis::where('category_image', 'infografis_jember')->get();
        $infografisRaung = Infografis::where('category_image', 'infografis_raung')->get();
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        return view('page.components.index', compact('apiKey', 'news', 'consultation', 'informasiBPBD', 'infografisJember', 'infografisRaung'));
    }

    function login()
    {
        return view('auth.login');
    }

    function register()
    {
        return view('auth.register');
    }
    function konsultasi()
    {
        $consultation = Consultation::with('consultations')->get();
        return view('page.pagekonsultasi.konsultasi', compact('consultation'));
    }

    public function fetchConsultations(Request $request)
    {
        $type = $request->input('type');
        $search = $request->input('search');

        $query = Consultation::with('consultations');

        if ($type) {
            $query->where('type', $type);
        }

        if ($search) {
            $query->whereHas('consultations', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $consultations = $query->get();

        return response()->json(['consultations' => $consultations]);
    }


    function lapor()
    {
        $data = DisasterCategory::get();
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        return view('page.laporpage.lapor', compact('data', 'apiKey'));
    }


    public function getNewsDisaster()
    {
        $data = News::with(['imageNews'])
            ->where('status', 'published')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
    public function berita($slug)
    {

        $news = News::with(['imageNews', 'confirmReports.report.disasterCategory'])
            ->where('slug', $slug)
            ->firstOrFail();

        $allNews = News::with('imageNews')
            ->where('status', 'published')
            ->latest()
            ->get();

        $comments = Comments::where('news_id', $news->id)->with('user')->latest()->get();
        // return $comments;
        return view('page.disaster_detail.index', ['news' => $news, 'allNews' => $allNews, 'comments' => $comments]);
    }



    function processGeminiKonsultasi(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $image = $request->file('image');
        $base64Image = base64_encode(file_get_contents($image));
        $apiKey = env('GEMINI_API_KEY');
        $payload = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "Berikan jenis bencana, dampak dan penanganan yang tepat dan terbaik berdasarkan gambar bencana ini. Berikan jika bukan dari klasifikasi bencana alam atau non alam maka tidak menampilkan output  " .
                                "Pisahkan jenis bencana dengan awalan '[JENIS]', dampak dengan awalan '[DAMPAK]' dan penanganan dengan awalan '[PENANGANAN]'."

                        ],
                        [
                            'inlineData' => [
                                'mimeType' => $image->getMimeType(),
                                'data' => $base64Image
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $response = Http::post(
            "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}",
            $payload
        );
        $data = $response->json();

        if (isset($data['candidates']) && isset($data['candidates'][0]['content']['parts'])) {
            $result = $data['candidates'][0]['content']['parts'][0]['text'];
            return response()->json([
                'status' => 'success',
                'result' => $result
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'result' => 'Tidak dapat menghasilkan saran dari gambar ini.'
            ], 422);
        }
    }

    public function getLocationData(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$latitude},{$longitude}&key={$apiKey}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response);

        if ($data && $data->status === 'OK' && isset($data->results[0])) {
            $address = $data->results[0]->formatted_address;
            $subdistrict = '';

            foreach ($data->results[0]->address_components as $component) {
                if (in_array('administrative_area_level_3', $component->types)) {
                    $subdistrict = $component->long_name;
                    break;
                }
            }

            return response()->json([
                'status' => 'success',
                'display_name' => $address,
                'subdistrict' => $subdistrict,
                'latitude' => $latitude,
                'longitude' => $longitude,
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Unable to retrieve your address']);
    }


    public function loadMap()
    {
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        if (empty($apiKey)) {
            return response('Google Maps API Key not configured.', 500)
                ->header('Content-Type', 'application/javascript');
        }
        $googleMapsJsUrl = "https://maps.googleapis.com/maps/api/js?key={$apiKey}&callback=initMap&libraries=places";
        try {
            $response = Http::get($googleMapsJsUrl);
            if ($response->successful()) {
                $scriptContent = $response->body();
            } else {
                return response('Error loading Google Maps JavaScript.', 500)
                    ->header('Content-Type', 'application/javascript');
            }
        } catch (Exception $e) {
            return response('Error loading Google Maps JavaScript.', 500)
                ->header('Content-Type', 'application/javascript');
        }
        return response($scriptContent)
            ->header('Content-Type', 'application/javascript')
            ->header('Cache-Control', 'public, max-age=86400');
    }
    public function getDisasterMarker()
    {
        $data = ConfirmReport::with(['disasterImpacts', 'reports.disasterCategory'])->whereNull('main_report_id')->where('status', 'accepted')->get();
        $data->each(function ($item) {
            if ($item->reports && isset($item->reports->created_at)) {
                $item->reports->created_at_formatted = Carbon::parse($item->reports->created_at)->translatedFormat('d F Y');
            }
        });
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function mountainStatus()
    {
        return view('page.mountain.index');
    }

    public function gempaStatus()
    {
        $firebaseConfig = [
            'apiKey' => env('FIREBASE_API_KEY'),
            'authDomain' => env('FIREBASE_AUTH_DOMAIN'),
            'databaseURL' => env('FIREBASE_DATABASE_URL'),
            'projectId' => env('FIREBASE_PROJECT_ID'),
            'storageBucket' => env('FIREBASE_STORAGE_BUCKET'),
            'messagingSenderId' => env('FIREBASE_MESSAGING_SENDER_ID'),
            'appId' => env('FIREBASE_APP_ID'),
            'measurementId' => env('FIREBASE_MEASUREMENT_ID'),
        ];
        return view('page.earthquake.index', compact('firebaseConfig'));
    }


}

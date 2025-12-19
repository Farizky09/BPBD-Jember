<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Interfaces\DisasterVictimsInterfaces;
use App\Models\DisasterImpacts;
use App\Models\ImpactType;
use Illuminate\Http\Request;

class DisasterVictimsController extends Controller
{
    private $disasterVictims;
    public function __construct(DisasterVictimsInterfaces $disasterVictims)
    {
        $this->disasterVictims = $disasterVictims;
    }

    public function index(Request $request)
    {
        
        if ($request->ajax()) {
            return datatables()
                ->of($this->disasterVictims->datatable())
                ->addColumn('kd_disaster_impacts', fn($data) => $data->disasterImpact->kd_disaster_impacts ?? '-')
                ->addColumn('fullname', fn($data) => $data->fullname ?? "-")
                ->addColumn('nik', fn($data) => $data->nik ?? "-")
                ->addColumn('kk', fn($data) => $data->kk ?? "-")
                ->addColumn('gender', function ($data) {
                    if (!$data->gender) return "-";
                    return $data->gender === 'male' ? 'Laki-laki' : ($data->gender === 'female' ? 'Perempuan' : '-');
                })
                ->addColumn('age', fn($data) => $data->age ?? "-")
                ->addColumn('family_status', fn($data) => $data->family_status ?? "-")
                ->addColumn('phone_number', fn($data) => $data->phone_number ?? "-")
                ->addColumn('birth_place', fn($data) => $data->birth_place ?? "-")
                ->addColumn('birth_date', fn($data) => $data->birth_date ?? "-")
                ->addColumn('vulnerable_group', function ($data) {
                    $labels = [
                        'elderly' => 'Lansia',
                        'babies' => 'Bayi/Balita',
                        'disabled' => 'Disabilitas',
                        'pregnant_women' => 'Ibu Hamil',
                        'general' => 'Umum',
                    ];
                    return $data->vulnerable_group ? ($labels[$data->vulnerable_group] ?? '-') : '-';
                })
                ->addColumn('action', function ($data) {
                    return view('disaster_victims.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }

        return view('disaster_victims.index');
    }

    public function create($id)
    {
        $disasterImpact = DisasterImpacts::findOrFail($id);
        if (!$disasterImpact) {
            return redirect()->route('disaster_victims.index')->with('error', 'Disaster Impact not found');
        }
        $disasterImpactType = ImpactType::all();
        $disasterImpactsTypelabel = [
            'lightly_damaged_houses' => 'Rumah Rusak Ringan',
            'moderately_damaged_houses' => 'Rumah Rusak Sedang',
            'heavily_damaged_houses' => 'Rumah Rusak Berat',
            'damaged_public_facilities' => 'Fasilitas Umum Rusak',
            'missing_people' => 'Orang Hilang',
            'injured_people' => 'Orang Terluka',
            'affected_people' => 'Orang Terdampak',
            'deceased_people' => 'Orang Meninggal',
        ];
        return view('disaster_victims.create', compact('disasterImpact', 'disasterImpactType', 'disasterImpactsTypelabel'));
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'disaster_impact_id' => 'required|exists:disaster_impacts,id',

            'fullname' => 'nullable|string|max:255',
            'nik' => 'nullable|max:255',
            'kk' => 'nullable|max:255',
            'gender' => 'nullable|in:male,female',
            'age' => 'nullable|integer|min:0',
            'family_status' => 'nullable|in:ayah,ibu,anak',
            'phone_number' => 'nullable|max:255',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'vulnerable_group' => 'nullable|in:elderly,babies,disabled,pregnant_women,general',
            'impact_types' => 'nullable|array',
            'impact_types.*' => 'nullable|in:lightly_damaged_houses,moderately_damaged_houses,heavily_damaged_houses,damaged_public_facilities,missing_people,injured_people,affected_people,deceased_people',

        ]);
        try {
            $this->disasterVictims->store($data);
            return redirect()
                ->route('disaster_victims.create', ['id' => $data['disaster_impact_id']])
                ->with('success', 'Data Korban berhasil disimpan');
        } catch (\Throwable $th) {
            return redirect()->route('disaster_victims.create', ['id' => $data['disaster_impact_id']])->with('error', 'Data Korban gagal disimpan: ' . $th->getMessage());
        }
    }

    public function edit($id)
    {
        $data = $this->disasterVictims->getById($id);
        if (!$data) {
            return redirect()->route('disaster_victims.index')->with('error', 'Data tidak ditemukan');
        }
        $disasterImpactType = ImpactType::all();
        $selectedImpactTypes = $data->impactTypes->pluck('name')->toArray();
        $disasterImpactsTypelabel = [
            'lightly_damaged_houses' => 'Rumah Rusak Ringan',
            'moderately_damaged_houses' => 'Rumah Rusak Sedang',
            'heavily_damaged_houses' => 'Rumah Rusak Berat',
            'damaged_public_facilities' => 'Fasilitas Umum Rusak',
            'missing_people' => 'Orang Hilang',
            'injured_people' => 'Orang Terluka',
            'affected_people' => 'Orang Terdampak',
            'deceased_people' => 'Orang Meninggal',
        ];
        return view('disaster_victims.edit', compact('data', 'disasterImpactType', 'selectedImpactTypes', 'disasterImpactsTypelabel'));
    }
    public function update(Request $request, $id)
    {
        $validImpactTypes = [
            'lightly_damaged_houses',
            'moderately_damaged_houses',
            'heavily_damaged_houses',
            'damaged_public_facilities',
            'missing_people',
            'injured_people',
            'affected_people',
            'deceased_people'
        ];

        $data = $request->validate([
            'disaster_impact_id' => 'required|exists:disaster_impacts,id',
            'fullname' => 'required|string|max:255',
            'nik' => 'required|string|max:255|unique:disaster_victims,nik,' . $id,
            'kk' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'age' => 'required|integer|min:0',
            'family_status' => 'required|in:ayah,ibu,anak',
            'phone_number' => 'required|string|max:255',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'impact_types' => 'required|array|min:1',
            'impact_types.*' => 'required|in:' . implode(',', $validImpactTypes),
        ]);

        try {
            $this->disasterVictims->update($data, $id);
            return redirect()->route('disaster_victims.index')->with('success', 'Data berhasil diupdate');
        } catch (\Throwable $th) {
            return back()->with('error', 'Gagal update: ' . $th->getMessage())->withInput();
        }
    }
    public function delete($id)
    {
        try {
            $this->disasterVictims->delete($id);
            return response()->json(['success' => 'Data berhasil dihapus']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Data gagal dihapus: ' . $th->getMessage()]);
        }
    }
}

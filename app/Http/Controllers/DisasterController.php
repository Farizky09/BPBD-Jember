<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Interfaces\DisasterCategoryInterfaces;
use App\Models\DisasterCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DisasterController extends Controller
{


    private $disasterCategory;
    public function __construct(DisasterCategoryInterfaces $disasterCategory)
    {
        $this->disasterCategory = $disasterCategory;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->disasterCategory->datatable();

            return datatables()->of($data)
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('type', function ($data) {
                    return $data->type ? Str::headline($data->type) : '-';
                })
                ->addColumn('action', function ($data) {
                    return view('disaster.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('disaster.index');
    }
    public function create()
    {
        return view('disaster.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:disaster_category',
        ]);
        try {
            $data = $request->all();
            $this->disasterCategory->store($data);
            return redirect()->route('disaster.index')->with('success', 'Data berhasil disimpan');
        } catch (\Throwable $th) {
            throw $th;
            return redirect()->route('disaster.create')->with('error', 'Data gagal disimpan');
        }
    }

    public function edit(string $id)
    {
        $disaster = $this->disasterCategory->getByid($id);
        return view('disaster.edit', compact('disaster'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:disaster_category',

        ]);
        try {
            $data = $request->all();
            $this->disasterCategory->update($data, $id);
            return redirect()->route('disaster.index')->with('success', 'Data berhasil diupdate');
        } catch (\Throwable $th) {
            return redirect()->route('disaster.create')->with('error', 'Data gagal diupdate');
        }
    }
    public function destroy(string $id)
    {
        try {
            $this->disasterCategory->delete($id);
            // return redirect()->json('disaster.index')->with('success', 'Data berhasil dihapus');
            return response()->json(['success' => 'Data berhasil dihapus']);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json(['error' => 'Data gagal dihapus! ' . $th->getMessage()]);
            // return redirect()->json('disaster.create')->with('error', 'Data gagal dihapus!' . $th->getMessage());
        }
    }
}

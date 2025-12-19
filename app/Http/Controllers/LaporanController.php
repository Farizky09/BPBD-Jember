<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function index()
    {
        $laporans = Laporan::all();
        return view('laporans.index', compact('laporans'));
    }
    public function create()
    {
        return view('laporans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bencana' => 'required|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keterangan_bencana' => 'required|string'
        ]);

        $gambarPath = $request->file('gambar')->store('gambar_laporans', 'public');

        Laporan::create([
            'nama_bencana' => $request->nama_bencana,
            'gambar' => $gambarPath,
            'keterangan_bencana' => $request->keterangan_bencana
        ]);

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil ditambahkan!');
    }

    public function show(Laporan $laporan)
    {
        return view('laporan.show', compact('laporan'));
    }

    public function edit(Laporan $laporan)
    {
        return view('laporans.edit', compact('laporan'));
    }

    public function update(Request $request, Laporan $laporan)
    {
        $request->validate([
            'nama_bencana' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keterangan_bencana' => 'required|string'
        ]);

        if ($request->hasFile('gambar')) {
            Storage::delete('public/' . $laporan->gambar);
            $gambarPath = $request->file('gambar')->store('gambar_laporans', 'public');
            $laporan->update(['gambar' => $gambarPath]);
        }

        $laporan->update([
            'nama_bencana' => $request->nama_bencana,
            'keterangan_bencana' => $request->keterangan_bencana
        ]);

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil diperbarui!');
    }

    public function destroy(Laporan $laporan)
    {
        Storage::delete('public/' . $laporan->gambar);
        $laporan->delete();
        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dihapus!');
    }
}

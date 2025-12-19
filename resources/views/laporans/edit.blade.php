@extends('layouts.master')

@section('main')
<h2>Edit Laporan</h2>
<form action="{{ route('laporan.update', $laporan) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label for="nama_bencana" class="block text-gray-700 text-sm font-bold mb-2">Nama Bencana</label>
        <input type="text" name="nama_bencana" value="{{ $laporan->nama_bencana }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>

    <div class="mb-4">
        <label for="gambar" class="block text-gray-700 text-sm font-bold mb-2">Gambar Bencana</label>
        <input type="file" name="gambar" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>

    <div class="mb-4">
        <label for="keterangan_bencana" class="block text-gray-700 text-sm font-bold mb-2">Keterangan</label>
        <textarea name="keterangan_bencana" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $laporan->keterangan_bencana }}</textarea>
    </div>

    <div class="flex items-center justify-center">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update</button>
    </div>
</form>
@endsection




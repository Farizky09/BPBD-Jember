@extends('layouts.master')

@section('main')
@section('breadcrumb')
    @php
        $links = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Manajemen Berita', 'url' => route('news.index')],
            ['name' => 'Detail', 'url' => ''],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Detail Berita" class="text-center" />
@endsection
<div class="mb-8 border-b pb-4">
    <h1 class="text-3xl font-bold text-gray-800">{{ $data->title }}</h1>
    <div class="mt-2 text-sm text-gray-500">
        <span class="mr-4">Slug: {{ $data->slug }}</span>
        <span>Status:
            <span
                class="capitalize px-2 py-1 rounded
                            @if ($data->status === 'draft') bg-yellow-100 text-yellow-800
                            @elseif($data->status === 'published') bg-green-100 text-green-800
                            @elseif($data->status === 'takedown') bg-red-100 text-red-800 @endif">
                {{ $data->status }}
            </span>
        </span>
    </div>
</div>

<!-- Grid Layout for Details -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Laporan Section -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-semibold text-gray-600 mb-2">Laporan Terkait</label>
        <p class="text-gray-800">{{ $data->confirmReports->report->kd_report ?? '-' }}</p>
    </div>

    <!-- Dates Section -->
    <div class="space-y-4">
        <div class="bg-gray-50 p-4 rounded-lg">
            <label class="block text-sm font-semibold text-gray-600 mb-2">Tanggal Publikasi</label>
            <p class="text-gray-800">
                {{ $data->published_at ? \Carbon\Carbon::parse($data->published_at)->translatedFormat('d F Y H:i') : '-' }}
            </p>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg">
            <label class="block text-sm font-semibold text-gray-600 mb-2">Tanggal Penurunan</label>
            <p class="text-gray-800">
                {{ $data->takedown_at ? \Carbon\Carbon::parse($data->takedown_at)->translatedFormat('d F Y H:i') : '-' }}
            </p>
        </div>
    </div>
</div>

<!-- Content Section -->
<div class="mb-8">
    <label class="block text-sm font-semibold text-gray-600 mb-4">Konten Lengkap</label>
    <div class="prose max-w-none text-gray-800">
        {!! $data->content !!}
    </div>
</div>

<!-- Gallery Section -->
<div class="w-full px-4">
    <div class="form-group mb-4">
        <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Gambar
            Bencana:</label>
        <div class="flex flex-wrap">
            @foreach ($data->imageNews as $image)
                <a href="{{ asset('storage/' . $image->image_path) }}" data-lightbox="bukti-pembayaran"
                    data-title="Gambar Bencana" class="h-auto">
                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Gambar Bencana"
                        class="mt-2 mr-4 mb-4 rounded-lg object-cover w-[200px] h-[200px]">
                </a>
            @endforeach
        </div>
    </div>
</div>

<!-- Back Button -->
<div class="mt-8 flex justify-end">
    <a href="{{ route('news.index') }}"
        class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors duration-200 flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
            </path>
        </svg>
        Kembali
    </a>
</div>
@endsection

@push('scripts')
<script>
    // Inisialisasi lightbox jika diperlukan
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'albumLabel': "Gambar %1 dari %2"
    })
</script>
@endpush

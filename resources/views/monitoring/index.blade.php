@extends('layouts.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3">Monitoring Level Air</h1>

                {{-- Debug Section --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Debug Information</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $folderPath = env('CCTV_DATA_PATH');
                            $csvPath = rtrim($folderPath, '/') . '/data_level_air.csv';
                        @endphp

                        <p><strong>Folder Path:</strong> {{ $folderPath }}</p>
                        <p><strong>CSV Path:</strong> {{ $csvPath }}</p>
                        <p><strong>CSV Exists:</strong> {{ file_exists($csvPath) ? '✅ Yes' : '❌ No' }}</p>
                        <p><strong>Total Data:</strong> {{ count($dataMonitoring ?? []) }}</p>

                        @if (!empty($dataMonitoring))
                            <p><strong>First Data Sample:</strong></p>
                            <pre>{{ print_r($dataMonitoring[0] ?? [], true) }}</pre>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if (empty($dataMonitoring))
            <div class="alert alert-info" role="alert">
                Tidak ada data monitoring tersedia.
            </div>
        @else
            <div class="row">
                @foreach ($dataMonitoring as $index => $data)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">{{ $data['waktu'] }}</h5>
                                <p class="card-text">
                                    <strong>Level Air:</strong> {{ $data['level'] }} meter
                                </p>

                                <div class="text-center">
                                    @php
                                        $imageUrl = route('monitoring.image', ['filename' => $data['gambar']]);
                                        $folderPath = env('CCTV_DATA_PATH');
                                        $directPath = rtrim($folderPath, '/') . '/' . $data['gambar'];
                                        $directPath2 = rtrim($folderPath, '/') . '/images/' . $data['gambar'];
                                    @endphp

                                    {{-- Debug info --}}
                                    <div class="debug-info" style="font-size: 0.8em; color: #666; margin-bottom: 10px;">
                                        <div>Image: {{ $data['gambar'] }}</div>
                                        <div>URL: <a href="{{ $imageUrl }}" target="_blank">{{ $imageUrl }}</a>
                                        </div>
                                        <div>Direct Path: {{ file_exists($directPath) ? '✅' : '❌' }} {{ $directPath }}
                                        </div>
                                        <div>With images/: {{ file_exists($directPath2) ? '✅' : '❌' }} {{ $directPath2 }}
                                        </div>
                                    </div>

                                    <img src="{{ $imageUrl }}" alt="Gambar Level Air" class="img-fluid rounded"
                                        onerror="this.onerror=null; this.src='https://via.placeholder.com/400x300/ff0000/ffffff?text=Image+Not+Found';">

                                    {{-- Test link --}}
                                    <div class="mt-2">
                                        <a href="{{ $imageUrl }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            Open Image in New Tab
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white">
                                <small class="text-muted">{{ $data['waktu'] }}</small>
                            </div>
                        </div>
                    </div>

                    {{-- Tampilkan hanya 3 data pertama untuk debugging --}}
                    @if ($index >= 2)
                        @break
                    @endif
                @endforeach
            </div>
        @endif
    </div>
@endsection

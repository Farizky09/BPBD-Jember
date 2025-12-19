@extends('layouts.master')
@push('style')
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --secondary: #f9fafb;
            --text: #1f2937;
            --text-light: #6b7280;

        }

        body {
            background-color: #f5f7fa;
            font-family: 'Inter', sans-serif;
            color: var(--text);
        }

        .detail-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .section-card {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--border);
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--primary);
        }

        .profile-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        @media (min-width: 768px) {
            .profile-container {
                flex-direction: row;
            }
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--primary);
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.2);
        }

        .profile-name {
            font-size: 1.2rem;
            font-weight: 600;
            margin-top: 0.75rem;
        }

        .info-grid {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
        }

        .info-box {
            background: var(--secondary);
            border-radius: calc(var(--radius) - 0.25rem);
            padding: 1rem;
        }

        .info-label {
            font-size: 0.85rem;
            color: var(--text-light);
            margin-bottom: 0.25rem;
            font-weight: 500;
        }

        .info-value {
            font-weight: 600;
            font-size: 1.05rem;
        }

        .identity-img {
            width: 180px;
            height: 120px;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 1px solid var(--border);
            transition: transform 0.3s ease;
        }

        .identity-img:hover {
            transform: scale(1.05);
        }

        .map-container {
            width: 100%;
            height: 250px;
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid var(--border);
            margin-top: 0.5rem;
        }

        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }

        .gallery-item {
            position: relative;
            width: 160px;
            height: 120px;
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid var(--border);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .gallery-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .gallery-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            text-align: center;
        }

        .description-box {
            background: var(--secondary);
            border-radius: calc(var(--radius) - 0.25rem);
            padding: 1rem;
            margin-top: 0.5rem;
            white-space: pre-line;
            line-height: 1.6;
        }

        .victims-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .victims-table th {
            background-color: var(--secondary);
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text);
            border-bottom: 2px solid var(--border);
        }

        .victims-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border);
            font-size: 0.9rem;
        }

        .victims-table tr:hover {
            background-color: #f3f4f6;
        }

        .no-data {
            padding: 1.5rem;
            text-align: center;
            color: var(--text-light);
            font-style: italic;
        }

        .impact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }
    </style>
@endpush

@section('main')
@section('breadcrumb')
    @php
        $links = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Rekapitulasi', 'url' => route('recap.index')],
            ['name' => 'Detail', 'url' => ''],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Detail Laporan" class="text-center" />
@endsection

<div class="detail-container">
    <!-- Detail Pelapor -->
    <div class="section-card border border-gray-200 rounded-md">
        <h2 class="section-title">Detail Pelapor</h2>
        <div class="profile-container  ">
            <div class="profile-info">
                <img src="{{ $data->report->user->image_avatar ? asset('storage/' . $data->report->user->image_avatar) : asset('assets/img/avatar/a-sm.jpg') }}"
                    alt="Foto Pelapor" class="profile-img">
                <p class="profile-name">{{ $data->report->user->name }}</p>
            </div>
            <div class="info-grid">
                <div class="info-box">
                    <div class="info-label">Nama Lengkap</div>
                    <div class="info-value">{{ $data->report->user->name }}</div>
                </div>
                <div class="info-box">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $data->report->user->email }}</div>
                </div>
                <div class="info-box">
                    <div class="info-label">No Telp</div>
                    <div class="info-value">{{ $data->report->user->phone_number }}</div>
                </div>
                <div class="info-box">
                    <div class="info-label">NIK</div>
                    <div class="info-value">{{ $data->report->user->nik ?: '-' }}</div>
                </div>
                @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super_admin'))
                    <div class="info-box">
                        <div class="info-label">Poin</div>
                        <div class="info-value">{{ $data->report->user->poin ?: '-' }}</div>
                    </div>
                @endif
                <div class="info-box">
                    <div class="info-label">Foto Identitas</div>
                    <a href="{{ $data->report->user->photo_identity_path ? asset('storage/' . $data->report->user->photo_identity_path) : asset('assets/img/avatar/dok.png') }}"
                        target="_blank">
                        <img src="{{ $data->report->user->photo_identity_path ? asset('storage/' . $data->report->user->photo_identity_path) : asset('assets/img/avatar/dok.png') }}"
                            class="identity-img">
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Laporan -->
    <div class="section-card border border-gray-200 rounded-md">
        <h2 class="section-title">Detail Laporan</h2>
        <div class="info-grid">
            <div class="info-box">
                <div class="info-label">Kode Laporan</div>
                <div class="info-value">{{ $data->report->kd_report }}</div>
            </div>
            <div class="info-box">
                <div class="info-label">Jenis Bencana</div>
                <div class="info-value">{{ $data->report->disasterCategory->name }}</div>
            </div>
            <div class="info-box">
                <div class="info-label">Status</div>
                <div class="info-value">{{ $data->status }}</div>
            </div>
            <div class="info-box">
                <div class="info-label">Tanggal Laporan</div>
                <div class="info-value">
                    {{ \Carbon\Carbon::parse($data->report->created_at)->translatedFormat('l, d F Y') }}
                </div>
            </div>
            <div class="info-box">
                <div class="info-label">Tingkat Bencana</div>
                <div class="info-value">{{ $data->disaster_level }}</div>
            </div>
            <div class="info-box">
                <div class="info-label">Disetujui Oleh</div>
                <div class="info-value">{{ $data->admin->name }}</div>
            </div>
        </div>

        <div class="mt-6">
            <div class="info-label">Deskripsi</div>
            <div class="description-box">
                {{ $data->report->description }}
            </div>
        </div>

        <div class="mt-6">
            <div class="info-label">Lokasi Kejadian</div>
            <div class="info-value mb-2">{{ $data->report->address }}</div>
            <div class="map-container">
                <iframe
                    src="https://maps.google.com/maps?q={{ $data->report->latitude }},{{ $data->report->longitude }}&hl=en&z=14&amp;output=embed"
                    width="100%" height="100%"></iframe>
            </div>
        </div>

        <div class="mt-6">
            <div class="info-label">Gambar Bencana</div>
            <div class="gallery">
                @foreach ($data->report->images as $image)
                    <a href="{{ asset('storage/' . $image->image_path) }}" data-lightbox="bencana"
                        class="gallery-item">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Bencana">
                        <div class="gallery-overlay">Lihat</div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Dampak Bencana -->
    <div class="section-card border border-gray-200 rounded-md">
        <h2 class="section-title">Dampak Bencana</h2>
        <div class="impact-grid">
            @php
                $impacts = [
                    'Rumah Rusak Ringan' => $data->disasterImpacts->lightly_damaged_houses ?? '-',
                    'Rumah Rusak Sedang' => $data->disasterImpacts->moderately_damaged_houses ?? '-',
                    'Rumah Rusak Berat' => $data->disasterImpacts->heavily_damaged_houses ?? '-',
                    'Fasilitas Umum Rusak' => $data->disasterImpacts->damaged_public_facilities ?? '-',
                    'Orang Hilang' => $data->disasterImpacts->missing_people ?? '-',
                    'Orang Terluka' => $data->disasterImpacts->injured_people ?? '-',
                    'Orang Meninggal' => $data->disasterImpacts->deceased_people ?? '-',
                    'Bayi / Balita' => $data->disasterImpacts->affected_babies ?? '-',
                    'lansia' => $data->disasterImpacts->affected_elderly ?? '-',
                    'Disabilitas' => $data->disasterImpacts->affected_disabled ?? '-',
                    'Ibu Hamil' => $data->disasterImpacts->affected_pregnant_women ?? '-',
                    'Umum' => $data->disasterImpacts->affected_general ?? '-',
                    'Total Korban (Bayi, Lansia, Disabilitas, Ibu Hamil, Umum)' => $data->disasterImpacts->affected_people ?? '-',
                ];
            @endphp
            @foreach ($impacts as $label => $value)
                <div class="info-box">
                    <div class="info-label">{{ $label }}</div>
                    <div class="info-value">{{ $value }}</div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            <div class="info-label">Deskripsi penanganan</div>
            <div class="description-box">
                @if (!empty($data->disasterImpacts->description))
                    {!! $data->disasterImpacts->description !!}
                @else
                    -
                @endif
            </div>
        </div>
        <div class="mt-6">
            <div class="info-label">Deskripsi Bantuan Logistik</div>
            <div class="description-box">
                @if (!empty($data->disasterImpacts->logistic_aid_description))
                    {!! $data->disasterImpacts->logistic_aid_description !!}
                @else
                    -
                @endif
            </div>
        </div>
    </div>

    <!-- Korban Bencana -->
    <div class="section-card border border-gray-200 rounded-md">
        <h2 class="section-title">Korban Bencana</h2>
        <div class="overflow-x-auto">
            <table class="victims-table" id="victims-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Usia</th>
                        <th>Jenis Kelamin</th>
                        <th>NIK</th>
                        <th>No. KK</th>
                        <th>Status Keluarga</th>
                        <th>No. Telp</th>
                        <th>Tempat Lahir</th>
                        <th>Tanggal Lahir</th>
                    </tr>
                </thead>
                {{-- Body tabel akan diisi oleh DataTables via AJAX --}}
                <tbody>
                    {{-- Data akan dimuat di sini --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {

            const reportId = {{ $data->id }};

            $('#victims-table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    // Panggil endpoint AJAX yang sudah Anda buat
                    "url": `{{ route('recap.victims.dataTable', ':id') }}`.replace(':id', reportId),
                    "type": "GET"
                },
                "columns": [{
                        "data": "fullname",
                        "name": "fullname"
                    },
                    {
                        "data": "age",
                        "name": "age"
                    },
                    {
                        "data": "gender",
                        "name": "gender"
                    },
                    {
                        "data": "nik",
                        "name": "nik"
                    },
                    {
                        "data": "kk",
                        "name": "kk"
                    },
                    {
                        "data": "family_status",
                        "name": "family_status"
                    },
                    {
                        "data": "phone_number",
                        "name": "phone_number"
                    },
                    {
                        "data": "birth_place",
                        "name": "birth_place"
                    },
                    {
                        "data": "birth_date",
                        "name": "birth_date"
                    },
                ],
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": false,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "search": "Cari:",
                    "paginate": {
                        "previous": "Sebelumnya",
                        "next": "Berikutnya"
                    },
                    "emptyTable": "Tidak ada data korban bencana",
                    "zeroRecords": "Tidak ada data yang cocok dengan pencarian"
                }
            });
        });
    </script>
@endpush
@endsection

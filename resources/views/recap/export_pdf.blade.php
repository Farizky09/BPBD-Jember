<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Laporan Bencana</title>
    <style>
        @page {
            margin: 2cm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
            color: #000;
        }

        .header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            width: 80px;
            height: 80px;
        }

        .header-text {
            text-align: center;
            flex: 1;
            margin-left: -80px;
        }

        .header-text h1 {
            margin: 0;
            font-size: 18pt;
        }

        .header-text h2 {
            margin: 5px 0;
            font-size: 14pt;
        }

        .header-text p {
            margin: 0;
            font-size: 10pt;
        }

        .periode {
            text-align: right;
            font-size: 10pt;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11pt;
            table-layout: fixed;
            /* Menentukan lebar kolom secara tetap */
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
            /* Mencegah teks keluar batas sel */
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        th {
            background-color: #f0f0f0;
        }

        /* Mengatur lebar setiap kolom dalam persentase */
        .col-no {
            width: 3%;
        }

        .col-kode {
            width: 8%;
        }

        .col-pengirim {
            width: 10%;
        }

        .col-kecamatan {
            width: 10%;
        }

        .col-alamat {
            width: 15%;
        }

        .col-kategori {
            width: 8%;
        }

        .col-tingkat {
            width: 8%;
        }

        .col-status {
            width: 6%;
        }

        .col-admin {
            width: 10%;
        }

        .col-kerusakan {
            width: 12%;
        }

        .col-korban {
            width: 10%;
        }

        /* Gaya tambahan untuk daftar dalam sel */
        ul {
            margin: 0;
            padding-left: 15px;
            list-style-type: disc;
        }

        li {
            margin-bottom: 2px;
        }


        .footer {
            margin-top: 50px;
            font-size: 11pt;
            text-align: right;
        }

        .signature {
            margin-top: 80px;
            text-align: right;
        }

        .signature p {
            margin: 2px 0;
        }
    </style>
</head>

<body>
    {{-- Header --}}
    <div class="header">
        <img src="{{ public_path('images/BPBD JEMBER2H.jpg') }}" alt="Logo BPBD" class="logo">
        <div class="header-text">
            <h1>Badan Penanggulangan Bencana Daerah</h1>
            <h2>Kabupaten Bumi Kita</h2>
            <p>Jl. Contoh No. 123, Kota Bencana, Indonesia</p>
        </div>
    </div>

    {{-- Periode --}}
    <div class="periode">
        Periode Laporan: <strong>{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</strong>
    </div>

    {{-- Table --}}
    <table>
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-kode">Kode Laporan</th>
                <th class="col-pengirim">Pengirim</th>
                <th class="col-kecamatan">Kecamatan</th>
                <th class="col-alamat">Alamat</th>
                <th class="col-kategori">Kategori</th>
                <th class="col-tingkat">Tingkat Bencana</th>
                <th class="col-status">Status</th>
                <th class="col-admin">Disetujui Oleh</th>
                <th class="col-kerusakan">Total Kerusakan</th>
                <th class="col-korban">Nama Korban</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $laporan)
                @php
                    // Ambil data dampak, pastikan relasi dimuat di controller
                    $impacts = $laporan->disasterImpacts;
                    // Ambil koleksi korban, jika ada
                    $victims = $impacts->disasterVictims ?? collect();

                    // Hitung total kerusakan rumah
                    $total_damage =
                        ($impacts->lightly_damaged_houses ?? 0) +
                        ($impacts->moderately_damaged_houses ?? 0) +
                        ($impacts->heavily_damaged_houses ?? 0);
                @endphp
                <tr>
                    <td class="col-no">{{ $index + 1 }}</td>
                    <td class="col-kode">{{ $laporan->report->kd_report ?? '-' }}</td>
                    <td class="col-pengirim">{{ $laporan->report->user->name ?? '-' }}</td>
                    <td class="col-kecamatan">{{ $laporan->report->subdistrict ?? '-' }}</td>
                    <td class="col-alamat">{{ $laporan->report->address ?? '-' }}</td>
                    <td class="col-kategori">{{ $laporan->report->disasterCategory->name ?? '-' }}</td>
                    <td class="col-tingkat">{{ $laporan->disaster_level ?? '-' }}</td>
                    <td class="col-status">{{ ucfirst($laporan->status) ?? '-' }}</td>
                    <td class="col-admin">{{ $laporan->admin->name ?? '-' }}</td>

                    {{-- Sel untuk Total Kerusakan --}}
                    <td class="col-kerusakan">
                        @if ($impacts)
                            <ul>
                                Total Rumah Rusak: {{ $total_damage }}<br>
                                Total Korban jiwa: {{ $impacts->affected_people }}
                                {{-- Fasilitas Umum Rusak: {{ $impacts->damaged_public_facilities ?? 0 }} --}}
                                <li>Rumah R. Ringan: {{ $impacts->lightly_damaged_houses ?? 0 }}</li>
                                <li>Rumah R. Sedang: {{ $impacts->moderately_damaged_houses ?? 0 }}</li>
                                <li>Rumah R. Berat: {{ $impacts->heavily_damaged_houses ?? 0 }}</li>
                                <li>Fasilitas Umum Rusak: {{ $impacts->damaged_public_facilities ?? 0 }}</li>
                                <li>Orang Hilang: {{ $impacts->missing_people ?? 0 }}</li>
                                <li>Orang Terluka: {{ $impacts->injured_people ?? 0 }}</li>
                                <li>Orang Meninggal: {{ $impacts->deceased_people ?? 0 }}</li>
                                <li>Total Terdampak: {{ $impacts->affected_people ?? 0 }}</li>
                                <li>Bayi/Balita: {{ $impacts->affected_babies ?? 0 }}</li>
                                <li>Lansia: {{ $impacts->affected_elderly ?? 0 }}</li>
                                <li>Disabilitas: {{ $impacts->affected_disabled ?? 0 }}</li>
                                <li>Ibu Hamil: {{ $impacts->affected_pregnant_women ?? 0 }}</li>
                                <li>Umum: {{ $impacts->affected_general ?? 0 }}</li>
                            </ul>
                        @else
                            -
                        @endif
                    </td>

                    {{-- Sel untuk Nama Korban --}}
                    <td class="col-korban">
                        @if ($victims->isNotEmpty())
                            <ul>
                                @foreach ($victims as $victim)
                                    <li>{{ $victim->fullname }} ({{ $victim->age }} th)</li>
                                @endforeach
                            </ul>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Footer --}}
    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>

    {{-- Tanda Tangan --}}
    <div class="signature">
        <p>Mengetahui,</p>
        <p>Kepala BPBD Kabupaten Bumi Kita</p>
        <br><br><br>
        <p><strong>___________________________</strong></p>
        <p>NIP: 1234567890</p>
    </div>
</body>

</html>

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
            justify-content: space-between;
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
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        th {
            background-color: #f0f0f0;
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
                <th>No</th>
                <th>Nama</th>
                <th style="width: 220px;">Alamat</th>
                <th>Status</th>
                <th>Tingkat Bencana</th>
                <th>{{ Auth::user()->hasRole(['admin', 'super_admin']) ? 'Pengirim' : 'Admin' }}</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->report->name ?? '-' }}</td>
                    <td>{{ $item->report->address ?? '-' }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                    <td>{{ $item->disaster_level ?? '-' }}</td>
                    <td>
                        @if (Auth::user()->hasRole(['admin', 'super_admin']))
                            {{ $item->report->user->name ?? '-' }}
                        @else
                            {{ $item->admin->name ?? '-' }}
                        @endif
                    </td>
                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
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

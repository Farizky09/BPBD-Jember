<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 14px;
            margin-bottom: 15px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 5px;
            vertical-align: top;
        }

        img {
            width: 100px;
            height: auto;
            margin-bottom: 10px;
            display: block;
        }

        .page-break {
            page-break-after: always;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>

<body>
  
    <div class="header">
        <div class="title">REKAPITULASI LAPORAN KEJADIAN BENCANA</div>
        @php

            $minDate = $data->min('created_at');
            $maxDate = $data->max('created_at');


            $startDateFormatted = $minDate ? \Carbon\Carbon::parse($minDate)->format('d F Y') : '';
            $endDateFormatted = $maxDate ? \Carbon\Carbon::parse($maxDate)->format('d F Y') : '';


            $sameMonth = $minDate && $maxDate &&
                         \Carbon\Carbon::parse($minDate)->format('m Y') ===
                         \Carbon\Carbon::parse($maxDate)->format('m Y');
        @endphp

        @if($minDate && $maxDate)
            @if($sameMonth)
                <div class="subtitle">
                    Periode: {{ \Carbon\Carbon::parse($minDate)->format('d') }} -
                    {{ \Carbon\Carbon::parse($maxDate)->format('d F Y') }}
                </div>
            @else
                <div class="subtitle">
                    Periode: {{ $startDateFormatted }} - {{ $endDateFormatted }}
                </div>
            @endif
        @else
            <div class="subtitle">Tidak ada data laporan</div>
        @endif
    </div>

    <!-- Tabel data -->
    <table width="100%">
        <tr>
            <th>NO.</th>
            <th>WAKTU DAN LOKASI</th>
            <th>JENIS DAN SEBAB/KRONOLOGIS BENCANA</th>
            <th>AKIBAT YANG DITIMBULKAN</th>
            <th>SUSUNAN GIAT/UPAYA YANG DILAKUKAN</th>
            <th>DOKUMENTASI</th>
        </tr>
        @foreach ($data as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <span class="title">Waktu:</span><br>
                    * Hari: {{ \Carbon\Carbon::parse($item->confirmReport->confirmed_at)->translatedFormat('l') }}<br>
                    * Tgl: {{ \Carbon\Carbon::parse($item->confirmReport->confirmed_at)->format('d F Y') }}<br>
                    * Pukul: {{ \Carbon\Carbon::parse($item->confirmReport->confirmed_at)->format('H:i') }}<br><br>
                    <span class="title">Lokasi:</span><br>
                    * {{ $item->confirmReport->report->address }}<br>
                </td>
                <td>
                    <span class="title">Jenis:</span><br>
                    {{ $item->confirmReport->report->disasterCategory->name }}<br>
                    <span class="title">Kronologis:</span><br>
                    {!! $item->disaster_chronology !!}
                </td>
                <td>
                    {!! $item->disaster_impact !!}
                </td>
                <td>
                    {!! $item->disaster_response !!}
                </td>
                <td>
                    @foreach ($item->images as $photo)
                        @php

                            $path = storage_path('app/public/' . $photo->image_path);
                            if (file_exists($path)) {
                                $type = pathinfo($path, PATHINFO_EXTENSION);
                                $dataImg = file_get_contents($path);
                                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($dataImg);
                            } else {
                                $base64 = '';
                            }
                        @endphp
                        @if($base64)
                            <img src="{{ $base64 }}">
                        @endif
                    @endforeach
                </td>
            </tr>
        @endforeach
    </table>

    <!-- Footer -->
    <div class="footer">
        Dicetak pada: {{ now()->format('d F Y H:i') }}
    </div>
</body>

</html>

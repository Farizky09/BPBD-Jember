<table border="1" cellspacing="0" cellpadding="5"
    style="border-collapse: collapse; width: 100%; font-family: Arial, sans-serif;">
    <thead>
        <tr>
            {{-- Sesuaikan colspan dengan jumlah kolom baru (11 kolom) --}}
            <th align="center" colspan="11" style="font-size: 18px; background: #f2f2f2; padding: 10px;">
                Laporan Riwayat
            </th>
        </tr>
        <tr style="background: #e6e6e6;">
            <th style="width: 40px; text-align: center;">#</th>
            <th style="width: 180px; text-align: left;">Kode Laporan</th>
            <th style="width: 160px; text-align: left;">Pengirim</th>
            <th style="width: 120px; text-align: left;">Kecamatan</th>
            <th style="width: 900px; text-align: left;">Alamat</th>
            <th style="width: 180px; text-align: left;">Kategori Bencana</th>
            <th style="width: 120px; text-align: center;">Tingkat Bencana</th>
            <th style="width: 120px; text-align: center;">Status</th>
            <th style="width: 160px; text-align: left;">Disetujui Oleh</th>
            <th style="width: 250px; text-align: left;">Total Kerusakan</th> {{-- Kolom baru --}}
            <th style="width: 200px; text-align: left;">Nama Korban</th> {{-- Kolom baru --}}
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $laporan)
            @php
                // Ambil data dampak, pastikan relasi dimuat di controller
                $impacts = $laporan->disasterImpacts;
                // Ambil koleksi korban, jika ada
                $victims = $impacts->disasterVictims ?? collect();
            @endphp
            <tr>
                <td style="text-align: center; vertical-align: top;">{{ $index + 1 }}</td>
                <td style="text-align: left; vertical-align: top;">{{ $laporan->report->kd_report ?? '-' }}</td>
                <td style="text-align: left; vertical-align: top;">{{ $laporan->report->user->name ?? '-' }}</td>
                <td style="text-align: left; vertical-align: top;">{{ $laporan->report->subdistrict ?? '-' }}</td>
                <td style="text-align: left; vertical-align: top;">{{ $laporan->report->address ?? '-' }}</td>
                <td style="text-align: left; vertical-align: top;">{{ $laporan->report->disasterCategory->name ?? '-' }}</td>
                <td style="text-align: center; vertical-align: top;">{{ $laporan->disaster_level ?? '-' }}</td>
                <td style="text-align: center; vertical-align: top;">{{ ucfirst($laporan->status) ?? '-' }}</td>
                <td style="text-align: left; vertical-align: top;">{{ $laporan->admin->name ?? '-' }}</td>


                <td>
                    @if ($impacts)
                        Total Rumah Rusak:
                        {{ ($impacts->lightly_damaged_houses ?? 0) + ($impacts->moderately_damaged_houses ?? 0) + ($impacts->heavily_damaged_houses ?? 0) }}
                        <br>Fasilitas Umum Rusak: {{ $impacts->damaged_public_facilities ?? 0 }}
                        <br>Orang Hilang: {{ $impacts->missing_people ?? 0 }}
                        <br>Orang Terluka: {{ $impacts->injured_people ?? 0 }}
                        <br>Orang Meninggal: {{ $impacts->deceased_people ?? 0 }}
                        <br>Total Terdampak: {{ $impacts->affected_people ?? 0 }}
                        <br>Bayi/Balita: {{ $impacts->affected_babies ?? 0 }}
                        <br>Lansia: {{ $impacts->affected_elderly ?? 0 }}
                        <br>Disabilitas: {{ $impacts->affected_disabled ?? 0 }}
                        <br>Ibu Hamil: {{ $impacts->affected_pregnant_women ?? 0 }}
                        <br>Umum: {{ $impacts->affected_general ?? 0 }}
                    @else
                        -
                    @endif
                </td>


                <td style="text-align: left; vertical-align: top;">
                    @if ($victims->isNotEmpty())
                        @foreach ($victims as $victim)
                            - {{ $victim->fullname }} ({{ $victim->age }} th)<br>
                        @endforeach
                    @else
                        -
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

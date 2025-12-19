<table border="1" cellspacing="0" cellpadding="5"
    style="border-collapse: collapse; width: 100%; font-family: Arial, sans-serif;">
    <thead>
        <tr>
            <th align="center" colspan="9" style="font-size: 18px; background: #f2f2f2; padding: 10px;">Laporan Dokumen Kategori
            </th>
        </tr>
        <tr style="background: #e6e6e6;">
            <th style="width: 60px; text-align: center;">No</th>
            <th style="width: 180px; text-align: center;">Kode Laporan</th>
            <th style="width: 160px; text-align: center;">Nama</th>
            <th style="width: 120px; text-align: center;">Kecamatan</th>
            <th style="width: 160px; text-align: center;">Alamat</th>
            <th style="width: 160px; text-align: center;">Jenis Bencana</th>
            <th style="width: 120px; text-align: center;">Kronologi Bencana</th>
            <th style="width: 160px; text-align: center;">Dampak Bencana</th>
            <th style="width: 160px; text-align: center;">Penanganan Bencana</th>


        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td>{{ $row->confirmReport->report->kd_report ?? '-' }}</td>
                <td>{{ $row->confirmReport->report->user->name ?? '-' }}</td>
                <td>{{ $row->confirmReport->report->subdistrict ?? '-' }}</td>
                <td>{{ $row->confirmReport->report->address ?? '-' }}</td>
                <td>{{ $row->confirmReport->report->disasterCategory->name ?? '-' }}</td>
                <td>{{ strip_tags ($row->disaster_chronology ?? '-') }}</td>
                <td>{{ strip_tags($row->disaster_impact ?? '-') }}</td>
                <td>{{ $row->disaster_response ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

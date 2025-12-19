<table border="1" cellspacing="0" cellpadding="5"
    style="border-collapse: collapse; width: 100%; font-family: Arial, sans-serif;">
    <thead>
        <tr>
            <th align="center" colspan="6" style="font-size: 18px; background: #f2f2f2; padding: 10px;">Laporan Riwayat
            </th>
        </tr>
        <tr style="background: #e6e6e6;">
            <th style="width: 60px; text-align: center;">#</th>
            <th style="width: 180px; text-align: left;">Nama</th>
            <th style="width: 550px; text-align: left;">Alamat</th>
            <th style="width: 160px; text-align: left;">Pengirim</th>
            <th style="width: 120px; text-align: center;">Status</th>
            <th style="width: 160px; text-align: center;">Tgl Dibuat</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td>{{ $row->report->name }}</td>
                <td>{{ $row->report->address }}</td>
                <td>{{ $row->report->user->name }}</td>
                <td style="text-align: center;">{{ $row->status }}</td>
                <td style="text-align: center;">{{ date('d-m-Y H:i', strtotime($row->created_at)) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

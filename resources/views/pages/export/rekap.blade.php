<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
        margin: 20px;
    }

    header {
        text-align: center;
        margin-bottom: 30px;
    }

    header h1 {
        margin: 0;
        font-size: 10px;
        font-weight: bold;
    }

    .header h2 {
        margin: 5px 0;
        font-size: 16px;
        font-weight: normal;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 8px;
        text-align: center;
    }

    th {
        background-color: #f0f0f0;
        font-weight: bold;
    }

    .text-left {
        text-align: left;
    }

    .footer {
        margin-top: 30px;
        text-align: right;
    }
</style>

<body>

    <div class="header">
        <h1>REKAP ABSENSI SISWA</h1>
        <h2>{{ $title }}</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th width="10%">No</th>
                <th width="15%">NIS</th>
                <th width="25%">Nama Siswa</th>
                <th width="15%">Jenis Kelamin</th>
                <th width="10%">Hadir</th>
                <th width="10%">Sakit</th>
                <th width="10%">Izin</th>
                <th width="10%">Alpa</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kelas->siswa as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nis }}</td>
                    <td class="text-left">{{ $item->nama }}</td>
                    <td>{{ $item->jenis_kelamin }}</td>
                    <td>
                        {{ $item->absensi()->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'Hadir')->count() }}
                    </td>
                    <td>
                        {{ $item->absensi()->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'Sakit')->count() }}
                    </td>
                    <td>
                        {{ $item->absensi()->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'Izin')->count() }}
                    </td>
                    <td>
                        {{ $item->absensi()->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'Alpa')->count() }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #f0f0f0">
                <td colspan="4">Total</td>
                <td>{{ number_format($totalHadir) }}</td>
                <td>{{ number_format($totalSakit) }}</td>
                <td>{{ number_format($totalIzin) }}</td>
                <td>{{ number_format($totalAlpa) }}</td>
            </tr>
        </tfoot>
    </table>
    <div class="footer">
        <p>Dicetak pada: {{ date('d/n/Y H:i') }}</p>
    </div>
</body>

</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export PDF - Rekap Sesi Absensi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #222;
            padding-bottom: 15px;
        }
        h1 {
            font-size: 18px;
            margin: 0 0 5px;
            text-transform: uppercase;
        }
        .meta {
            margin-bottom: 20px;
        }
        .meta strong {
            display: inline-block;
            width: 120px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #777;
            text-align: right;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $title }}</h1>
        <p style="margin:0;">Laporan Riwayat Sesi Mata Pelajaran</p>
    </div>

    <div class="meta">
        <div><strong>Kelas</strong>: {{ $kelas->nama_kelas }}</div>
        <div><strong>Tanggal Cetak </strong>: {{ now()->translatedFormat('d F Y H:i:s') }}</div>
        <div><strong>Total Sesi Cetak</strong>: {{ $sesi->count() }} Sesi</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Mata Pelajaran</th>
                <th>Guru / Pengajar</th>
                <th class="text-center">Hadir</th>
                <th class="text-center">Izin</th>
                <th class="text-center">Sakit</th>
                <th class="text-center">Alpa</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sesi as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $row->tanggal->translatedFormat('d M Y') }}</td>
                    <td>{{ $row->started_at?->format('H:i') }} - {{ $row->closed_at?->format('H:i') ?? '...' }}</td>
                    <td>
                        <strong>{{ $row->jadwalPelajaran->pengampu->mataPelajaran->nama }}</strong><br>
                        <span style="font-size: 10px; color:#555;">{{ $row->topik ?? 'Tanpa Topik' }}</span>
                    </td>
                    <td>{{ $row->guru->nama }}</td>
                    <td class="text-center">{{ $row->hadir_count }}</td>
                    <td class="text-center">{{ $row->izin_count }}</td>
                    <td class="text-center">{{ $row->sakit_count }}</td>
                    <td class="text-center">{{ $row->alpa_count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada riwayat sesi yang sesuai dengan filter pencarian.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak dari Sistem Admin SmarSchool &copy; {{ date('Y') }}
    </div>

</body>
</html>

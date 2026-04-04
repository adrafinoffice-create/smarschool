@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="font-bold mb-3">{{ $title }}</h2>
        <div class="d-flex gap-1">
            <a href="{{ route('guru.rekap-absensi.export', ['id' => $kelas->id, 'bulan' => 'bulan', 'tahun' => $tahun]) }}"
                class="btn btn-danger btn-sm">
                <i class="bx bx-file-pdf sm"></i> Export PDF
            </a>
            <a href="" class="btn btn-light border">
                <i class="bx bx-arrow-back"></i>Kembali
            </a>
        </div>
    </div>

    <div class="card border-0">
        <div class="card-body p-4">
            <div class="table-responsive mb-4">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Jenis Kelamin</th>
                            <th>Total Hadir</th>
                            <th>Total Sakit</th>
                            <th>Total Izin</th>
                            <th>Total Alpa</th>

                        </tr>
                    </thead>
                    <tbody>

                        @php
                            $totalHadir = 0;
                            $totalSakit = 0;
                            $totalIzin = 0;
                            $totalAlpa = 0;
                        @endphp

                        @foreach ($kelas->siswa as $item)
                            @php
                                $totalHadir += $item
                                    ->absensi()
                                    ->whereMonth('tanggal', $bulan)
                                    ->whereYear('tanggal', $tahun)
                                    ->where('status', 'Hadir')
                                    ->count();
                                $totalSakit += $item
                                    ->absensi()
                                    ->whereMonth('tanggal', $bulan)
                                    ->whereYear('tanggal', $tahun)
                                    ->where('status', 'Sakit')
                                    ->count();

                                $totalIzin += $item
                                    ->absensi()
                                    ->whereMonth('tanggal', $bulan)
                                    ->whereYear('tanggal', $tahun)
                                    ->where('status', 'Izin')
                                    ->count();

                                $totalAlpa += $item
                                    ->absensi()
                                    ->whereMonth('tanggal', $bulan)
                                    ->whereYear('tanggal', $tahun)
                                    ->where('status', 'Alpa')
                                    ->count();
                            @endphp

                            <tr>
                                <td>{{ $item->nis }}</td>
                                <td>{{ $item->nama }}</td>
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
                        <tr>
                            <th colspan="3">Total</th>
                            <th>{{ number_format($totalHadir) }}</th>
                            <th>{{ number_format($totalSakit) }}</th>
                            <th>{{ number_format($totalIzin) }}</th>
                            <th>{{ number_format($totalAlpa) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

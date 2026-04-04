@extends('layouts.admin')

@section('content')
    <h2 class="font-bold mb-3">{{ $title }}</h2>
    <div class="card border-0">
        <div class="card-body p-4">
            <div class="table-responsive mb-4">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Total Siswa</th>
                            <th>Total Hadir</th>
                            <th>Total Sakit</th>
                            <th>Total Izin</th>
                            <th>Total Alpa</th>
                            <th></th>
                        </tr>
                    </thead>
                    <thead>
                        @foreach ($absensi as $item)
                            <tr>
                                <td>{{ Carbon\Carbon::parse($item->tanggal)->translatedFormat('j F Y') }}</td>
                                <td>{{ $kelas->siswa->count() }}</td>
                                <td>
                                    {{ $kelas->absensi->where('tanggal', $item->tanggal)->where('status', 'Hadir')->count() }}
                                </td>
                                <td>s
                                    {{ $kelas->absensi->where('tanggal', $item->tanggal)->where('status', 'Sakit')->count() }}
                                </td>
                                <td>
                                    {{ $kelas->absensi->where('tanggal', $item->tanggal)->where('status', 'Izin')->count() }}
                                </td>
                                <td>
                                    {{ $kelas->absensi->where('tanggal', $item->tanggal)->where('status', 'Alpa')->count() }}
                                </td>
                                <td>
                                    <a href="{{ route('kehadiran.detail', ['id' => $kelas->id, 'tanggal' => $item->tanggal]) }}"
                                        class="btn btn-sm btn-success" style="width: max-content">
                                        <i class="bx bx-file"></i>Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

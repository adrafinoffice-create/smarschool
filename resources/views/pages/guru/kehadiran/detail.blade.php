@extends('layouts.admin')

@section('content')
    <h2 class="font-bold mb-3">{{ $title }}</h2>

    <div class="card border-0">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Jenis Kelamin</th>
                            <th>Status Kehadiran</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($absensi as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->siswa->nis }}</td>
                                <td>{{ $item->siswa->nama }}</td>
                                <td>{{ $item->siswa->jenis_kelamin }}</td>
                                <td>
                                    @if ($item->status == 'Hadir')
                                        <span class="badge bg-succes">Hadir</span>
                                    @elseif($item->status == 'Sakit')
                                        <span class="badge bg-warning">Sakit</span>
                                    @elseif($item->status == 'Izin')
                                        <span class="badge bg-info">Izin</span>
                                    @elseif($item->status == 'Alpa')
                                        <span class="badge bg-danger">Alpa</span>
                                    @endif
                                </td>
                                <td>{{ $item->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

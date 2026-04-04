@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Rekap Absensi semua kelas</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane show active" id="state-saving-preview">
                                <table id="state-saving-datatable" class="table activate-select dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Kelas</th>
                                            <th>Nama Wali Kelas</th>
                                            <th>Tahun Ajaran</th>
                                            <th>Jumlah Siswa</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kelas as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->nama_kelas }}</td>
                                                <td>{{ $item->waliKelas->nama }}</td>
                                                <td>{{ $item->tahunAjaran->tahun_ajaran }}</td>
                                                <td>{{ $item->siswa->count() }} Siwa</td>
                                                <td>
                                                    <div class="d-flex gap-3">
                                                        <a href="{{ route('admin.kehadiran.show', $item->id) }}"
                                                            class="btn btn-primary btn-sm">
                                                            Lihat Kehadiran
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

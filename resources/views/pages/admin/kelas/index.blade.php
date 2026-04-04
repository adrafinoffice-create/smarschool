@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="text-dark fw-bold">Data Wali Kelas</h4>
        <a href="{{ route('kelas.create') }}" class="btn btn-primary btn-sm">
            Tambah Kelas
        </a>
    </div>
    <div class="row">
        <div class="card border-0">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
                    </div>
                @endif
                <div class="card border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
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
                                                    <a href="{{ route('kelas.edit', $item->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('kelas.destroy', $item->id) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button class="btn btn-warning btn-sm" type="submit"
                                                            onclick="return confirm('Apakah kamu yakin menghapus data ini.?')">
                                                            Hapus
                                                        </button>
                                                    </form>
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
@endsection

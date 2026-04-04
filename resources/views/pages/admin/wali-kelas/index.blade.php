@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="text-dark fw-bold">Data Wali Kelas</h4>
        <a href="{{ route('wali-kelas.create') }}" class="btn btn-primary btn-sm">
            Tambah Data
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
                                        <th>NIP</th>
                                        <th>Nama Lengkap</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Tempat Tanggal Lahir</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($wali_kelas as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->nip }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ $item->jenis_kelamin }}</td>
                                            <td>{{ $item->tempat_lahir . ',' . $item->tanggal_lahir }}</td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('wali-kelas.edit', $item->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="bx bx-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('wali-kelas.destroy', $item->id) }}"
                                                        method="post">
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

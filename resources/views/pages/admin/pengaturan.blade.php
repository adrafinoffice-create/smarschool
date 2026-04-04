@extends('layouts.admin')

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{ $title }}</h4>
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <form action="{{ route('pengaturan.update') }}" enctype="multipart/form-data" method="POST">
                            @csrf

                            <div class="row g-3 mb-3">
                                <div class="mb-3 col-md-6">
                                    <label for="nama_sekolah" class="form-label">Nama Sekolah</label>
                                    <input type="text" name="nama_sekolah" id="nama_sekolah"
                                        value="{{ old('nama_sekolah', $pengaturan->nama_sekolah) }}"
                                        class="form-control @error('nama_sekolah') is-invalid @enderror">
                                    @error('nama_sekolah')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="logo" class="form-label">Logo Sekolah</label>
                                    <input type="file" accept="image/*" name="logo" id="logo"
                                        class="form-control @error('logo') is-invalid @enderror">
                                    @error('logo')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <input type="text" name="alamat" id="alamat"
                                        value="{{ old('alamat', $pengaturan->alamat) }}"
                                        class="form-control @error('alamat') is-invalid @enderror">
                                    @error('alamat')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class=" col-md-6">
                                    <label for="jam_masuk" class="form-label">Jam Masuk</label>
                                    <input type="text" name="jam_masuk" id="jam_masuk"
                                        value="{{ old('jam_masuk', $pengaturan->jam_masuk) }}"
                                        class="form-control @error('jam_masuk') is-invalid @enderror">
                                    @error('jam_masuk')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="jam_pulang" class="form-label">Jam Pulang</label>
                                    <input type="text" name="jam_pulang" id="jam_pulang"
                                        value="{{ old('jam_pulang', $pengaturan->jam_pulang) }}"
                                        class="form-control @error('jam_pulang') is-invalid @enderror">
                                    @error('jam_pulang')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                            </div>

                            <button class="btn btn-primary btn-sm" type="submit">Simpan Perubahan</button>

                    </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

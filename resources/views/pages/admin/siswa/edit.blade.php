@extends('layouts.admin')

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{ $title }}</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <!-- Start Inpur-->
                        <form action="{{ route('siswa.update', $siswa->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row g-2">
                                <div class="mb-3 col-md-6">
                                    <label for="kelas" class="form-label">Kelas</label>
                                    <select name="kelas_id" id="kelas" class="form-select">
                                        <option value="" selected disabled>-- Pilih Kelas --</option>
                                        @foreach ($kelas as $kl)
                                            <option value="{{ $kl->id }}" @selected(old('kelas_id', $siswa->kelas_id) == $kl->id)>
                                                {{ $kl->nama_kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kelas_id')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="nama" class="form-label">Nama Lengkap</label>
                                    <input type="text" name="nama"
                                        id="nama"class="form-control @error('nama') is-invalid @enderror"
                                        placeholder="Masukan Nama Anda" value="{{ old('nama', $siswa->nama) }}">
                                    @error('nama')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="nis" class="form-label">NIS</label>
                                    <input type="text" name="nis" id="nis"
                                        class="form-control @error('nis') is-invalid @enderror"
                                        value="{{ old('nis', $siswa->nis) }}">
                                    @error('nis')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" id="tempat_lahir"
                                        class="form-control @error('tempat_lahir') is-invalid @enderror"
                                        value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}">

                                    @error('tempat_lahir')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                    class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                    value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}">
                                @error('tanggal_lahir')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="row g-2">
                                <div class="mb-3 col-md-6">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" id="jenis_kelamin"
                                        class="form-select @error('jenis_kelamin') is-invalid @enderror">
                                        <option value="" disabled>-- Pilih Jenis Kelamin --</option>
                                        <option value="Laki-laki" @selected(old('jenis_kelamin', $siswa->jenis_kelamin) == 'Laki-laki')>Laki-laki</option>
                                        <option value="Perempuan" @selected(old('jenis_kelamin', $siswa->jenis_kelamin) == 'Perempuan')>Perempuan</option>

                                    </select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <input type="text" name="alamat" id="alamat"
                                        class="form-control @error('alamat') is-invalid @enderror"
                                        value="{{ old('alamat', $siswa->alamat) }}">

                                    @error('alamat')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="nama_orang_tua" class="form-label">Nama Orang Tua</label>
                                    <input type="text" name="nama_orang_tua" id="nama_orang_tua"
                                        value="{{ old('nama_orang_tua', $siswa->nama_orang_tua) }}"class="form-control @error('nama_orang_tua') is-invalid @enderror">
                                    @error('nama_orang_tua')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="no_hp" class="form-label">Nomor HP</label>
                                    <input type="text" name="no_hp" id="no_hp"
                                        value="{{ old('no_hp', $siswa->no_hp) }}"class="form-control @error('no_hp') is-invalid @enderror">
                                    @error('no_hp')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="d-flex gap-1 mt-3">
                                <button class="btn btn-primary btn-sm" type="submit">Simpan Perubahan</button>
                                <a href="{{ route('siswa.index') }}" class="btn btn-light border">Batal</a>
                            </div>
                        </form>
                        <!-- End Input-->

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

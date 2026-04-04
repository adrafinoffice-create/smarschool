@extends('layouts.admin')

@section('content')
    <h2 class="fw-bold mb-3">Edit Data</h2>

    <div class="card border-0">
        <div class="card-body">
            <form action="{{ route('wali-kelas.update', $wali_kelas->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-2">
                    <div class="mb-3 col-md-6">
                        <label for="nip" class="form-label">NIP</label>
                        <input type="text" name="nip" id="nip"
                            class="form-control @error('nip') is-invalid @enderror"
                            value="{{ old('nip', $wali_kelas->nip) }}">
                        @error('nip')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama"
                            id="nama"class="form-control @error('nama') is-invalid @enderror"
                            placeholder="Masukan Nama Anda" value="{{ old('nama', $wali_kelas->nama) }}">
                        @error('nama')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row g-2">
                    <div class="mb-3 col-md-6">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin"
                            class="form-select @error('jenis_kelamin') is-invalid @enderror">
                            <option>-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki" @selected(old('jenis_kelamin', $wali_kelas->jenis_kelamin) == 'Laki-laki')>Laki-laki</option>
                            <option value="Perempuan" @selected(old('jenis_kelamin', $wali_kelas->jenis_kelamin) == 'Perempuan')>Perempuan</option>

                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir"
                            id="tanggal_lahir"class="form-control @error('tanggal_lahir') is-invalid  @enderror"
                            value="{{ old('tanggal_lahir', $wali_kelas->tanggal_lahir) }}">
                        @error('tanggal_lahir')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir"
                            class="form-control @error('tempat_lahir', $wali_kelas->tempat_lahir) is-invalid @enderror"
                            value="{{ old('tempat_lahir') }}">

                        @error('tempat_lahir')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" name="alamat"
                            id="alamat"class="form-control @error('alamat') is-invalid @enderror"
                            value="{{ old('alamat', $wali_kelas->alamat) }}">
                        @error('alamat')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input type="email" name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $wali_kelas->user->email) }}">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password"
                            class="form-control @error('password') is-invalid  @enderror">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="d-flex gap-1 mt-3">
                    <button class="btn btn-primary btn-sm" type="submit">Simpan Baru</button>
                    <a href="{{ route('wali-kelas.index') }}" class="btn btn-light border">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

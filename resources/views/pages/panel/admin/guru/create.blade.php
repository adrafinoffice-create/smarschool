@extends('layouts.panel')

@section('content')
    <section class="page-shell">
        <div>
            <h1 class="panel-title">Tambah Guru</h1>
            <p class="panel-subtitle">Buat akun guru agar dapat ditugaskan ke mapel dan mengakses panel guru.</p>
        </div>

        <div class="section-card max-w-5xl">
            <form action="{{ route('admin.guru.store') }}" method="POST" class="grid gap-5 md:grid-cols-2" data-enhanced-form>
                @csrf
                <div>
                    <label class="form-label" for="nip">NIP</label>
                    <input class="form-input" id="nip" name="nip" type="text" value="{{ old('nip') }}" required>
                    @error('nip') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label" for="nama">Nama Lengkap</label>
                    <input class="form-input" id="nama" name="nama" type="text" value="{{ old('nama') }}" required>
                    @error('nama') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label" for="email">Email</label>
                    <input class="form-input" id="email" name="email" type="email" value="{{ old('email') }}" required>
                    @error('email') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label" for="password">Password</label>
                    <input class="form-input" id="password" name="password" type="password" required minlength="8">
                    @error('password') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label" for="jenis_kelamin">Jenis Kelamin</label>
                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="">Pilih jenis kelamin</option>
                        <option value="Laki-laki" @selected(old('jenis_kelamin') === 'Laki-laki')>Laki-laki</option>
                        <option value="Perempuan" @selected(old('jenis_kelamin') === 'Perempuan')>Perempuan</option>
                    </select>
                    @error('jenis_kelamin') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label" for="tempat_lahir">Tempat Lahir</label>
                    <input class="form-input" id="tempat_lahir" name="tempat_lahir" type="text" value="{{ old('tempat_lahir') }}" required>
                    @error('tempat_lahir') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label" for="tanggal_lahir">Tanggal Lahir</label>
                    <input class="form-input" id="tanggal_lahir" name="tanggal_lahir" type="date" value="{{ old('tanggal_lahir') }}" required>
                    @error('tanggal_lahir') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="form-label" for="alamat">Alamat</label>
                    <textarea class="form-input" id="alamat" name="alamat" rows="4" required>{{ old('alamat') }}</textarea>
                    @error('alamat') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2 flex gap-3">
                    <button class="btn-primary" type="submit">Simpan Guru</button>
                    <a href="{{ route('admin.guru.index') }}" class="btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </section>
@endsection

@extends('layouts.panel')

@section('content')
    <section class="page-shell">
        <div>
            <h1 class="panel-title">Pengaturan Sekolah</h1>
            <p class="panel-subtitle">Informasi dasar sekolah yang dipakai pada kartu pelajar dan profil sistem.</p>
        </div>

        <div class="section-card max-w-5xl">
            <form action="{{ route('admin.pengaturan.update') }}" enctype="multipart/form-data" method="POST"
                class="grid gap-5 md:grid-cols-2" data-enhanced-form>
                @csrf

                <div>
                    <label class="form-label" for="nama_sekolah">Nama Sekolah</label>
                    <input class="form-input" id="nama_sekolah" name="nama_sekolah" type="text"
                        value="{{ old('nama_sekolah', $pengaturan->nama_sekolah ?? '') }}" required>
                    @error('nama_sekolah') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label" for="logo">Logo Sekolah</label>
                    <input class="form-input" id="logo" name="logo" type="file" accept="image/*">
                    @error('logo') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="form-label" for="alamat">Alamat</label>
                    <textarea class="form-input" id="alamat" name="alamat" rows="4" required>{{ old('alamat', $pengaturan->alamat ?? '') }}</textarea>
                    @error('alamat') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label" for="jam_masuk">Jam Masuk Global</label>
                    <input class="form-input" id="jam_masuk" name="jam_masuk" type="time" required
                        value="{{ old('jam_masuk', $pengaturan->jam_masuk ?? '') }}">
                    @error('jam_masuk') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label" for="jam_pulang">Jam Pulang Global</label>
                    <input class="form-input" id="jam_pulang" name="jam_pulang" type="time" required
                        value="{{ old('jam_pulang', $pengaturan->jam_pulang ?? '') }}">
                    @error('jam_pulang') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <button class="btn-primary" type="submit">Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </section>
@endsection

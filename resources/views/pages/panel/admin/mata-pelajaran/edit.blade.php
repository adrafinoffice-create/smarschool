@extends('layouts.panel')

@section('content')
    <section class="page-shell">
        <div>
            <h1 class="panel-title">Edit Mata Pelajaran</h1>
            <p class="panel-subtitle">Perbarui data mapel yang digunakan dalam jadwal.</p>
        </div>

        <div class="section-card max-w-4xl">
            <form action="{{ route('admin.mata-pelajaran.update', $mataPelajaran) }}" method="POST" class="grid gap-5 md:grid-cols-2" data-enhanced-form>
                @csrf
                @method('PUT')
                <div>
                    <label class="form-label" for="kode">Kode</label>
                    <input class="form-input" id="kode" name="kode" type="text" value="{{ old('kode', $mataPelajaran->kode) }}" required>
                    @error('kode') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label" for="nama">Nama</label>
                    <input class="form-input" id="nama" name="nama" type="text" value="{{ old('nama', $mataPelajaran->nama) }}" required>
                    @error('nama') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="form-label" for="deskripsi">Deskripsi</label>
                    <textarea class="form-input" id="deskripsi" name="deskripsi" rows="4">{{ old('deskripsi', $mataPelajaran->deskripsi) }}</textarea>
                    @error('deskripsi') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2 flex gap-3">
                    <button class="btn-primary" type="submit">Simpan Perubahan</button>
                    <a href="{{ route('admin.mata-pelajaran.index') }}" class="btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </section>
@endsection

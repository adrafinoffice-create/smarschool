@extends('layouts.panel')

@section('content')
    <section class="page-shell">
        <div>
            <h1 class="panel-title">Tambah Tahun Ajaran</h1>
            <p class="panel-subtitle">Tambahkan periode akademik sebelum menyusun kelas, pengampu, dan jadwal.</p>
        </div>

        <div class="section-card max-w-3xl">
            <form action="{{ route('admin.tahun-ajaran.store') }}" method="POST" class="grid gap-5 md:grid-cols-2" data-enhanced-form>
                @csrf
                <div>
                    <label class="form-label" for="tahun_ajaran">Tahun Ajaran</label>
                    <input class="form-input" id="tahun_ajaran" name="tahun_ajaran" type="text"
                        value="{{ old('tahun_ajaran') }}" placeholder="2026/2027" required>
                    @error('tahun_ajaran')
                        <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label" for="semester">Semester</label>
                    <select class="form-select" id="semester" name="semester" required>
                        <option value="">Pilih semester</option>
                        <option value="Ganjil" @selected(old('semester') === 'Ganjil')>Ganjil</option>
                        <option value="Genap" @selected(old('semester') === 'Genap')>Genap</option>
                    </select>
                    @error('semester')
                        <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2 flex gap-3">
                    <button class="btn-primary" type="submit">Simpan</button>
                    <a href="{{ route('admin.tahun-ajaran.index') }}" class="btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </section>
@endsection

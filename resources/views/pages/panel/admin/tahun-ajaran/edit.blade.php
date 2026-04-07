@extends('layouts.panel')

@section('content')
    <section class="page-shell">
        <div>
            <h1 class="panel-title">Edit Tahun Ajaran</h1>
            <p class="panel-subtitle">Perbarui informasi periode akademik sekolah.</p>
        </div>

        <div class="section-card max-w-3xl">
            <form action="{{ route('admin.tahun-ajaran.update', $tahunAjaran) }}" method="POST" class="grid gap-5 md:grid-cols-2" data-enhanced-form>
                @csrf
                @method('PUT')
                <div>
                    <label class="form-label" for="tahun_ajaran">Tahun Ajaran</label>
                    <input class="form-input" id="tahun_ajaran" name="tahun_ajaran" type="text"
                        value="{{ old('tahun_ajaran', $tahunAjaran->tahun_ajaran) }}" required>
                    @error('tahun_ajaran')
                        <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label" for="semester">Semester</label>
                    <select class="form-select" id="semester" name="semester" required>
                        <option value="Ganjil" @selected(old('semester', $tahunAjaran->semester) === 'Ganjil')>Ganjil</option>
                        <option value="Genap" @selected(old('semester', $tahunAjaran->semester) === 'Genap')>Genap</option>
                    </select>
                    @error('semester')
                        <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2 flex gap-3">
                    <button class="btn-primary" type="submit">Simpan Perubahan</button>
                    <a href="{{ route('admin.tahun-ajaran.index') }}" class="btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </section>
@endsection

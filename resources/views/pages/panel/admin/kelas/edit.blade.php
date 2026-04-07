@extends('layouts.panel')

@section('content')
    <section class="page-shell">
        <div>
            <h1 class="panel-title">Edit Kelas</h1>
            <p class="panel-subtitle">Perbarui data kelas, tahun ajaran, dan wali kelas.</p>
        </div>

        <div class="section-card max-w-4xl">
            <form action="{{ route('admin.kelas.update', $kelas) }}" method="POST" class="grid gap-5 md:grid-cols-2" data-enhanced-form>
                @csrf
                @method('PUT')
                <div>
                    <label class="form-label" for="nama_kelas">Nama Kelas</label>
                    <input class="form-input" id="nama_kelas" name="nama_kelas" type="text" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required>
                    @error('nama_kelas') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label" for="tahun_ajaran_id">Tahun Ajaran</label>
                    <select class="form-select" id="tahun_ajaran_id" name="tahun_ajaran_id" required>
                        @foreach ($tahunAjarans as $tahun)
                            <option value="{{ $tahun->id }}" @selected(old('tahun_ajaran_id', $kelas->tahun_ajaran_id) == $tahun->id)>
                                {{ $tahun->tahun_ajaran }} - {{ $tahun->semester }}
                            </option>
                        @endforeach
                    </select>
                    @error('tahun_ajaran_id') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="form-label" for="wali_guru_id">Wali Kelas</label>
                    <select class="form-select" id="wali_guru_id" name="wali_guru_id">
                        <option value="">Pilih guru</option>
                        @foreach ($gurus as $guru)
                            <option value="{{ $guru->id }}" @selected(old('wali_guru_id', $kelas->wali_guru_id) == $guru->id)>{{ $guru->nama }}</option>
                        @endforeach
                    </select>
                    @error('wali_guru_id') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2 flex gap-3">
                    <button class="btn-primary" type="submit">Simpan Perubahan</button>
                    <a href="{{ route('admin.kelas.index') }}" class="btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </section>
@endsection

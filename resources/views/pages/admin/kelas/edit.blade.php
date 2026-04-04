@extends('layouts.admin')

@section('content')
    <h2 class="fw-bold mb-3">Edit Data</h2>

    <div class="card border-0">
        <div class="card-body">
            <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-2">

                    <div class="mb-3 col-md-6">
                        <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                        <select name="tahun_ajaran_id" id="tahun_ajaran" class="form-select">
                            <option value="" selected disabled>-- Pilih Tahun Ajaran --</option>
                            @foreach ($tahun_ajaran as $tahun)
                                <option value="{{ $tahun->id }}" @selected(old('tahun_ajaran_id', $tahun->id) == $tahun->id)>
                                    {{ $tahun->tahun_ajaran }}
                                </option>
                            @endforeach
                        </select>
                        @error('tahun_ajaran_id')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>



                    <div class="mb-3 col-md-6">
                        <label for="wali_kelas" class="form-label">Wali Kelas</label>
                        <select name="wali_kelas_id" id="wali_kelas" class="form-select">
                            <option value="" selected disabled>-- Pilih Wali Kelas --</option>
                            @foreach ($wali_kelas as $walas)
                                <option value="{{ $walas->id }}" @selected(old('wali_kelas_id', $walas->id) == $walas->id)>
                                    {{ $walas->nama }}</option>
                            @endforeach
                        </select>
                        @error('wali_kelas_id')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="nama_kelas" class="form-label">Nama Kelas</label>
                        <input type="text" name="nama_kelas" id="nama_kelas"
                            value="{{ old('nama_kelas', $kelas->nama_kelas) }}"
                            class="form-control @error('nama_kelas') is-invalid @enderror">
                        @error('nama_kelas')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                </div>
                <div class="d-flex gap-1 mt-3">
                    <button class="btn btn-primary btn-sm" type="submit">Simpan Perubahan</button>
                    <a href="{{ route('kelas.index') }}" class="btn btn-light border">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

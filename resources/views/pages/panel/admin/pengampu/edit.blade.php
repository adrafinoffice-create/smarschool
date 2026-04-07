@extends('layouts.panel')

@section('content')
    <section class="page-shell">
        <div>
            <h1 class="panel-title">Edit Penugasan Guru</h1>
            <p class="panel-subtitle">Ubah penugasan guru mengajar pada tahun ajaran aktif.</p>
        </div>

        <div class="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
            <div class="section-card w-full max-w-2xl mx-auto xl:mx-0 xl:max-w-full">
                <form action="{{ route('admin.pengampu.update', $pengampu) }}" method="POST" class="space-y-5" data-enhanced-form>
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="form-label" for="guru_id">Guru</label>
                        <select class="form-select" id="guru_id" name="guru_id" required>
                            <option value="">Pilih guru</option>
                            @foreach ($gurus as $guru)
                                <option value="{{ $guru->id }}" @selected($pengampu->guru_id == $guru->id)>{{ $guru->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label" for="mata_pelajaran_id">Mata Pelajaran</label>
                        <select class="form-select" id="mata_pelajaran_id" name="mata_pelajaran_id" required>
                            <option value="">Pilih mapel</option>
                            @foreach ($mataPelajarans as $mapel)
                                <option value="{{ $mapel->id }}" @selected($pengampu->mata_pelajaran_id == $mapel->id)>{{ $mapel->kode }} - {{ $mapel->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label" for="kelas_id">Kelas</label>
                        <select class="form-select" id="kelas_id" name="kelas_id" required>
                            <option value="">Pilih kelas</option>
                            @foreach ($kelas as $item)
                                <option value="{{ $item->id }}" @selected($pengampu->kelas_id == $item->id)>{{ $item->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-3">
                        <button class="btn-primary" type="submit">Update Penugasan</button>
                        <a href="{{ route('admin.pengampu.index') }}" class="btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

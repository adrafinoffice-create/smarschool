@extends('layouts.panel')

@section('content')
    <section class="page-shell">
        <div>
            <h1 class="panel-title">Edit Jadwal Pelajaran</h1>
            <p class="panel-subtitle">Ubah jadwal berdasarkan penugasan guru-mapel.</p>
        </div>

        <div class="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
            <div class="section-card w-full max-w-2xl mx-auto xl:mx-0 xl:max-w-full">
                <form action="{{ route('admin.jadwal.update', $jadwal) }}" method="POST" class="space-y-5" data-enhanced-form>
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="form-label" for="pengampu_id">Penugasan Guru</label>
                        <select class="form-select" id="pengampu_id" name="pengampu_id" required>
                            <option value="">Pilih penugasan</option>
                            @foreach ($pengampus as $item)
                                <option value="{{ $item->id }}" @selected($jadwal->pengampu_id == $item->id)>{{ $item->guru->nama }} - {{ $item->mataPelajaran->nama }} - {{ $item->kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label" for="hari">Hari</label>
                        <select class="form-select" id="hari" name="hari" required>
                            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                                <option value="{{ $hari }}" @selected($jadwal->hari == $hari)>{{ $hari }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="form-label" for="jam_mulai">Jam Mulai</label>
                            <input class="form-input" id="jam_mulai" name="jam_mulai" type="time" value="{{ \Illuminate\Support\Str::of($jadwal->jam_mulai)->substr(0, 5) }}" required>
                        </div>
                        <div>
                            <label class="form-label" for="jam_selesai">Jam Selesai</label>
                            <input class="form-input" id="jam_selesai" name="jam_selesai" type="time" value="{{ \Illuminate\Support\Str::of($jadwal->jam_selesai)->substr(0, 5) }}" required>
                        </div>
                    </div>
                    <div>
                        <label class="form-label" for="ruangan">Ruangan</label>
                        <input class="form-input" id="ruangan" name="ruangan" type="text" value="{{ $jadwal->ruangan }}" placeholder="Opsional">
                    </div>
                    <div class="flex gap-3">
                        <button class="btn-primary" type="submit">Update Jadwal</button>
                        <a href="{{ route('admin.jadwal.index') }}" class="btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

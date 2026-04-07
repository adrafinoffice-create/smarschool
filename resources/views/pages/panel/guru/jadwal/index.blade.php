@extends('layouts.panel')

@section('content')
    <section class="page-shell">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="panel-title">Jadwal Mengajar</h1>
                <p class="panel-subtitle">Pantau seluruh jadwal mengajar Anda dan buka absensi hanya saat jam pelajaran sedang berlangsung.</p>
            </div>
            <div class="badge-soft bg-primary/10 text-primary">Hari ini: {{ $hariIni }}</div>
        </div>

        <div class="section-card">
            <form action="{{ route('guru.jadwal.index') }}" method="GET" class="grid gap-4 md:grid-cols-[1fr_auto] md:items-end" data-enhanced-form>
                <div>
                    <label class="form-label" for="hari">Filter Hari</label>
                    <select class="form-select" id="hari" name="hari">
                        <option value="">Semua hari</option>
                        @foreach ($hariOptions as $hari)
                            <option value="{{ $hari }}" @selected($selectedHari === $hari)>{{ $hari }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3">
                    <button class="btn-primary" type="submit">Terapkan Filter</button>
                    @if ($selectedHari)
                        <a href="{{ route('guru.jadwal.index') }}" class="btn-secondary">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="grid gap-4">
            @forelse ($jadwals as $jadwal)
                <div class="section-card">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                        <div class="space-y-3">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="badge-soft bg-slate-100 text-slate-600">{{ $jadwal->hari }}</span>
                                <span @class([
                                    'badge-soft',
                                    'bg-emerald-100 text-emerald-700' => $jadwal->attendance_state['tone'] === 'emerald',
                                    'bg-amber-100 text-amber-700' => $jadwal->attendance_state['tone'] === 'amber',
                                    'bg-rose-100 text-rose-700' => $jadwal->attendance_state['tone'] === 'rose',
                                    'bg-slate-100 text-slate-600' => ! in_array($jadwal->attendance_state['tone'], ['emerald', 'amber', 'rose']),
                                ])>{{ $jadwal->attendance_state['label'] }}</span>
                            </div>
                            <div>
                                <h2 class="headline-text text-2xl font-black text-on-surface">{{ $jadwal->pengampu->mataPelajaran->nama }}</h2>
                                <p class="mt-2 text-sm text-on-surface-variant">
                                    {{ $jadwal->pengampu->kelas->nama_kelas }} | {{ \Illuminate\Support\Str::of($jadwal->jam_mulai)->substr(0, 5) }} - {{ \Illuminate\Support\Str::of($jadwal->jam_selesai)->substr(0, 5) }}
                                    @if ($jadwal->ruangan)
                                        | Ruang {{ $jadwal->ruangan }}
                                    @endif
                                </p>
                                <p class="mt-2 text-sm text-on-surface-variant">{{ $jadwal->attendance_state['description'] }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-start gap-3 lg:items-end">
                            <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-400">{{ $jadwal->pengampu->tahunAjaran->tahun_ajaran }} | Semester {{ $jadwal->pengampu->tahunAjaran->semester }}</p>
                            <a href="{{ route('guru.absensi.show', $jadwal) }}"
                                class="{{ $jadwal->attendance_state['can_take_attendance'] ? 'btn-primary' : 'btn-secondary' }}">
                                {{ $jadwal->attendance_state['can_take_attendance'] ? 'Buka Absensi Sekarang' : 'Lihat Sesi' }}
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="section-card text-center text-on-surface-variant">
                    Belum ada jadwal mengajar yang ditetapkan untuk Anda.
                </div>
            @endforelse
        </div>
    </section>
@endsection

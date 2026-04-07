@extends('layouts.panel')

@section('content')
    <section class="page-shell">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="panel-title">Dashboard Guru</h1>
                <p class="panel-subtitle">Selamat datang, {{ $guru->nama }}. Pantau jadwal hari ini, buka absensi mapel sesuai jam pelajaran, dan lihat rekap kehadiran siswa dari satu tempat.</p>
            </div>
            <div class="badge-soft bg-primary/10 text-primary">NIP: {{ $guru->nip }}</div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="section-card space-y-2">
                <p class="text-xs font-black uppercase tracking-[0.24em] text-slate-400">Jadwal Hari Ini</p>
                <p class="headline-text text-3xl font-black text-on-surface">{{ $ringkasan['jadwal_hari_ini'] }}</p>
                <p class="text-sm text-on-surface-variant">Sesi mengajar yang terjadwal untuk hari ini.</p>
            </div>
            <div class="section-card space-y-2">
                <p class="text-xs font-black uppercase tracking-[0.24em] text-slate-400">Sesi Bulan Ini</p>
                <p class="headline-text text-3xl font-black text-on-surface">{{ $ringkasan['sesi_bulan_ini'] }}</p>
                <p class="text-sm text-on-surface-variant">Jumlah sesi absensi mapel yang sudah tersimpan bulan ini.</p>
            </div>
            <div class="section-card space-y-2">
                <p class="text-xs font-black uppercase tracking-[0.24em] text-slate-400">Kelas Diampu</p>
                <p class="headline-text text-3xl font-black text-on-surface">{{ $ringkasan['kelas_diampu'] }}</p>
                <p class="text-sm text-on-surface-variant">Kelas aktif yang saat ini Anda ampu.</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
            <div class="section-card">
                <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="headline-text text-xl font-black">Jadwal Hari Ini</h2>
                        <p class="panel-subtitle">Pilih jadwal untuk membuka absensi mapel saat sesi sedang berlangsung.</p>
                    </div>
                    <a href="{{ route('guru.jadwal.index') }}" class="btn-secondary">Lihat Semua Jadwal</a>
                </div>
                <div class="space-y-4">
                    @forelse ($jadwalHariIni as $jadwal)
                        <div class="surface-card p-5">
                            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-[0.24em] text-slate-400">{{ $jadwal->hari }}</p>
                                    <h3 class="headline-text mt-2 text-xl font-black text-on-surface">{{ $jadwal->pengampu->mataPelajaran->nama }}</h3>
                                    <p class="mt-2 text-sm text-on-surface-variant">
                                        {{ $jadwal->pengampu->kelas->nama_kelas }} | {{ \Illuminate\Support\Str::of($jadwal->jam_mulai)->substr(0, 5) }} - {{ \Illuminate\Support\Str::of($jadwal->jam_selesai)->substr(0, 5) }}
                                    </p>
                                    <div class="mt-3">
                                        <span @class([
                                            'badge-soft',
                                            'bg-emerald-100 text-emerald-700' => $jadwal->attendance_state['tone'] === 'emerald',
                                            'bg-amber-100 text-amber-700' => $jadwal->attendance_state['tone'] === 'amber',
                                            'bg-rose-100 text-rose-700' => $jadwal->attendance_state['tone'] === 'rose',
                                            'bg-slate-100 text-slate-600' => ! in_array($jadwal->attendance_state['tone'], ['emerald', 'amber', 'rose']),
                                        ])>{{ $jadwal->attendance_state['label'] }}</span>
                                    </div>
                                </div>
                                <div class="flex flex-col items-start gap-2 md:items-end">
                                    <a href="{{ route('guru.absensi.show', $jadwal) }}"
                                        class="{{ $jadwal->attendance_state['can_take_attendance'] ? 'btn-primary' : 'btn-secondary' }}">
                                        {{ $jadwal->attendance_state['can_take_attendance'] ? 'Buka Sesi Absensi' : 'Lihat Detail Jadwal' }}
                                    </a>
                                    <p class="text-xs text-on-surface-variant">{{ $jadwal->attendance_state['description'] }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="surface-card p-6 text-center text-on-surface-variant">Tidak ada jadwal mengajar untuk hari ini.</div>
                    @endforelse
                </div>
            </div>

            <div class="section-card">
                <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="headline-text text-xl font-black">Riwayat Sesi</h2>
                        <p class="panel-subtitle">10 sesi absensi terakhir yang pernah Anda simpan.</p>
                    </div>
                    <a href="{{ route('guru.rekap.index') }}" class="btn-secondary">Buka Rekap</a>
                </div>
                <div class="space-y-3">
                    @forelse ($riwayatSesi as $sesi)
                        <a href="{{ route('guru.absensi.detail', $sesi) }}" class="block rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4 transition hover:border-primary/20 hover:bg-white">
                            <p class="font-bold text-on-surface">{{ $sesi->jadwalPelajaran->pengampu->mataPelajaran->nama }}</p>
                            <p class="mt-1 text-sm text-on-surface-variant">{{ $sesi->jadwalPelajaran->pengampu->kelas->nama_kelas }} | {{ $sesi->tanggal->translatedFormat('d F Y') }}</p>
                        </a>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-200 px-4 py-6 text-center text-on-surface-variant">
                            Belum ada sesi absensi yang tersimpan.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection

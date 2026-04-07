@extends('layouts.panel')

@section('content')
    <section class="page-shell">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="panel-title">Dashboard Admin</h1>
                <p class="panel-subtitle">Pantau kesiapan tahun ajaran, guru, jadwal, dan sesi absensi sekolah.</p>
            </div>
            <div class="badge-soft bg-primary/10 text-primary">
                Tahun Aktif:
                {{ $tahunAjaranAktif ? $tahunAjaranAktif->tahun_ajaran . ' - ' . $tahunAjaranAktif->semester : 'Belum diatur' }}
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <div class="metric-card group relative">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-on-surface-variant">Jumlah Guru</p>
                        <p class="mt-4 text-4xl font-black text-on-surface">{{ number_format($stats['guru']) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary/10 text-primary transition-transform group-hover:scale-110">
                        <span class="material-symbols-outlined text-2xl">supervisor_account</span>
                    </div>
                </div>
            </div>
            <div class="metric-card group relative">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-on-surface-variant">Jumlah Kelas</p>
                        <p class="mt-4 text-4xl font-black text-on-surface">{{ number_format($stats['kelas']) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 transition-transform group-hover:scale-110">
                        <span class="material-symbols-outlined text-2xl">meeting_room</span>
                    </div>
                </div>
            </div>
            <div class="metric-card group relative">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-on-surface-variant">Jumlah Siswa</p>
                        <p class="mt-4 text-4xl font-black text-on-surface">{{ number_format($stats['siswa']) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-amber-600 transition-transform group-hover:scale-110">
                        <span class="material-symbols-outlined text-2xl">groups</span>
                    </div>
                </div>
            </div>
            <div class="metric-card group relative">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-on-surface-variant">Mata Pelajaran</p>
                        <p class="mt-4 text-4xl font-black text-on-surface">{{ number_format($stats['mapel']) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-100 text-rose-600 transition-transform group-hover:scale-110">
                        <span class="material-symbols-outlined text-2xl">menu_book</span>
                    </div>
                </div>
            </div>
            <div class="metric-card group relative">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-on-surface-variant">Jadwal Aktif</p>
                        <p class="mt-4 text-4xl font-black text-on-surface">{{ number_format($stats['jadwal']) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 transition-transform group-hover:scale-110">
                        <span class="material-symbols-outlined text-2xl">calendar_month</span>
                    </div>
                </div>
            </div>
            <div class="metric-card group relative">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-on-surface-variant">Sesi Hari Ini</p>
                        <p class="mt-4 text-4xl font-black text-on-surface">{{ number_format($stats['sesi_hari_ini']) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-100 text-cyan-600 transition-transform group-hover:scale-110">
                        <span class="material-symbols-outlined text-2xl">fact_check</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-card">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h2 class="headline-text text-xl font-black">Jadwal Hari Ini</h2>
                    <p class="panel-subtitle">Daftar pelajaran yang berlangsung pada hari {{ now()->translatedFormat('l') }}.</p>
                </div>
                <a href="{{ route('admin.jadwal.index') }}" class="btn-secondary">Kelola Jadwal</a>
            </div>

            <div class="table-shell">
                <table>
                    <thead>
                        <tr>
                            <th>Jam</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jadwalHariIni as $jadwal)
                            <tr>
                                <td>{{ \Illuminate\Support\Str::of($jadwal->jam_mulai)->substr(0, 5) }} - {{ \Illuminate\Support\Str::of($jadwal->jam_selesai)->substr(0, 5) }}</td>
                                <td>{{ $jadwal->pengampu->kelas->nama_kelas }}</td>
                                <td>{{ $jadwal->pengampu->mataPelajaran->nama }}</td>
                                <td>{{ $jadwal->pengampu->guru->nama }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-on-surface-variant">Belum ada jadwal untuk hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@extends('layouts.panel')

@php
    $statusTone = match ($attendanceState['tone']) {
        'emerald' => 'bg-emerald-100 text-emerald-700',
        'amber' => 'bg-amber-100 text-amber-700',
        'rose' => 'bg-rose-100 text-rose-700',
        default => 'bg-slate-100 text-slate-600',
    };

    $summary = [
        'Hadir' => $details->where('status', 'Hadir')->count(),
        'Izin' => $details->where('status', 'Izin')->count(),
        'Sakit' => $details->where('status', 'Sakit')->count(),
        'Alpa' => $details->where('status', 'Alpa')->count(),
    ];
@endphp

@section('content')
    <section class="page-shell">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="panel-title">Absensi {{ $jadwal->pengampu->mataPelajaran->nama }}</h1>
                <p class="panel-subtitle">
                    {{ $jadwal->pengampu->kelas->nama_kelas }} | {{ $jadwal->hari }} |
                    {{ \Illuminate\Support\Str::of($jadwal->jam_mulai)->substr(0, 5) }} -
                    {{ \Illuminate\Support\Str::of($jadwal->jam_selesai)->substr(0, 5) }}
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <span class="badge-soft {{ $statusTone }}">{{ $attendanceState['label'] }}</span>
                <a href="{{ route('guru.jadwal.index') }}" class="btn-secondary">Kembali ke Jadwal</a>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-4">
            @foreach ($summary as $label => $count)
                <div class="section-card space-y-2">
                    <p class="text-xs font-black uppercase tracking-[0.24em] text-slate-400">{{ $label }}</p>
                    <p class="headline-text text-3xl font-black text-on-surface" data-summary-count="{{ $label }}">
                        {{ $count }}</p>
                    <p class="text-sm text-on-surface-variant">Jumlah siswa dengan status {{ strtolower($label) }} pada
                        sesi ini.</p>
                </div>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-[0.92fr_1.08fr]">
            <div class="section-card">
                <div class="mb-4">
                    <h2 class="headline-text text-xl font-black">Scan QR Kehadiran</h2>
                    <p class="panel-subtitle">{{ $attendanceState['description'] }}</p>
                </div>

                @if ($enrollments->isEmpty())
                    <div
                        class="rounded-2xl border border-dashed border-slate-200 px-4 py-6 text-center text-on-surface-variant">
                        Belum ada siswa aktif yang di-enroll ke kelas ini.
                    </div>
                @else
                    <div data-qr-attendance data-scan-endpoint="{{ route('guru.absensi.scan', $jadwal) }}"
                        data-csrf-token="{{ csrf_token() }}"
                        data-can-scan="{{ $attendanceState['can_take_attendance'] ? 'true' : 'false' }}">

                        <div class="qr-scanner-container">
                            <div class="relative aspect-[4/3] min-h-[300px] bg-black">
                                <div id="qr-reader-{{ $jadwal->id }}" class="qr-scanner-video" data-qr-video>
                                </div>
                                <div class="qr-scanner-placeholder" data-qr-placeholder>
                                    <div class="text-center">
                                        <span class="material-symbols-outlined text-4xl mb-2">qr_code_scanner</span>
                                        <p>Aktifkan kamera untuk mulai memindai QR siswa.</p>
                                        <p class="text-xs mt-2 opacity-75">Pastikan izin kamera diberikan</p>
                                    </div>
                                </div>

                                {{-- Optional: Scan overlay frame --}}
                                <div class="qr-scan-overlay hidden" data-qr-overlay>
                                    <div class="qr-scan-frame">
                                        <div class="corner-bottom-left"></div>
                                        <div class="corner-top-right"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-3">
                            <button class="btn-primary" type="button" data-qr-start @disabled(!$attendanceState['can_take_attendance'])>
                                <span class="material-symbols-outlined text-base mr-1">camera</span>
                                Aktifkan Kamera
                            </button>
                            <button class="btn-secondary hidden" type="button" data-qr-stop>
                                <span class="material-symbols-outlined text-base mr-1">stop</span>
                                Stop Kamera
                            </button>
                        </div>

                        <form class="mt-5 space-y-3" data-qr-manual-form>
                            <div>
                                <label class="form-label" for="kode_scan">Kode QR / NIS Siswa</label>
                                <input class="form-input" id="kode_scan" name="kode" type="text"
                                    placeholder="Scan atau ketik NIS siswa" data-qr-input @disabled(!$attendanceState['can_take_attendance'])>
                            </div>
                            <button class="btn-secondary" type="submit" data-loading-text="Memproses scan..."
                                @disabled(!$attendanceState['can_take_attendance'])>Proses Scan</button>
                        </form>

                        <div class="mt-5 rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <p class="text-xs font-black uppercase tracking-[0.24em] text-slate-400">Info Scanner</p>
                            <p class="mt-2 text-sm text-on-surface-variant" data-qr-status>
                                {{ $attendanceState['can_take_attendance'] ? 'Scanner siap digunakan. QR siswa dapat berisi NIS, ID siswa, atau payload JSON sederhana.' : 'Scanner belum aktif karena jadwal tidak sedang berlangsung.' }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="section-card">
                <div class="mb-4 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="headline-text text-xl font-black">Sesi {{ now()->translatedFormat('d F Y') }}</h2>
                        <p class="panel-subtitle">
                            {{ $sesi ? 'Sesi sudah pernah disimpan dan masih bisa diperbarui selama jam pelajaran berlangsung.' : 'Simpan absensi atau scan QR untuk membuat sesi hari ini.' }}
                        </p>
                    </div>
                    @if ($sesi)
                        <span class="badge-soft bg-emerald-100 text-emerald-700">Terakhir disimpan
                            {{ optional($sesi->closed_at)->format('H:i') }}</span>
                    @endif
                </div>

                <form action="{{ route('guru.absensi.store', $jadwal) }}" method="POST" data-enhanced-form>
                    @csrf
                    <div class="table-shell">
                        <table>
                            <thead>
                                <tr>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Dicek</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($enrollments as $enrollment)
                                    @php $detail = $details->get($enrollment->siswa_id); @endphp
                                    <tr data-siswa-row data-siswa-id="{{ $enrollment->siswa_id }}">
                                        <td>{{ $enrollment->siswa->nis }}</td>
                                        <td>
                                            <p class="font-semibold text-on-surface">{{ $enrollment->siswa->nama }}</p>
                                        </td>
                                        <td>
                                            <select class="form-select mt-0" name="status[{{ $enrollment->siswa_id }}]"
                                                data-status-input>
                                                @foreach (['Hadir', 'Izin', 'Sakit', 'Alpa'] as $status)
                                                    <option value="{{ $status }}" @selected(old("status.{$enrollment->siswa_id}", $detail?->status ?? 'Alpa') === $status)>
                                                        {{ $status }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input class="form-input mt-0" name="keterangan[{{ $enrollment->siswa_id }}]"
                                                type="text"
                                                value="{{ old("keterangan.{$enrollment->siswa_id}", $detail?->keterangan) }}"
                                                placeholder="Opsional">
                                        </td>
                                        <td class="text-sm text-on-surface-variant" data-checked-at>
                                            {{ optional($detail?->checked_at)->format('H:i:s') ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-on-surface-variant">Belum ada siswa aktif
                                            yang di-enroll ke kelas ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($enrollments->isNotEmpty())
                        <div class="mt-5 flex gap-3">
                            <button class="btn-primary" type="submit" @disabled(!$attendanceState['can_take_attendance'])>Simpan Absensi</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </section>
@endsection

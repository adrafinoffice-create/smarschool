@extends('layouts.panel')

@php
    $summary = [
        'Hadir' => $sesi->detailAbsensi->where('status', 'Hadir')->count(),
        'Izin' => $sesi->detailAbsensi->where('status', 'Izin')->count(),
        'Sakit' => $sesi->detailAbsensi->where('status', 'Sakit')->count(),
        'Alpa' => $sesi->detailAbsensi->where('status', 'Alpa')->count(),
    ];
@endphp

@section('content')
    <section class="page-shell">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="panel-title">Detail Sesi Absensi</h1>
                <p class="panel-subtitle">
                    {{ $sesi->jadwalPelajaran->pengampu->mataPelajaran->nama }} |
                    {{ $sesi->jadwalPelajaran->pengampu->kelas->nama_kelas }} |
                    {{ $sesi->tanggal->translatedFormat('d F Y') }}
                </p>
            </div>
            <a href="{{ route('guru.rekap.index') }}" class="btn-secondary">Kembali ke Rekap</a>
        </div>

        <div class="grid gap-4 md:grid-cols-4">
            @foreach ($summary as $label => $count)
                <div class="section-card space-y-2">
                    <p class="text-xs font-black uppercase tracking-[0.24em] text-slate-400">{{ $label }}</p>
                    <p class="headline-text text-3xl font-black text-on-surface">{{ $count }}</p>
                    <p class="text-sm text-on-surface-variant">Jumlah siswa dengan status {{ strtolower($label) }} pada sesi ini.</p>
                </div>
            @endforeach
        </div>

        <div class="section-card">
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
                        @foreach ($sesi->detailAbsensi->sortBy(fn ($detail) => $detail->siswa->nama) as $detail)
                            <tr>
                                <td>{{ $detail->siswa->nis }}</td>
                                <td>{{ $detail->siswa->nama }}</td>
                                <td>{{ $detail->status }}</td>
                                <td>{{ $detail->keterangan ?: '-' }}</td>
                                <td>{{ optional($detail->checked_at)->format('H:i:s') ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

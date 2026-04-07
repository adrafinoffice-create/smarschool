@extends('layouts.panel')

@php
    $statusTones = [
        'Hadir' => 'bg-emerald-100 text-emerald-700',
        'Sakit' => 'bg-amber-100 text-amber-700',
        'Izin' => 'bg-blue-100 text-blue-700',
        'Alpa' => 'bg-rose-100 text-rose-700',
    ];
@endphp

@section('content')
<div class="page-shell">
    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="panel-title">{{ $title }}</h1>
            <p class="panel-subtitle">
                {{ $sesi->jadwalPelajaran->pengampu->kelas->nama_kelas }} | 
                Tanggal {{ $sesi->tanggal->translatedFormat('d F Y') }} | 
                {{ $sesi->started_at?->format('H:i') }} - {{ $sesi->closed_at?->format('H:i') ?? 'Sedang Berjalan' }}
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.laporan-absensi.showClass', $sesi->jadwalPelajaran->pengampu->kelas_id) }}" class="btn-secondary">Kembali ke Daftar Sesi</a>
        </div>
    </div>

    <!-- Summary Widgets -->
    <div class="grid gap-4 md:grid-cols-4 mb-6">
        @foreach ($summary as $label => $count)
            <div class="section-card space-y-2">
                <p class="text-xs font-black uppercase tracking-[0.24em] text-slate-400">{{ $label }}</p>
                <p class="headline-text text-3xl font-black text-on-surface">{{ $count }}</p>
            </div>
        @endforeach
    </div>

    <div class="section-card">
        <div class="mb-4">
            <h2 class="headline-text text-xl font-black">Data Presensi Siswa</h2>
            <p class="panel-subtitle">Daftar presensi siswa pada sesi jadwal ini oleh Guru <strong>{{ $sesi->guru->nama }}</strong>.</p>
        </div>

        <div class="table-shell">
            <table>
                <thead>
                    <tr>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Status / Cek</th>
                        <th>Keterangan Tambahan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($details as $detail)
                        <tr>
                            <td class="font-medium text-slate-600">{{ $detail->siswa->nis }}</td>
                            <td>
                                <div class="font-bold text-slate-800">{{ $detail->siswa->nama }}</div>
                                <div class="text-xs text-slate-500">{{ $detail->siswa->jenis_kelamin }}</div>
                            </td>
                            <td>
                                <span class="badge-soft {{ $statusTones[$detail->status] ?? 'bg-slate-100 text-slate-700' }} inline-block mb-1">
                                    {{ $detail->status }}
                                </span>
                                <div class="text-[10px] text-slate-400">
                                    Dicek: {{ $detail->checked_at?->format('H:i:s') ?? '-' }}
                                </div>
                            </td>
                            <td>
                                <span class="text-sm text-slate-600 italic">
                                    {{ $detail->keterangan ?? '-' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-slate-500 py-8">Belum ada rincian absensi tersimpan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@extends('layouts.panel')

@section('content')
<div class="page-shell">
    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="panel-title">{{ $title }}</h1>
            <p class="panel-subtitle">Pilih kelas untuk melihat rekapitulasi dan history sesi absensi.</p>
        </div>
    </div>

    <div class="section-card">
        <div class="table-shell">
            <table>
                <thead>
                    <tr>
                        <th>Kelas</th>
                        <th>Jumlah Siswa Aktif</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kelas as $kls)
                        <tr>
                            <td>
                                <div class="font-bold text-slate-800">{{ $kls->nama_kelas }}</div>
                            </td>
                            <td>
                                <span class="badge-soft bg-blue-50 text-blue-700">
                                    {{ $kls->siswa_count }} Siswa
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.laporan-absensi.showClass', $kls->id) }}" class="btn-primary py-1.5 px-3 text-xs">
                                    <span class="material-symbols-outlined text-sm mr-1">monitoring</span>
                                    Lihat Rekap
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-slate-500 py-8">Belum ada data kelas yang tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@extends('layouts.panel')

@section('content')
<div class="page-shell">
    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="panel-title">{{ $title }}</h1>
            <p class="panel-subtitle">Riwayat seluruh sesi jadwal mata pelajaran kelas ini.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.laporan-absensi.exportExcel', ['kelas' => $kelas->id, 'mata_pelajaran_id' => $mataPelajaranId, 'tanggal_mulai' => $tanggalMulai, 'tanggal_selesai' => $tanggalSelesai]) }}" class="btn-primary bg-green-600 hover:bg-green-700">
                <span class="material-symbols-outlined mr-1 text-sm">table</span> Export Excel
            </a>
            <a href="{{ route('admin.laporan-absensi.exportPdf', ['kelas' => $kelas->id, 'mata_pelajaran_id' => $mataPelajaranId, 'tanggal_mulai' => $tanggalMulai, 'tanggal_selesai' => $tanggalSelesai]) }}" class="btn-danger">
                <span class="material-symbols-outlined mr-1 text-sm">picture_as_pdf</span> Export PDF
            </a>
            <a href="{{ route('admin.laporan-absensi.index') }}" class="btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="section-card">
        <form action="{{ route('admin.laporan-absensi.showClass', $kelas->id) }}" method="GET" class="mb-6 grid gap-4 md:grid-cols-4 items-end bg-slate-50 p-4 rounded-2xl">
            <div>
                <label for="mata_pelajaran_id" class="form-label">Mata Pelajaran</label>
                <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-input">
                    <option value="">Semua Mapel</option>
                    @foreach($mapelOptions as $mapel)
                        <option value="{{ $mapel->id }}" {{ $mataPelajaranId == $mapel->id ? 'selected' : '' }}>
                            {{ $mapel->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ $tanggalMulai }}" class="form-input">
            </div>
            <div>
                <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ $tanggalSelesai }}" class="form-input">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary w-full">Filter</button>
                <a href="{{ route('admin.laporan-absensi.showClass', $kelas->id) }}" class="btn-secondary w-full">Reset</a>
            </div>
        </form>

        <div class="table-shell">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Mapel / Topik</th>
                        <th>Waktu / Guru</th>
                        <th class="text-center">Hadir</th>
                        <th class="text-center">Sakit</th>
                        <th class="text-center">Izin</th>
                        <th class="text-center">Alpa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sesi as $s)
                        <tr>
                            <td>
                                <div class="font-bold text-slate-800">
                                    {{ $s->tanggal->translatedFormat('d M Y') }}
                                </div>
                                <div class="text-xs text-slate-500">{{ $s->tanggal->translatedFormat('l') }}</div>
                            </td>
                            <td>
                                <div class="font-bold text-primary">{{ $s->jadwalPelajaran->pengampu->mataPelajaran->nama }}</div>
                                <div class="text-xs text-slate-500 line-clamp-1" title="{{ $s->topik }}">{{ $s->topik ?? 'Tanpa Topik' }}</div>
                            </td>
                            <td>
                                <div class="text-sm shadow-sm border border-slate-100 rounded-md py-0.5 px-2 bg-slate-50 inline-block mb-1">
                                    {{ $s->started_at?->format('H:i') ?? '-' }} s/d {{ $s->closed_at?->format('H:i') ?? 'Buka' }}
                                </div>
                                <div class="text-xs font-medium text-slate-600">{{ $s->guru->nama }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge-soft bg-emerald-100 text-emerald-700">{{ $s->hadir_count }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge-soft bg-amber-100 text-amber-700">{{ $s->sakit_count }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge-soft bg-blue-100 text-blue-700">{{ $s->izin_count }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge-soft bg-rose-100 text-rose-700">{{ $s->alpa_count }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.laporan-absensi.showSession', $s->id) }}" class="btn-secondary py-1.5 px-3 text-xs w-full justify-center">
                                    Detail Cek
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-slate-500 py-8">Tidak ada sesi absensi yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $sesi->links() }}
        </div>
    </div>
</div>
@endsection

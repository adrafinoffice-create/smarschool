@extends('layouts.panel')

@section('content')
    <section class="page-shell">
        <div>
            <h1 class="panel-title">Rekap Absensi</h1>
            <p class="panel-subtitle">Lihat riwayat absensi per kelas, per mata pelajaran, dan detail setiap sesi yang pernah Anda isi.</p>
        </div>

        <div class="section-card">
            <form action="{{ route('guru.rekap.index') }}" method="GET" class="grid gap-4 xl:grid-cols-5" data-enhanced-form>
                <div>
                    <label class="form-label" for="kelas_id">Kelas</label>
                    <select class="form-select" id="kelas_id" name="kelas_id">
                        <option value="">Semua kelas</option>
                        @foreach ($kelasOptions as $kelas)
                            <option value="{{ $kelas->id }}" @selected($selectedKelas === $kelas->id)>{{ $kelas->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label" for="mata_pelajaran_id">Mata Pelajaran</label>
                    <select class="form-select" id="mata_pelajaran_id" name="mata_pelajaran_id">
                        <option value="">Semua mata pelajaran</option>
                        @foreach ($mataPelajaranOptions as $mataPelajaran)
                            <option value="{{ $mataPelajaran->id }}" @selected($selectedMataPelajaran === $mataPelajaran->id)>{{ $mataPelajaran->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label" for="tanggal_mulai">Tanggal Mulai</label>
                    <input class="form-input" id="tanggal_mulai" name="tanggal_mulai" type="date" value="{{ $tanggalMulai }}">
                </div>
                <div>
                    <label class="form-label" for="tanggal_selesai">Tanggal Selesai</label>
                    <input class="form-input" id="tanggal_selesai" name="tanggal_selesai" type="date" value="{{ $tanggalSelesai }}">
                </div>
                <div class="flex items-end gap-3">
                    <button class="btn-primary w-full" type="submit">Tampilkan Rekap</button>
                    <a href="{{ route('guru.rekap.index') }}" class="btn-secondary">Reset</a>
                </div>
            </form>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <div class="section-card">
                <div class="mb-4">
                    <h2 class="headline-text text-xl font-black">Ringkasan Per Kelas</h2>
                    <p class="panel-subtitle">Akumulasi status kehadiran dari seluruh sesi yang sesuai filter.</p>
                </div>
                <div class="table-shell">
                    <table>
                        <thead>
                            <tr>
                                <th>Kelas</th>
                                <th>Hadir</th>
                                <th>Izin</th>
                                <th>Sakit</th>
                                <th>Alpa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rekapPerKelas as $item)
                                <tr>
                                    <td>{{ $item->nama_kelas }}</td>
                                    <td>{{ $item->hadir_count }}</td>
                                    <td>{{ $item->izin_count }}</td>
                                    <td>{{ $item->sakit_count }}</td>
                                    <td>{{ $item->alpa_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-on-surface-variant">Belum ada data rekap per kelas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="section-card">
                <div class="mb-4">
                    <h2 class="headline-text text-xl font-black">Ringkasan Per Mapel</h2>
                    <p class="panel-subtitle">Akumulasi status kehadiran berdasarkan mata pelajaran yang Anda ampu.</p>
                </div>
                <div class="table-shell">
                    <table>
                        <thead>
                            <tr>
                                <th>Mapel</th>
                                <th>Hadir</th>
                                <th>Izin</th>
                                <th>Sakit</th>
                                <th>Alpa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rekapPerMapel as $item)
                                <tr>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->hadir_count }}</td>
                                    <td>{{ $item->izin_count }}</td>
                                    <td>{{ $item->sakit_count }}</td>
                                    <td>{{ $item->alpa_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-on-surface-variant">Belum ada data rekap per mata pelajaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="section-card">
            <div class="mb-4">
                <h2 class="headline-text text-xl font-black">Riwayat Sesi Absensi</h2>
                <p class="panel-subtitle">Klik detail sesi untuk melihat absensi siswa pada setiap pertemuan.</p>
            </div>
            <div class="table-shell">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Hadir</th>
                            <th>Izin</th>
                            <th>Sakit</th>
                            <th>Alpa</th>
                            <th class="w-[120px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sessions as $session)
                            <tr>
                                <td>{{ $session->tanggal->translatedFormat('d M Y') }}</td>
                                <td>{{ $session->jadwalPelajaran->pengampu->kelas->nama_kelas }}</td>
                                <td>{{ $session->jadwalPelajaran->pengampu->mataPelajaran->nama }}</td>
                                <td>{{ $session->hadir_count }}</td>
                                <td>{{ $session->izin_count }}</td>
                                <td>{{ $session->sakit_count }}</td>
                                <td>{{ $session->alpa_count }}</td>
                                <td>
                                    <a href="{{ route('guru.absensi.detail', $session) }}" class="btn-secondary">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-on-surface-variant">Belum ada sesi absensi yang sesuai filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($sessions->hasPages())
                <div class="mt-5">
                    {{ $sessions->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection

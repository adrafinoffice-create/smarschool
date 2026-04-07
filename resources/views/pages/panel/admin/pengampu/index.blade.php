@extends('layouts.panel')

@section('content')
    <section class="page-shell">
        <div>
            <h1 class="panel-title">Penugasan Guru Mapel</h1>
            <p class="panel-subtitle">Tentukan guru mengajar mapel apa di kelas mana pada tahun ajaran aktif.</p>
        </div>

        <div class="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
            <div class="section-card">
                <div class="mb-4">
                    <h2 class="headline-text text-xl font-black">Tambah Penugasan</h2>
                    <p class="panel-subtitle">{{ $tahunAjaranAktif ? $tahunAjaranAktif->tahun_ajaran . ' - ' . $tahunAjaranAktif->semester : 'Tahun ajaran aktif belum dipilih' }}</p>
                </div>
                <form action="{{ route('admin.pengampu.store') }}" method="POST" class="space-y-5" data-enhanced-form>
                    @csrf
                    <div>
                        <label class="form-label" for="guru_id">Guru</label>
                        <select class="form-select" id="guru_id" name="guru_id" required>
                            <option value="">Pilih guru</option>
                            @foreach ($gurus as $guru)
                                <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label" for="mata_pelajaran_id">Mata Pelajaran</label>
                        <select class="form-select" id="mata_pelajaran_id" name="mata_pelajaran_id" required>
                            <option value="">Pilih mapel</option>
                            @foreach ($mataPelajarans as $mapel)
                                <option value="{{ $mapel->id }}">{{ $mapel->kode }} - {{ $mapel->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label" for="kelas_id">Kelas</label>
                        <select class="form-select" id="kelas_id" name="kelas_id" required>
                            <option value="">Pilih kelas</option>
                            @foreach ($kelas as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn-primary" type="submit">Simpan Penugasan</button>
                </form>
            </div>

            <div class="section-card">
                <div class="table-shell">
                    <table>
                        <thead>
                            <tr>
                                <th>Guru</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Tahun Ajaran</th>
                                <th class="w-[120px]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengampus as $item)
                                <tr>
                                    <td>{{ $item->guru->nama }}</td>
                                    <td>{{ $item->mataPelajaran->nama }}</td>
                                    <td>{{ $item->kelas->nama_kelas }}</td>
                                    <td>{{ $item->tahunAjaran->tahun_ajaran }} {{ $item->tahunAjaran->semester }}</td>
                                     <td>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.pengampu.edit', $item) }}" class="text-gray-400 hover:text-blue-600 transition-colors" title="Edit">
                                                <span class="material-symbols-outlined text-xl">edit</span>
                                            </a>
                                            <form action="{{ route('admin.pengampu.destroy', $item) }}" method="POST" data-confirm-delete class="m-0 p-0 flex">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors bg-transparent border-0 p-0 cursor-pointer" title="Hapus">
                                                    <span class="material-symbols-outlined text-xl">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@extends('layouts.panel')

@section('content')
    <section class="page-shell">
        <div>
            <h1 class="panel-title">Jadwal Pelajaran</h1>
            <p class="panel-subtitle">Susun jadwal berdasarkan penugasan guru-mapel agar absensi berjalan per sesi.</p>
        </div>

        <div class="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
            <div class="section-card">
                <h2 class="headline-text text-xl font-black">Tambah Jadwal</h2>
                <form action="{{ route('admin.jadwal.store') }}" method="POST" class="mt-5 space-y-5" data-enhanced-form>
                    @csrf
                    <div>
                        <label class="form-label" for="pengampu_id">Penugasan Guru</label>
                        <select class="form-select" id="pengampu_id" name="pengampu_id" required>
                            <option value="">Pilih penugasan</option>
                            @foreach ($pengampus as $item)
                                <option value="{{ $item->id }}">{{ $item->guru->nama }} - {{ $item->mataPelajaran->nama }} - {{ $item->kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label" for="hari">Hari</label>
                        <select class="form-select" id="hari" name="hari" required>
                            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                                <option value="{{ $hari }}">{{ $hari }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="form-label" for="jam_mulai">Jam Mulai</label>
                            <input class="form-input" id="jam_mulai" name="jam_mulai" type="time" required>
                        </div>
                        <div>
                            <label class="form-label" for="jam_selesai">Jam Selesai</label>
                            <input class="form-input" id="jam_selesai" name="jam_selesai" type="time" required>
                        </div>
                    </div>
                    <div>
                        <label class="form-label" for="ruangan">Ruangan</label>
                        <input class="form-input" id="ruangan" name="ruangan" type="text" placeholder="Opsional">
                    </div>
                    <button class="btn-primary" type="submit">Simpan Jadwal</button>
                </form>
            </div>

            <div class="section-card">
                <div class="table-shell">
                    <table>
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Jam</th>
                                <th>Kelas</th>
                                <th>Mapel</th>
                                <th>Guru</th>
                                <th class="w-[120px]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jadwals as $jadwal)
                                <tr>
                                    <td>{{ $jadwal->hari }}</td>
                                    <td>{{ \Illuminate\Support\Str::of($jadwal->jam_mulai)->substr(0, 5) }} - {{ \Illuminate\Support\Str::of($jadwal->jam_selesai)->substr(0, 5) }}</td>
                                    <td>{{ $jadwal->pengampu->kelas->nama_kelas }}</td>
                                    <td>{{ $jadwal->pengampu->mataPelajaran->nama }}</td>
                                    <td>{{ $jadwal->pengampu->guru->nama }}</td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.jadwal.edit', $jadwal) }}" class="text-gray-400 hover:text-blue-600 transition-colors" title="Edit">
                                                <span class="material-symbols-outlined text-xl">edit</span>
                                            </a>
                                            <form action="{{ route('admin.jadwal.destroy', $jadwal) }}" method="POST" data-confirm-delete class="m-0 p-0 flex">
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

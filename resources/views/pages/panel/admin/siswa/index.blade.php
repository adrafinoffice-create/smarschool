@extends('layouts.panel')

@section('content')
    <section class="page-shell">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="panel-title">Siswa</h1>
                <p class="panel-subtitle">Data siswa aktif yang nanti di-enroll ke kelas dan diabsen per sesi.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <form action="{{ route('admin.siswa.index') }}" method="GET">
                    <select class="form-select min-w-[220px]" name="kelas_id" onchange="this.form.submit()">
                        <option value="">Semua kelas</option>
                        @foreach ($kelas as $item)
                            <option value="{{ $item->id }}" @selected($selectedKelasId == $item->id)>{{ $item->nama_kelas }}</option>
                        @endforeach
                    </select>
                </form>
                <a href="{{ route('admin.siswa.create') }}" class="btn-primary">Tambah Siswa</a>
            </div>
        </div>

        <div class="section-card">
            <div class="table-shell">
                <table>
                    <thead>
                        <tr>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Jenis Kelamin</th>
                            <th>Orang Tua</th>
                            <th class="w-[220px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($siswa as $item)
                            <tr>
                                <td>{{ $item->nis }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->kelas?->nama_kelas ?? '-' }}</td>
                                <td>{{ $item->jenis_kelamin }}</td>
                                <td>{{ $item->nama_orang_tua }}</td>
                                <td>
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.siswa.show', $item) }}" class="btn-primary text-xs">Detail</a>
                                        <a href="{{ route('admin.siswa.edit', $item) }}" class="btn-secondary">Edit</a>
                                        <form action="{{ route('admin.siswa.destroy', $item) }}" method="POST" data-confirm-delete>
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn-danger" type="submit">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $siswa->links() }}
            </div>
        </div>
    </section>
@endsection

@extends('layouts.panel')

@section('content')
    <section class="page-shell">
        <div class="flex items-end justify-between">
            <div>
                <h1 class="panel-title">Kelas</h1>
                <p class="panel-subtitle">Atur kelas berdasarkan tahun ajaran dan wali kelas dari data guru.</p>
            </div>
            <a href="{{ route('admin.kelas.create') }}" class="btn-primary">Tambah Kelas</a>
        </div>

        <div class="section-card">
            <div class="table-shell">
                <table>
                    <thead>
                        <tr>
                            <th>Kelas</th>
                            <th>Tahun Ajaran</th>
                            <th>Wali Kelas</th>
                            <th>Jumlah Enrollment</th>
                            <th class="w-[220px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kelas as $item)
                            <tr>
                                <td>{{ $item->nama_kelas }}</td>
                                <td>{{ $item->tahunAjaran?->tahun_ajaran }} {{ $item->tahunAjaran?->semester }}</td>
                                <td>{{ $item->waliGuru?->nama ?? '-' }}</td>
                                <td>{{ $item->enrollments->where('status', 'aktif')->count() }}</td>
                                <td>
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.kelas.edit', $item) }}" class="btn-secondary">Edit</a>
                                        <form action="{{ route('admin.kelas.destroy', $item) }}" method="POST" data-confirm-delete>
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
        </div>
    </section>
@endsection

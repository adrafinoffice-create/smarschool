@extends('layouts.panel')

@section('content')
    <section class="page-shell">
        <div class="flex items-end justify-between">
            <div>
                <h1 class="panel-title">Mata Pelajaran</h1>
                <p class="panel-subtitle">Siapkan daftar mapel yang akan diampu guru pada setiap kelas.</p>
            </div>
            <a href="{{ route('admin.mata-pelajaran.create') }}" class="btn-primary">Tambah Mapel</a>
        </div>

        <div class="section-card">
            <div class="table-shell">
                <table>
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th class="w-[220px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mataPelajarans as $mapel)
                            <tr>
                                <td>{{ $mapel->kode }}</td>
                                <td>{{ $mapel->nama }}</td>
                                <td>{{ $mapel->deskripsi ?: '-' }}</td>
                                <td>
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.mata-pelajaran.edit', $mapel) }}" class="btn-secondary">Edit</a>
                                        <form action="{{ route('admin.mata-pelajaran.destroy', $mapel) }}" method="POST" data-confirm-delete>
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

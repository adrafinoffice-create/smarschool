@extends('layouts.panel')

@section('content')
    <section class="page-shell">
        <div class="flex items-end justify-between">
            <div>
                <h1 class="panel-title">Manajemen Guru</h1>
                <p class="panel-subtitle">Data guru menjadi dasar penugasan mapel, wali kelas, jadwal, dan absensi.</p>
            </div>
            <a href="{{ route('admin.guru.create') }}" class="btn-primary">Tambah Guru</a>
        </div>

        <div class="section-card">
            <div class="table-shell">
                <table>
                    <thead>
                        <tr>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Jenis Kelamin</th>
                            <th class="w-[220px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gurus as $guru)
                            <tr>
                                <td>{{ $guru->nip }}</td>
                                <td>{{ $guru->nama }}</td>
                                <td>{{ $guru->user?->email ?? '-' }}</td>
                                <td>{{ $guru->jenis_kelamin }}</td>
                                <td>
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.guru.edit', $guru) }}" class="btn-secondary">Edit</a>
                                        <form action="{{ route('admin.guru.destroy', $guru) }}" method="POST" data-confirm-delete>
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

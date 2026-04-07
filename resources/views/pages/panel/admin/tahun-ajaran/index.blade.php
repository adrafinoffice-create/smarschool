@extends('layouts.panel')

@section('content')
    <section class="page-shell">
        <div class="flex items-end justify-between">
            <div>
                <h1 class="panel-title">Tahun Ajaran</h1>
                <p class="panel-subtitle">Tentukan tahun ajaran aktif yang menjadi acuan enrollment, jadwal, dan absensi.</p>
            </div>
            <a href="{{ route('admin.tahun-ajaran.create') }}" class="btn-primary">Tambah Tahun Ajaran</a>
        </div>

        <div class="section-card">
            <div class="table-shell">
                <table>
                    <thead>
                        <tr>
                            <th>Tahun Ajaran</th>
                            <th>Semester</th>
                            <th>Status</th>
                            <th class="w-[260px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tahunAjarans as $item)
                            <tr>
                                <td>{{ $item->tahun_ajaran }}</td>
                                <td>{{ $item->semester }}</td>
                                <td>
                                    @if ($item->is_active)
                                        <span class="badge-soft bg-emerald-100 text-emerald-700">Aktif</span>
                                    @else
                                        <span class="badge-soft bg-slate-100 text-slate-600">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.tahun-ajaran.edit', $item) }}" class="btn-secondary">Edit</a>
                                        @if (! $item->is_active)
                                            <form action="{{ route('admin.tahun-ajaran.activate', $item) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn-primary px-4 py-2.5" type="submit">Jadikan Aktif</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.tahun-ajaran.destroy', $item) }}" method="POST" data-confirm-delete>
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

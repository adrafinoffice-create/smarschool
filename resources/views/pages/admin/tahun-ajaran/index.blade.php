@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="page-title">{{ $title }}</h4>
        <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addmodal">
            Tambah Data
        </button>
    </div>
    <div class="row">
        <div class="card border-0">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
                    </div>
                @endif
                @error('tahun_ajaran', 'semester')
                    <div class="alert alert-danger alert-dismissible fade" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
                    </div>
                @enderror
                <div class="tab-content">
                    <div class="tab-pane show active" id="state-saving-preview">
                        <table id="state-saving-datatable" class="table activate-select dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Semester</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tahun_ajaran as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->tahun_ajaran }}</td>
                                        <td>{{ $item->semester }}</td>
                                        <td>
                                            <div class="d-flex gap-3">
                                                <a href="{{ route('kelas.edit', $item->id) }}"
                                                    class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{ $item->id }}">
                                                    Edit
                                                </a>
                                                <form action="{{ route('tahun-ajaran.destroy', $item->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-warning btn-sm" type="submit"
                                                        onclick="return confirm('Apakah kamu yakin menghapus data ini.?')">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="editModal{{ $item->id }}Label" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="editModal{{ $item->id }}Label">
                                                        Edit Data</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-hidden="true"></button>
                                                </div>
                                                <form action="{{ route('tahun-ajaran.update', $item->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="tahun_ajaran" class="form-label">Tahun
                                                                Ajaran</label>
                                                            <input type="text" name="tahun_ajaran"id="tahun_ajaran"
                                                                class="form-control" value="{{ $item->tahun_ajaran }}">
                                                        </div>

                                                        <div>
                                                            <label for="semester" class="form-label">Semester</label>
                                                            <select name="semester" id="semester" class="form-select">
                                                                <option value="" selected disabled>-- Pilih Semester
                                                                    --</option>
                                                                <option value="Ganjil" @selected(old('semester', $item->semester) == 'Ganjil')>Ganjil
                                                                </option>
                                                                <option value="Genap" @selected(old('semester', $item->semester) == 'Genap')>Genap
                                                                </option>
                                                            </select>

                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary btn-sm">Simpan
                                                            Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Small modal -->
    <div class="modal fade" id="addmodal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addModalLabel">{{ $title }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="{{ route('tahun-ajaran.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                            <input type="text" name="tahun_ajaran"id="tahun_ajaran" class="form-control" required>
                        </div>
                        <div>
                            <label for="semester" class="form-label">Semester</label>
                            <select name="semester" id="semester" class="form-select">
                                <option value="" selected disabled>-- Pilih Semester --</option>
                                <option value="Ganjil" @selected(old('semester') == 'Ganjil')>Ganjil</option>
                                <option value="Genap" @selected(old('semester') == 'Genap')>Genap</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

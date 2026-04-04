@extends('layouts.admin')

@section('content')
    <h2 class="font-bold mb-3">Pilih Kelas dan bulan untuk melihat rekap</h2>

    <div class="card border-0">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama Kelas</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (Auth::user()->waliKelas->kelas as $item)
                            <tr>
                                <td>{{ $item->nama_kelas }}</td>
                                <td>
                                    <form action="{{ route('guru.rekap-absensi.show', $item->id) }}" method="get">
                                        <div class="d-flex gap-1">
                                            <select name="bulan" id="bulan" class="form-select w-25">
                                                <option value="">Pilih Bulan</option>
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                            <select name="tahun" id="tahun" class="form-select w-25">
                                                <option value="">Pilih Tahun</option>
                                                @for ($i = 2023; $i <= date('Y'); $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                            <button class="btn btn-primary btn-sm" type="submit">Lihat Rekap</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

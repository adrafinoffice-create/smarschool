@extends('layouts.admin')

@section('content')
    <h1 class="text-dark fw-bold">{{ $title }}</h1>

    <div class="row row-cols-2 row-cols-md-3  g-3">
        <div class="col">
            <a href="" class="card card-dashboard">
                <div class="card-body">
                    <div class="d-flex gap-2">
                        <span class="icon">
                            <i class="bx bxs-school"></i>
                        </span>
                        <div>
                            <p class="fw-semibol text-secondary mb-0">Jumlah Kelas Dipegang</p>
                            <h4 class="fw-bold">{{ number_format($kelas->count()) }} Kelas</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>


        <div class="col">
            <a href="#" class="card card-dashboard">
                <div class="card-body">
                    <div class="d-flex gap-3">
                        <span class="icon">
                            <i class="bx bxs-user"></i>
                        </span>
                        <div>
                            <p class="fw-semibol text-secondary mb-0">Jumlah Seluru Siswa</p>
                            <h4 class="fw-bold">{{ number_format($jumlahSiswa) }} Siswa</h4>
                        </div>
                    </div>

                </div>
            </a>
        </div>

        @foreach ($kelas as $item)
            <div class="col">
                <a href="#" class="card card-dashboard">
                    <div class="card-body">
                        <div class="d-flex gap-3">
                            <span class="icon">
                                <i class="bx bxs-user"></i>
                            </span>
                            <div>
                                <p class="fw-semibol text-secondary mb-0">Jumlah Siswa Kelas {{ $item->nama_kelas }}</p>
                                <h4 class="fw-bold">{{ number_format($item->siswa->count()) }} Siswa</h4>
                            </div>
                        </div>

                    </div>
                </a>
            </div>
        @endforeach

        {{-- <div class="col">
            <a href="" class="card card-dashboard">
                <div class="card-body">
                    <div class="d-flex gap-3">
                        <span class="icon">
                            <i class="bx bxs-graduation"></i>
                        </span>
                        <div>
                            <p class="fw-semibol text-secondary mb-0">Jumlah Admin</p>
                            <h4 class="fw-bold">{{ number_format($jumlahAdmin) }} Admin</h4>
                        </div>
                    </div>

                </div>
            </a>
        </div> --}}
    </div>

    {{-- <h1 class="text-dark fw-bold">{{ $title }}</h1> --}}

    {{-- <div class="row row-cols-2 row-cols-md-3  g-3">
        <div class="col">
            <a href="" class="card card-dashboard">
                <div class="card-body">
                    <div class="d-flex gap-2">
                        <span class="icon">
                            <i class="bx bxs-school"></i>
                        </span>
                        <div>
                            <p class="fw-semibol text-secondary mb-0">Jumlah Kelas Mengajar</p>
                            <h4 class="fw-bold">{{ number_format($kelas->count()) }} Kelas</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('siswa.index') }}" class="card card-dashboard">
                <div class="card-body">
                    <div class="d-flex gap-3">
                        <span class="icon">
                            <i class="bx bxs-user"></i>
                        </span>
                        <div>
                            <p class="fw-semibol text-secondary mb-0">Jumlah Siswa</p>
                            <h4 class="fw-bold">{{ number_format($jumlahSiswa) }} Siswa</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        @foreach ($kelas as $item)
            <div class="col">
                <a href="" class="card card-dashboard">
                    <div class="card-body">
                        <div class="d-flex gap-2">
                            <span class="icon">
                                <i class="bx bxs-school"></i>
                            </span>
                            <div>
                                <p class="fw-semibol text-secondary mb-0">Jumlah Siswa Kelas {{ $item->nama_kelas }}</p>
                                <h4 class="fw-bold">{{ number_format($item->siswa->count()) }} Siswa</h4>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div> --}}
@endsection

@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="fw-bold mb-4">Detail Siswa</h2>
        <a href="{{ route('siswa.index') }}" class="btn btn-light border">
            Kembali
        </a>
    </div>

    <div class="row g-3">
        <div class="col-md-8">
            <div class="card border-0">
                <div class="card-body">
                    <h5 class="mb-4 fw-semibold">Informasi Siswa</h5>
                    <div class="mb-3">
                        <p class="mb-0 text-secondary">Kelas</p>
                        <p class="mb-0 fw-medium">Kelas {{ $siswa->kelas->nama_kelas }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="mb-0 text-secondary">NIS</p>
                        <p class="mb-0 fw-medium"> {{ $siswa->nis }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="mb-0 text-secondary">Nama Lengkap</p>
                        <p class="mb-0 fw-medium">{{ $siswa->nama }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="mb-0 text-secondary">Jenis Kelamin</p>
                        <p class="mb-0 fw-medium">{{ $siswa->jenis_kelamin }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="mb-0 text-secondary">Tanggal Lahir</p>
                        <p class="mb-0 fw-medium">{{ $siswa->tanggal_lahir }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="mb-0 text-secondary">Tempat Lahir</p>
                        <p class="mb-0 fw-medium">{{ $siswa->tempat_lahir }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="mb-0 text-secondary">Alamat</p>
                        <p class="mb-0 fw-medium">{{ $siswa->alamat }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="mb-0 text-secondary">Nama Orang Tua</p>
                        <p class="mb-0 fw-medium">{{ $siswa->nama_orang_tua }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="mb-0 text-secondary">No Hp Orang Tua</p>
                        <p class="mb-0 fw-medium">{{ $siswa->no_hp }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0">
                <div class="card-body">
                    <h5 class="mb-4 fw-semibold">QR Code Siswa</h5>
                    <div class="d-flex justify-content-center mb-2">
                        {!! $qrCode !!}
                    </div>
                    <a href="{{ route('siswa.qr-code.download', $siswa->id) }}"
                        class="btn btn-primary mx-auto w-100 d-flex justify-content-center mb-2" style="width: mx-content">
                        <i class="bx bx-download"></i> Download
                    </a>
                    <a href="{{ route('siswa.kartu-pelajar.download', $siswa->id) }}" class="btn btn-success mx-auto w-100"
                        style="width: mx-content">
                        <i class="bx bx-user-pin"></i> Download Kartu Pelajar
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

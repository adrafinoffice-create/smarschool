@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{ $title }}</h4>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
                        </div>
                    @endif

                </div>
            </div>
        </div>
        <div class="row g-3 mt-3">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Daftar Siswa</h5>
                        <div class="tab-content">
                            <div class="tab-pane show active" id="state-saving-preview">
                                <table id="state-saving-datatable" class="table activate-select dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Siswa</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Kehadiran</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kelas->siswa as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ $item->nis }} <br>
                                                    {{ $item->nama }} <br>
                                                </td>
                                                <td>{{ $item->jenis_kelamin }}</td>
                                                <td>

                                                    @if ($kelas->absensi->where('siswa_id', $item->id)->where('tanggal', now()->format('Y-m-d'))->count() > 0)
                                                        @php
                                                            $status = $kelas->absensi
                                                                ->where('siswa_id', $item->id)
                                                                ->where('tanggal', now()->format('Y-m-d'))
                                                                ->first()->status;
                                                        @endphp

                                                        @if ($status == 'Hadir')
                                                            <span class="badge bg-success">Hadir</span>
                                                        @elseif($status == 'Izin')
                                                            <span class="badge bg-warning">Izin</span>
                                                        @elseif($status == 'Sakit')
                                                            <span class="badge bg-info">Sakit</span>
                                                        @elseif($status == 'Alpa')
                                                            <span class="badge bg-danger">Alpa</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-light text-dark">Belum Absen</span>
                                                    @endif
                                                </td>

                                                {{-- <td>

                                                    @if ($item->absensi -
        where('jam_pulang')->where('tanggal', now()->format('Y-m-d'))->first() ==
    null)
                                                        <span class="budge bg-success">Sudah pulang</span>
                                                    @else
                                                        <span class="budge bg-info">Belum Pulang</span>
                                                    @endif
                                                </td> --}}

                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-light border dropdown-toggle"
                                                            type="button" data-bs-toggle="dropdown">
                                                            @if ($kelas->absensi->where('siswa_id', $item->id)->where('tanggal', now()->format('Y-m-d'))->count() > 0)
                                                                Ubah Status
                                                            @else
                                                                Pilih Kehadiran
                                                            @endif

                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <form action="{{ route('absensi.storeManual') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <input type="hidden" name="siswa_id"
                                                                        value="{{ $item->id }}">
                                                                    <input type="hidden" name="status" value="Hadir">
                                                                    <button class="dropdown-item" type="submit">
                                                                        Hadir
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('absensi.storeManual') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <input type="hidden" name="siswa_id"
                                                                        value="{{ $item->id }}">
                                                                    <input type="hidden" name="status" value="Alpa">
                                                                    <button class="dropdown-item" type="submit">
                                                                        Alpa
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('absensi.storeManual') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <input type="hidden" name="siswa_id"
                                                                        value="{{ $item->id }}">
                                                                    <input type="hidden" name="status" value="Izin">
                                                                    <button class="dropdown-item" type="submit">
                                                                        Izin
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('absensi.storeManual') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <input type="hidden" name="siswa_id"
                                                                        value="{{ $item->id }}">
                                                                    <input type="hidden" name="status" value="Sakit">
                                                                    <button class="dropdown-item" type="submit">
                                                                        Sakit
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0">
                    <div class="card-body p-4">
                        <h5 class="mb-3">Scan Barcode disini</h5>
                        <div id="reader" style="width: 100%; border-radius: 10px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('addon-script')
    <script src="https://unpkg.com/html5-qrcode"></script>


    <script>
        function onScanSuccess(decodedText, decodeResult) {
            html5QrcodeScanner.clear();

            fetch("{{ route('absensi.storeQr') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        siswa_id: decodedText
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message == 'Sudah Absen') {
                        alert('Siswa sudah absen hari ini');
                    } else if (data.message == 'absen berhasil') {
                        alert('Siswa berhasil absen');
                    } else if (data.message == 'Terlambat') {
                        alert('Siswa terlambat absen');
                    } else if (data.message == 'Pulang') {
                        alert('Siswa sudah pulang');
                    } else {
                        alert('Siswa tidak terdaftar');
                    }

                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function onScanFailure(error) {
            // Optional: console.log(error);
        }

        let config = {
            fps: 10,
            qrbox: {
                width: 250,
                height: 250
            },
            aspectRatio: 1.0
        };

        let html5QrcodeScanner = new Html5QrcodeScanner("reader", config, false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>



    {{-- <script>
        function onScanSuccess(decorderText, decodeResult) {
            html5QrcodeScanner.clear();

            fetch("{{ route('absensi.storeQr') }}", {
                    method: 'POST',
                    headers: {
                        'content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },

                    body: JSON.stringify({
                        siswa_id: decorderText
                    })
                }).then(response => response.json())

                .then(data => {
                        if (data.message == 'Sudah Absen') {
                            alert('Siswa sudah absen hari ini')
                            location.reload();
                        } else if (data.message == 'absen berhasil') {
                            alert('Siswa berhasil absen')
                            location.reload();
                        } else if (data.message == 'Terlambat') {
                            alert('Siswa terlambat absen')
                            location.reload();
                        } else if (data.message == 'Pulang') {
                            alert('Siswa sudah pulang')
                            location.reload();

                        } else {
                            alert('Siswa tidak terdaftar')
                            location.reload();
                        }
                        .catch(error => {
                            console.error('Error:', error);
                        });
                    }
                }

            function onScanFailure(error) {
                // Kosong
            }

            let config = {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                },
                aspectRatio: 1.0
            }
        }

        let html5QrcodeScanner = new Html5QrcodeScanner("reader", config, false)
        html5QrcodeScanner.render(onScanSuccess, onScanFailure)
    </script> --}}
@endpush

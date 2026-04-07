@extends('layouts.panel')

@section('content')
    <section class="page-shell">
        <div>
            <h1 class="panel-title">Tambah Siswa</h1>
            <p class="panel-subtitle">Isi biodata siswa sebelum melakukan enrollment ke kelas.</p>
        </div>

        <div class="section-card max-w-5xl">
            <form action="{{ route('admin.siswa.store') }}" method="POST" class="grid gap-5 md:grid-cols-2" data-enhanced-form>
                @csrf
                @include('pages.panel.admin.siswa.partials.form-fields', ['siswa' => null])
                <div class="md:col-span-2 flex gap-3">
                    <button class="btn-primary" type="submit">Simpan Siswa</button>
                    <a href="{{ route('admin.siswa.index') }}" class="btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </section>
@endsection

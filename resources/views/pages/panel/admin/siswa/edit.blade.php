@extends('layouts.panel')

@section('content')
    <section class="page-shell">
        <div>
            <h1 class="panel-title">Edit Siswa</h1>
            <p class="panel-subtitle">Perbarui biodata siswa dan kelas awalnya.</p>
        </div>

        <div class="section-card max-w-5xl">
            <form action="{{ route('admin.siswa.update', $siswa) }}" method="POST" class="grid gap-5 md:grid-cols-2" data-enhanced-form>
                @csrf
                @method('PUT')
                @include('pages.panel.admin.siswa.partials.form-fields', ['siswa' => $siswa])
                <div class="md:col-span-2 flex gap-3">
                    <button class="btn-primary" type="submit">Simpan Perubahan</button>
                    <a href="{{ route('admin.siswa.index') }}" class="btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </section>
@endsection

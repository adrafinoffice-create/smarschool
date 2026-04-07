@extends('layouts.panel')

@section('content')
    <section class="page-shell">
        {{-- Page Header --}}
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="panel-title">Detail Siswa</h1>
                <p class="panel-subtitle">Informasi lengkap, QR Code, dan kartu pelajar siswa.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.siswa.index') }}" class="btn-secondary">
                    &larr; Kembali
                </a>
                <a href="{{ route('admin.siswa.edit', $siswa->id) }}" class="btn-primary">
                    Edit Siswa
                </a>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-3 mt-4">

            {{-- ── Informasi Siswa ── --}}
            <div class="section-card md:col-span-2">
                <div class="flex items-center gap-2 mb-5 pb-3 border-b" style="border-color: var(--border, #e5e7eb)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="color: var(--primary, #2563eb)" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                    <h2 class="text-sm font-bold" style="color: var(--on-surface, #111827)">Informasi Siswa</h2>
                </div>

                <dl class="grid grid-cols-1 gap-y-5 sm:grid-cols-2">

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-widest mb-1"
                            style="color: var(--on-surface-muted, #6b7280)">NIS</dt>
                        <dd class="text-sm font-bold" style="color: var(--on-surface, #111827)">{{ $siswa->nis }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-widest mb-1"
                            style="color: var(--on-surface-muted, #6b7280)">Kelas</dt>
                        <dd class="text-sm font-bold" style="color: var(--on-surface, #111827)">
                            {{ $siswa->kelas?->nama_kelas ?? '-' }}
                        </dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-widest mb-1"
                            style="color: var(--on-surface-muted, #6b7280)">Nama Lengkap</dt>
                        <dd class="text-sm font-bold" style="color: var(--on-surface, #111827)">{{ $siswa->nama }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-widest mb-1"
                            style="color: var(--on-surface-muted, #6b7280)">Jenis Kelamin</dt>
                        <dd class="text-sm font-bold" style="color: var(--on-surface, #111827)">{{ $siswa->jenis_kelamin }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-widest mb-1"
                            style="color: var(--on-surface-muted, #6b7280)">Tanggal Lahir</dt>
                        <dd class="text-sm font-bold" style="color: var(--on-surface, #111827)">
                            {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y') }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-widest mb-1"
                            style="color: var(--on-surface-muted, #6b7280)">Tempat Lahir</dt>
                        <dd class="text-sm font-bold" style="color: var(--on-surface, #111827)">{{ $siswa->tempat_lahir }}</dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-widest mb-1"
                            style="color: var(--on-surface-muted, #6b7280)">Alamat</dt>
                        <dd class="text-sm font-bold" style="color: var(--on-surface, #111827)">{{ $siswa->alamat }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-widest mb-1"
                            style="color: var(--on-surface-muted, #6b7280)">Nama Orang Tua</dt>
                        <dd class="text-sm font-bold" style="color: var(--on-surface, #111827)">{{ $siswa->nama_orang_tua }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-widest mb-1"
                            style="color: var(--on-surface-muted, #6b7280)">No. HP Orang Tua</dt>
                        <dd class="text-sm font-bold" style="color: var(--on-surface, #111827)">{{ $siswa->no_hp }}</dd>
                    </div>

                </dl>
            </div>

            {{-- ── Panel Kanan ── --}}
            <div class="flex flex-col gap-5">

                {{-- QR Code Card --}}
                <div class="section-card flex flex-col items-center text-center">
                    <div class="flex items-center gap-2 mb-4 pb-3 border-b w-full text-left" style="border-color: var(--border, #e5e7eb)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="color: var(--primary, #2563eb)" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                            <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                        </svg>
                        <h2 class="text-sm font-bold" style="color: var(--on-surface, #111827)">QR Code</h2>
                    </div>
                    <div class="p-3 rounded-xl inline-block mb-3"
                        style="background: #fff; border: 2px solid #e0e7ff; box-shadow: 0 2px 12px rgba(37,99,235,0.08)">
                        {!! $qrCode !!}
                    </div>
                    <p class="text-xs mb-4" style="color: var(--on-surface-muted, #6b7280)">
                        NIS: <strong style="color: var(--on-surface, #111827)">{{ $siswa->nis }}</strong>
                    </p>
                    <a href="{{ route('admin.siswa.qr-code.download', $siswa->id) }}"
                        class="btn-primary w-full flex items-center justify-center gap-2 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        Unduh QR Code (.png)
                    </a>
                </div>

                {{-- Kartu Pelajar Card --}}
                <div class="section-card flex flex-col">
                    <div class="flex items-center gap-2 mb-4 pb-3 border-b" style="border-color: var(--border, #e5e7eb)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="color: var(--primary, #2563eb)" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>
                        </svg>
                        <h2 class="text-sm font-bold" style="color: var(--on-surface, #111827)">Kartu Pelajar</h2>
                    </div>
                    <p class="text-xs mb-4" style="color: var(--on-surface-muted, #6b7280)">
                        Pratinjau atau unduh kartu pelajar resmi dalam format PDF.
                    </p>
                    <div class="flex flex-col gap-3">
                        <button type="button"
                            onclick="openModal()"
                            class="btn-secondary w-full flex items-center justify-center gap-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            Preview Kartu Pelajar
                        </button>
                        <a href="{{ route('admin.siswa.kartu-pelajar.download', $siswa->id) }}"
                            class="btn-primary w-full flex items-center justify-center gap-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Unduh Kartu Pelajar (.pdf)
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ═══ MODAL PREVIEW KARTU PELAJAR ═══ --}}
    <div id="modal-kartu"
        class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
        style="background: rgba(0,0,0,0.7); backdrop-filter: blur(6px)">

        <div class="rounded-2xl shadow-2xl w-full max-w-3xl flex flex-col overflow-hidden"
            style="max-height: 90vh; background: var(--surface, #fff)">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4"
                style="border-bottom: 1px solid var(--border, #e5e7eb)">
                <div>
                    <h3 class="text-sm font-bold" style="color: var(--on-surface, #111827)">
                        Preview Kartu Pelajar
                    </h3>
                    <p class="text-xs mt-0.5" style="color: var(--on-surface-muted, #6b7280)">
                        {{ $siswa->nama }} &mdash; NIS {{ $siswa->nis }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.siswa.kartu-pelajar.download', $siswa->id) }}"
                        class="btn-primary text-xs flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        Unduh PDF
                    </a>
                    <button type="button" onclick="closeModal()"
                        class="rounded-lg p-1.5 transition-colors"
                        style="color: var(--on-surface-muted, #6b7280)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="flex-1 overflow-auto p-4" style="background: #f1f5f9">
                <iframe
                    id="iframe-kartu"
                    src=""
                    data-src="{{ route('admin.siswa.kartu-pelajar.preview', $siswa->id) }}"
                    class="w-full rounded-xl"
                    style="min-height: 500px; border: 1px solid #e0e7ff; background: white"
                    title="Preview Kartu Pelajar {{ $siswa->nama }}">
                </iframe>
            </div>
        </div>
    </div>

    <script>
        var modal = document.getElementById('modal-kartu');
        var iframe = document.getElementById('iframe-kartu');
        var loaded = false;

        function openModal() {
            modal.classList.remove('hidden');
            // Lazy load iframe hanya saat dibuka pertama kali
            if (!loaded) {
                iframe.src = iframe.dataset.src;
                loaded = true;
            }
        }

        function closeModal() {
            modal.classList.add('hidden');
        }

        // Tutup saat klik backdrop
        modal.addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // Tutup dengan ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });
    </script>
@endsection

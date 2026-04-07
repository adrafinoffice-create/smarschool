@extends('layouts.panel')

@section('content')
    <section class="page-shell">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="panel-title">Enrollment Siswa</h1>
                <p class="panel-subtitle">Pilih kelas terlebih dahulu, lalu tambahkan hanya siswa yang memang masih belum memiliki enrollment aktif.</p>
            </div>
            <div class="badge-soft bg-primary/10 text-primary">
                {{ $tahunAjaran ? 'Tahun Aktif: ' . $tahunAjaran->tahun_ajaran . ' - ' . $tahunAjaran->semester : 'Tahun ajaran aktif belum tersedia' }}
            </div>
        </div>

        <div class="section-card">
            <div class="mb-4">
                <h2 class="headline-text text-xl font-black">1. Pilih Kelas Tujuan</h2>
                <p class="panel-subtitle">Klik salah satu kelas untuk melihat siswa aktif dan menambahkan enrollment baru.</p>
            </div>

            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($kelasStats as $item)
                    @php
                        $kelasItem = $item['kelas'];
                        $isActive = $selectedKelas && $selectedKelas->id === $kelasItem->id;
                    @endphp
                    <a href="{{ route('admin.enrollment.index', ['kelas_id' => $kelasItem->id]) }}"
                        class="{{ $isActive ? 'border-primary bg-primary/5 ring-2 ring-primary/15' : 'border-slate-200 hover:border-primary/30 hover:bg-slate-50' }} block rounded-xl border p-4 transition">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="headline-text text-lg font-black text-on-surface">{{ $kelasItem->nama_kelas }}</p>
                                <p class="mt-1 text-xs text-on-surface-variant">
                                    {{ $kelasItem->waliGuru?->nama ? 'Wali: ' . $kelasItem->waliGuru->nama : 'Wali kelas belum dipilih' }}
                                </p>
                            </div>
                            @if ($isActive)
                                <span class="badge-soft bg-primary text-white">Aktif</span>
                            @endif
                        </div>
                        <div class="mt-4 flex items-center justify-between text-sm">
                            <span class="text-on-surface-variant">Siswa aktif</span>
                            <span class="font-black text-on-surface">{{ $item['total_aktif'] }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[0.95fr_1.05fr]">
            <div class="section-card">
                <div class="mb-4 flex flex-col gap-2">
                    <h2 class="headline-text text-xl font-black">2. Tambah Siswa ke {{ $selectedKelas?->nama_kelas ?? 'Kelas' }}</h2>
                    <p class="panel-subtitle">
                        Yang tampil di daftar ini hanya siswa yang belum memiliki enrollment aktif pada tahun ajaran berjalan.
                    </p>
                </div>

                @if (! $tahunAjaran)
                    <div class="rounded-xl border border-dashed border-slate-200 px-4 py-6 text-sm text-on-surface-variant">
                        Tahun ajaran aktif belum tersedia. Aktifkan tahun ajaran terlebih dahulu.
                    </div>
                @else
                    <form action="{{ route('admin.enrollment.store') }}" method="POST" class="space-y-5" data-enhanced-form>
                        @csrf
                        <input type="hidden" name="kelas_id" value="{{ $selectedKelas?->id }}">

                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">Kelas Dipilih</p>
                            <p class="mt-2 text-lg font-black text-on-surface">{{ $selectedKelas?->nama_kelas ?? '-' }}</p>
                            <p class="mt-1 text-sm text-on-surface-variant">
                                {{ $selectedKelas?->waliGuru?->nama ? 'Wali kelas: ' . $selectedKelas->waliGuru->nama : 'Wali kelas belum diatur' }}
                            </p>
                        </div>

                        <div>
                            <label class="form-label" for="siswa_search">Cari siswa tersedia</label>
                            <input class="form-input" id="siswa_search" type="text" placeholder="Ketik nama atau NIS siswa" data-enrollment-search>
                        </div>

                        <div class="space-y-3 max-h-[520px] overflow-y-auto pr-1" data-enrollment-available-list>
                            @forelse ($availableSiswas as $item)
                                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 transition hover:border-primary/30 hover:bg-slate-50"
                                    data-enrollment-item data-search="{{ strtolower($item->nis . ' ' . $item->nama) }}">
                                    <input class="mt-1 h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary/20"
                                        type="checkbox" name="siswa_id[]" value="{{ $item->id }}">
                                    <div class="min-w-0">
                                        <p class="font-black text-on-surface">{{ $item->nama }}</p>
                                        <p class="mt-1 text-sm text-on-surface-variant">{{ $item->nis }}</p>
                                        <p class="mt-2 text-xs text-slate-400">
                                            Data induk saat ini: {{ $item->kelas?->nama_kelas ?? 'Belum ada kelas induk' }}
                                        </p>
                                    </div>
                                </label>
                            @empty
                                <div class="rounded-xl border border-dashed border-slate-200 px-4 py-6 text-sm text-on-surface-variant">
                                    Tidak ada siswa yang tersedia untuk dienroll. Semua siswa sudah aktif di kelas masing-masing.
                                </div>
                            @endforelse
                        </div>

                        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                            Jika seorang siswa masih aktif di kelas lain, enrollment lama harus dihapus lebih dulu dari daftar sebelah kanan.
                        </div>

                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm text-on-surface-variant">
                                Dipilih:
                                <span class="font-black text-on-surface" data-enrollment-selected-count>0</span>
                                siswa
                            </p>
                            <button class="btn-primary" type="submit" {{ $availableSiswas->isEmpty() || ! $selectedKelas ? 'disabled' : '' }}>
                                Tambahkan ke Kelas
                            </button>
                        </div>
                    </form>
                @endif
            </div>

            <div class="section-card">
                <div class="mb-4 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="headline-text text-xl font-black">3. Siswa Aktif di {{ $selectedKelas?->nama_kelas ?? 'Kelas' }}</h2>
                        <p class="panel-subtitle">Hapus enrollment dari sini jika siswa ingin dipindahkan ke kelas lain.</p>
                    </div>
                    <span class="badge-soft bg-slate-100 text-slate-700">
                        Total aktif: {{ $enrollments->count() }}
                    </span>
                </div>

                <div class="space-y-3">
                    @forelse ($enrollments as $enrollment)
                        <div class="flex flex-col gap-4 rounded-xl border border-slate-200 p-4 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="font-black text-on-surface">{{ $enrollment->siswa->nama }}</p>
                                <p class="mt-1 text-sm text-on-surface-variant">{{ $enrollment->siswa->nis }}</p>
                                <p class="mt-2 text-xs text-slate-400">
                                    Tanggal masuk: {{ optional($enrollment->tanggal_masuk)->format('d M Y') ?? '-' }}
                                </p>
                            </div>
                            <form action="{{ route('admin.enrollment.destroy', $enrollment) }}" method="POST" data-confirm-delete>
                                @csrf
                                @method('DELETE')
                                <button class="btn-danger" type="submit">Hapus Enrollment</button>
                            </form>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-200 px-4 py-6 text-center text-sm text-on-surface-variant">
                            Belum ada siswa aktif di kelas ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.querySelector('[data-enrollment-search]');
            const items = [...document.querySelectorAll('[data-enrollment-item]')];
            const checkboxes = [...document.querySelectorAll('input[name="siswa_id[]"]')];
            const selectedCountNode = document.querySelector('[data-enrollment-selected-count]');

            const updateSelectedCount = () => {
                if (!selectedCountNode) {
                    return;
                }

                selectedCountNode.textContent = String(checkboxes.filter((item) => item.checked).length);
            };

            searchInput?.addEventListener('input', () => {
                const query = searchInput.value.trim().toLowerCase();

                items.forEach((item) => {
                    const haystack = item.dataset.search || '';
                    const match = query === '' || haystack.includes(query);
                    item.classList.toggle('hidden', !match);
                });
            });

            checkboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', updateSelectedCount);
            });

            updateSelectedCount();
        });
    </script>
@endsection

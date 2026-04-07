@props(['pageKey' => null])

@php
    $isAdmin = auth()->user()?->role === 'admin';

    $items = $isAdmin
        ? [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'dashboard', 'key' => 'dashboard'],
            ['label' => 'Tahun Ajaran', 'route' => 'admin.tahun-ajaran.index', 'icon' => 'calendar_month', 'key' => 'tahun-ajaran'],
            ['label' => 'Guru', 'route' => 'admin.guru.index', 'icon' => 'badge', 'key' => 'guru'],
            ['label' => 'Mata Pelajaran', 'route' => 'admin.mata-pelajaran.index', 'icon' => 'menu_book', 'key' => 'mata-pelajaran'],
            ['label' => 'Kelas', 'route' => 'admin.kelas.index', 'icon' => 'meeting_room', 'key' => 'kelas'],
            ['label' => 'Siswa', 'route' => 'admin.siswa.index', 'icon' => 'groups', 'key' => 'siswa'],
            ['label' => 'Enrollment', 'route' => 'admin.enrollment.index', 'icon' => 'group_add', 'key' => 'enrollment'],
            ['label' => 'Penugasan', 'route' => 'admin.pengampu.index', 'icon' => 'assignment_ind', 'key' => 'pengampu'],
            ['label' => 'Jadwal', 'route' => 'admin.jadwal.index', 'icon' => 'schedule', 'key' => 'jadwal'],
            ['label' => 'Laporan Absensi', 'route' => 'admin.laporan-absensi.index', 'icon' => 'fact_check', 'key' => 'laporan-absensi'],
            ['label' => 'Pengaturan', 'route' => 'admin.pengaturan.index', 'icon' => 'settings', 'key' => 'pengaturan'],
        ]
        : [
            ['label' => 'Dashboard Guru', 'route' => 'guru.dashboard', 'icon' => 'dashboard', 'key' => 'guru-dashboard'],
            ['label' => 'Jadwal Mengajar', 'route' => 'guru.jadwal.index', 'icon' => 'calendar_month', 'key' => 'guru-jadwal'],
            ['label' => 'Rekap Absensi', 'route' => 'guru.rekap.index', 'icon' => 'overview', 'key' => 'guru-rekap'],
        ];

    $appTitle = $isAdmin ? 'SmarSchool Admin' : 'SmarSchool Guru';
@endphp

<aside
    class="relative sticky top-0 z-50 hidden h-screen w-72 flex-col overflow-visible rounded-r-[2rem] bg-slate-50 px-4 py-8 text-sm font-medium transition-all duration-300 md:flex"
    data-sidebar="desktop" data-collapsed="false">
    <button
        class="absolute -right-4 top-5 z-[60] flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white shadow-md transition-all hover:bg-slate-50"
        data-sidebar-toggle type="button">
        <span class="material-symbols-outlined text-lg text-primary transition-transform duration-300"
            data-sidebar-toggle-icon>chevron_left</span>
    </button>

    <div class="mb-8 flex items-center gap-3 px-2">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary text-white">
            <span class="material-symbols-outlined">school</span>
        </div>
        <div class="sidebar-text">
            <h1 class="headline-text text-lg font-extrabold text-primary">{{ $appTitle }}</h1>
            <p class="text-[11px] uppercase tracking-[0.24em] text-slate-400">
                {{ $isAdmin ? 'Academic Operations' : 'Teaching Session' }}
            </p>
        </div>
    </div>

    <nav class="flex flex-1 flex-col gap-1">
        @foreach ($items as $item)
            <a href="{{ route($item['route']) }}" @class([
                'desktop-nav-item flex items-center rounded-2xl px-4 py-3 transition-all duration-300',
                'nav-link-active' => $pageKey === $item['key'],
                'text-slate-500 hover:bg-slate-100 hover:text-primary' => $pageKey !== $item['key'],
            ])>
                <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
                <span class="sidebar-text ml-3">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="mt-auto border-t border-slate-100 pt-6">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button
                class="desktop-nav-item flex w-full items-center rounded-2xl px-4 py-3 text-slate-500 transition hover:bg-rose-50 hover:text-error"
                type="submit">
                <span class="material-symbols-outlined">logout</span>
                <span class="sidebar-text ml-3">Logout</span>
            </button>
        </form>
    </div>
</aside>

<div class="pointer-events-none fixed inset-0 z-50 md:hidden" data-sidebar="mobile">
    <div class="absolute inset-0 bg-slate-900/60 opacity-0 transition-opacity duration-300" data-mobile-overlay></div>
    <aside
        class="absolute left-0 top-0 flex h-full w-72 -translate-x-full flex-col gap-1 bg-white p-6 shadow-2xl transition-transform duration-300"
        data-mobile-content>
        <div class="mb-8 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary text-white">
                    <span class="material-symbols-outlined">school</span>
                </div>
                <span class="headline-text text-lg font-extrabold text-primary">{{ $appTitle }}</span>
            </div>
            <button class="p-2 text-slate-400 transition-colors hover:text-slate-600" data-mobile-drawer-close
                type="button">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <nav class="space-y-2">
            @foreach ($items as $item)
                <a href="{{ route($item['route']) }}" data-mobile-nav-link @class([
                    'flex items-center gap-3 rounded-2xl px-4 py-3 font-medium transition-all',
                    'mobile-nav-link-active' => $pageKey === $item['key'],
                    'text-slate-500 hover:bg-slate-50' => $pageKey !== $item['key'],
                ])>
                    <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="mt-auto border-t border-slate-100 pt-6">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-slate-500 transition hover:bg-rose-50 hover:text-error"
                    type="submit">
                    <span class="material-symbols-outlined">logout</span>
                    Logout
                </button>
            </form>
        </div>
    </aside>
</div>

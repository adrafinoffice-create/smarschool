@props(['title' => null])

<header
    class="sticky top-0 z-40 flex items-center justify-between bg-white/85 px-4 py-4 shadow-[0_20px_50px_rgba(53,37,205,0.08)] backdrop-blur-md md:px-6">
    <div class="flex items-center gap-4">
        <button class="rounded-xl p-2 text-slate-500 transition hover:bg-slate-100 md:hidden" data-mobile-drawer-toggle
            type="button">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <div>
            <p class="text-xs font-black uppercase tracking-[0.24em] text-slate-400">SmarSchool</p>
            <p class="headline-text text-lg font-extrabold text-on-surface">{{ $title ?? 'Panel' }}</p>
        </div>
    </div>

    <div class="relative" data-profile-menu>
        <button
            class="group flex items-center gap-3 rounded-2xl border border-transparent bg-white/80 px-2 py-1 transition-all hover:border-indigo-100 focus:outline-none"
            data-profile-toggle type="button" aria-expanded="false">
            <div class="hidden text-right sm:block">
                <p class="text-sm font-bold leading-none text-on-surface">{{ auth()->user()->name }}</p>
                <p class="text-[11px] font-medium capitalize text-on-surface-variant">{{ auth()->user()->role }}</p>
            </div>
            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                <span class="material-symbols-outlined">person</span>
            </div>
            <span class="material-symbols-outlined hidden text-slate-400 transition-transform duration-300 sm:block"
                data-profile-chevron>expand_more</span>
        </button>

        <div class="profile-menu pointer-events-none absolute right-0 top-[calc(100%+0.75rem)] z-50 w-72 translate-y-2 rounded-3xl border border-slate-100 bg-white p-3 opacity-0 shadow-[0_24px_60px_rgba(53,37,205,0.12)] transition-all duration-200"
            data-profile-panel>
            <div class="rounded-2xl bg-[radial-gradient(circle_at_top_left,_#e2dfff_0%,_#f7f9fb_52%,_#ffffff_100%)] p-4">
                <p class="text-sm font-black text-on-surface">{{ auth()->user()->name }}</p>
                <p class="mt-1 text-xs capitalize text-on-surface-variant">{{ auth()->user()->role }}</p>
                <p class="mt-2 truncate text-xs text-slate-400">{{ auth()->user()->email }}</p>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="mt-3">
                @csrf
                <button
                    class="flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-600 transition-all hover:bg-rose-50 hover:text-error"
                    type="submit">
                    <span class="material-symbols-outlined text-[20px]">logout</span>
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>

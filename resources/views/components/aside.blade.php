 <aside>
     <div class="d-flex align-item-center justify-content-between">
         <a href="#" class="text-dark text-decoration-none fw-bold fs-5">Absen Siswa</a>
         <button class="btn btn-light d-block d-md-none" type="button" onclick="toggleMenu()">
             <i class="bx bx-menu fs-5"></i>
         </button>
     </div>

     <div class="mt-5 d-none d-md-block" id="sidebarMenu">
         @if (Auth::user()->role == 'admin')
             <div class="mb-3">
                 <p class="sidebar-text text-secondary">Utama</p>
                 <a href="{{ route('admin.dashboard') }}"
                     class="btn sidebar-btn {{ request()->is('admin') ? 'active' : '' }}">
                     <i class="bx bx-home"></i> Dashboard
                 </a>
             </div>
             <div class="mb-3">
                 <p class="sidebar-text text-secondary">REKAP</p>
                 <a href="{{ route('admin.kehadiran') }}"
                     class="btn sidebar-btn {{ request()->is('admin/kehadiran*') ? 'active' : '' }}">
                     <i class="bx bx-file"></i> Kehadiran
                 </a>

                 <a href="{{ route('admin.rekap-absensi.index') }}" class="btn sidebar-btn ">
                     <i class="bx bx-home"></i> Rekap
                 </a>
             </div>
             <div class="mb-3">
                 <p class="sidebar-text text-secondary">Master</p>
                 <a href="{{ route('tahun-ajaran.index') }}"
                     class="btn sidebar-btn {{ request()->is('admin/tahun-ajaran') ? 'active' : '' }}">
                     <i class="bx bx-graduation"></i> Tahun Ajaran
                 </a>
             </div>
             <div class="mb-3">
                 <p class="sidebar-text text-secondary">Kelas</p>
                 <a href="{{ route('siswa.index') }}"
                     class="btn sidebar-btn {{ request()->is('admin/siswa*') ? 'active' : '' }}">
                     <i class="bx bxs-graduation"></i> Siswa
                 </a>
                 <a href="{{ route('wali-kelas.index') }}"
                     class="btn sidebar-btn {{ request()->is('admin/wali-kelas*') ? 'active' : '' }}">
                     <i class="bx bxs-graduation"></i> Wali kelas
                 </a>
                 <a href="{{ route('kelas.index') }}"
                     class="btn sidebar-btn {{ request()->is('admin/kelas*') ? 'active' : '' }}">
                     <i class="bx bxs-graduation"></i> Kelas
                 </a>
             </div>
             <div class="mb-3">
                 <p class="sidebar-text text-secondary">PENGATURAN</p>
                 <a href="{{ route('pengaturan.index') }}"
                     class="btn sidebar-btn {{ request()->is('admin/pengaturan') ? 'active' : '' }}">
                     <i class="bx bx-graduation"></i> Pengaturan
                 </a>
                 <a href="#" class="btn sidebar-btn"
                     onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                     <i class="bx bx-log-out"></i> Log Out
                 </a>
                 <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                     @csrf
                 </form>
             </div>
         @elseif (Auth::user()->role == 'guru')
             {{-- UNTUK GURU --}}
             <div class="mb-3">
                 <p class="sidebar-text text-secondary">Utama</p>
                 <a href="{{ route('guru.dashboard') }}"
                     class="btn sidebar-btn {{ request()->is('dashboard') ? 'active' : '' }}">
                     <i class="bx bx-home"></i> Dashboard
                 </a>
             </div>
             <div class="mb-3">
                 <p class="sidebar-text text-secondary">Absensi Kelas</p>
                 @foreach (Auth::user()->waliKelas->kelas as $kelasAnda)
                     <a href="{{ route('absensi.show', $kelasAnda->id) }}"
                         class="btn sidebar-btn {{ request()->is('absensi/', $kelasAnda->id) ? 'active' : '' }}">
                         <i class="bx bx-home"></i> Kelas {{ $kelasAnda->nama_kelas }}
                     </a>
                 @endforeach
             </div>
             <div class="mb-3">
                 <p class="sidebar-text text-secondary">Catatan Kehadiran</p>
                 @foreach (Auth::user()->waliKelas->kelas as $kelasAnda)
                     <a href="{{ route('kehadira.show', $kelasAnda->id) }}"
                         class="btn sidebar-btn {{ request()->is('kehadiran/' . $kelasAnda->id . '*') ? 'active' : '' }}">
                         <i class="bx bx-home"></i> Kelas {{ $kelasAnda->nama_kelas }}
                     </a>
                 @endforeach
             </div>
             <div class="mb-3">
                 <p class="sidebar-text text-secondary">Rekap Absensi</p>
                 <a href="{{ route('guru.rekap-absensi.index') }}"
                     class="btn sidebar-btn {{ request()->is('rekap*') ? 'active' : '' }}">
                     <i class="bx bx-home"></i> Rekap Absensi
                 </a>
             </div>
             <div class="mb-3">
                 <p class="sidebar-text text-secondary">Pengaturan</p>
                 <a href="{{ route('logout') }}" class="btn sidebar-btn"
                     onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                     <i class="bx bx-log-out"></i>Log Out
                 </a>

                 <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                     @csrf
                 </form>

             </div>
         @endif
     </div>
 </aside>

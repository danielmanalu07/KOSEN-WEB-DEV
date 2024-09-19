<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="#" class="text-nowrap logo-img">
                <h2 class="text-primary" style="font-weight: 900;">KOMFEND</h2>
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('dashboard.admin') }}" aria-expanded="false">
                        <iconify-icon icon="solar:file-text-line-duotone"></iconify-icon>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li>
                    <span class="sidebar-divider lg"></span>
                </li>
                <li class="nav-small-cap">
                    <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
                    <span class="hide-menu">Karyawan</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/attendance" aria-expanded="false">
                        <iconify-icon icon="solar:user-plus-rounded-line-duotone"></iconify-icon>
                        <span class="hide-menu">Absensi Pegawai</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ url('/admin/users') }}" aria-expanded="false">
                        <iconify-icon icon="mdi:users"></iconify-icon>
                        <span class="hide-menu">Data Pegawai</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/position" aria-expanded="false">
                        <iconify-icon icon="mdi:teacher"></iconify-icon>
                        <span class="hide-menu">Riwayat Absensi</span>
                    </a>
                </li>
                <li>
                    <span class="sidebar-divider lg"></span>
                </li>
            </ul>
        </nav>
    </div>
</aside>

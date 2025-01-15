<?php
use App\Models\NotifikasiVerifikatorModel;
use App\Models\NotifikasiAdminModel;

// Hitung total notifikasi belum dibaca
$totalNotifikasiVFR = NotifikasiVerifikatorModel::where('sudah_dibaca', null)->count();
$totalNotifikasiADM = NotifikasiAdminModel::where('sudah_dibaca', null)->count();
?>

<div class="sidebar">
    <!-- SidebarSearch Form -->
    <div class="form-inline mt-2">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Menu untuk setiap level_kode -->
            @if (Auth::user()->level->level_kode == 'ADM')
                <li class="nav-item">
                    <a href="{{ url('/dashboardAdmin') }}" class="nav-link {{ $activeMenu == 'dashboard' ? 'active' : '' }} ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/profile') }}" class="nav-link {{ $activeMenu == 'profile' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Profile</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/notifAdmin') }}" class="nav-link {{ $activeMenu == 'notifikasi' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bell"></i>
                        <p>Notifikasi</p>
                        @if($totalNotifikasiADM > 0)
                            <span class="badge badge-danger notification-badge">{{ $totalNotifikasiADM }}</span>
                        @endif
                    </a>
                </li>
            @elseif (Auth::user()->level->level_kode == 'MPU')
                <li class="nav-item">
                    <a href="{{ url('/dashboardMPU') }}" class="nav-link {{ $activeMenu == 'dashboard' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/profile') }}" class="nav-link {{ $activeMenu == 'profile' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Profile</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/kategori') }}" class="nav-link {{ $activeMenu == 'kategori' ? 'active' : '' }}">
                        <i class="nav-icon far fa-bookmark"></i>
                        <p>Kategori Barang</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/barang') }}" class="nav-link {{ $activeMenu == 'barang' ? 'active' : '' }}">
                        <i class="nav-icon far fa-list-alt"></i>
                        <p>Data Barang</p>
                    </a>
                </li>
            @elseif (Auth::user()->level->level_kode == 'VFR')
                <li class="nav-item">
                    <a href="{{ url('/dashboardVFR') }}" class="nav-link {{ $activeMenu == 'dashboard' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/profile') }}" class="nav-link {{ $activeMenu == 'profile' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Profile</p>
                    </a>
                </li>
                <li class="nav-item" style="position: relative;">
                    <a href="{{ url('/notifikasi') }}" class="nav-link {{ $activeMenu == 'notifikasi' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bell"></i>
                        <p>Notifikasi</p>
                        @if($totalNotifikasiVFR > 0)
                            <span class="badge badge-danger notification-badge">{{ $totalNotifikasiVFR }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/daftar_permohonan') }}" class="nav-link {{ $activeMenu == 'daftar_permohonan' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>Daftar Permohonan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/daftar_pertanyaan') }}" class="nav-link {{ $activeMenu == 'daftar_pertanyaan' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-question-circle"></i>
                        <p>Daftar Pertanyaan</p>
                    </a>
                </li>
            @elseif (Auth::user()->level->level_kode == 'RPN')
                <li class="nav-item">
                    <a href="{{ url('/dashboardResponden') }}" class="nav-link {{ $activeMenu == 'dashboard' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/profile') }}" class="nav-link {{ $activeMenu == 'profile' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Profile</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/permohonan') }}" class="nav-link {{ $activeMenu == 'permohonan' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>Pengajuan Permohonan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/pertanyaan') }}" class="nav-link {{ $activeMenu == 'pertanyaan' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-comments"></i>
                        <p>Pengajuan Pertanyaan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/riwayat') }}" class="nav-link {{ $activeMenu == 'riwayat' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-scroll"></i>
                        <p>Riwayat</p>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</div>

<style>
    .notification-badge {
        position: absolute;
        top: 50%; /* Vertikal tengah */
        right: 10px; /* Geser ke kiri dari ujung kanan */
        transform: translateY(-50%); /* Perbaiki posisi tengah */
        background-color: #dc3545; /* Warna merah */
        color: white; /* Warna teks */
        padding: 3px 8px; /* Spasi dalam */
        border-radius: 12px; /* Membulatkan sudut */
        font-size: 12px; /* Ukuran font */
        font-weight: bold; /* Tebal */
    }
</style>

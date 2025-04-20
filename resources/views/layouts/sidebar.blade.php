<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    {{-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="{{ asset('storage/' . ($user->foto_profile ?? 'uploads/profile/default-profile.jpg')) }}"
                class="img-circle elevation-2" width="5" height="5" alt="User Image">
        </div>
        <div class="info">
            <a href="#" class="d-block">{{ auth()->user()->nama }}</a>
        </div>
    </div> --}}
    <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
        <div class="image me-2">
            <img src="{{ asset('storage/' . (auth()->user()->foto_profile ?? 'uploads/profile/default-profile.jpg')) }}"
                alt="User Image"
                width="40" height="40"
                class="rounded-circle elevation-2"
                style="object-fit: cover;">
        </div>
        <div class="info">
            <a href="#" class="d-block text-white">{{ auth()->user()->nama }}</a>
        </div>
    </div>
    
    <!-- SidebarSearch Form -->
    <div class="form-inline">
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
            <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link  {{ ($activeMenu == 'dashboard') ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-header">Menu</li>
            <li class="nav-item {{ in_array($activeMenu, ['level', 'user']) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ in_array($activeMenu, ['level', 'user']) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        Data Pengguna
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/level') }}" class="nav-link {{ ($activeMenu == 'level') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Level User</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/user') }}" class="nav-link {{ ($activeMenu == 'user') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Data User</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item {{ in_array($activeMenu, ['kategori', 'barang']) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ in_array($activeMenu, ['kategori', 'barang']) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-boxes"></i>
                    <p>
                        Barang
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/kategori') }}"
                            class="nav-link {{ ($activeMenu == 'kategori') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Kategori Barang</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/barang') }}" class="nav-link {{ ($activeMenu == 'barang') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Data Barang</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li
                class="nav-item {{ in_array($activeMenu, ['penjualan', 'stok', 'penjualan_detail']) ? 'menu-open' : '' }}">
                <a href="#"
                    class="nav-link {{ in_array($activeMenu, ['penjualan', 'stok', 'penjualan_detail']) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-shopping-cart"></i>
                    <p>
                        Transaksi
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/penjualan') }}"
                            class="nav-link {{ ($activeMenu == 'penjualan') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Penjualan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/stok') }}" class="nav-link {{ ($activeMenu == 'stok') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Stok Masuk</p>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a href="{{ url('/penjualan_detail') }}"
                            class="nav-link {{ ($activeMenu == 'penjualan_detail') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Stok Keluar</p>
                        </a>
                    </li> --}}
                </ul>
            </li>
            <li class="nav-item">
                <a href="{{ url('/supplier') }}" class="nav-link  {{ ($activeMenu == 'supplier') ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-box"></i>
                    <p>Data Supplier</p>
                </a>
            </li>
        </ul>
    </nav>
    {{-- <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link  {{ ($activeMenu == 'dashboard') ?
    'active' : '' }} ">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-header">Data Pengguna</li>
            <li class="nav-item">
                <a href="{{ url('/level') }}" class="nav-link {{ ($activeMenu == 'level') ?
    'active' : '' }} ">
                    <i class="nav-icon fas fa-layer-group"></i>
                    <p>Level User</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/user') }}" class="nav-link {{ ($activeMenu == 'user') ?
    'active' : '' }}">
                    <i class="nav-icon far fa-user"></i>
                    <p>Data User</p>
                </a>
            </li>
            <li class="nav-header">Data Barang</li>
            <li class="nav-item">
                <a href="{{ url('/kategori') }}" class="nav-link {{ ($activeMenu ==
    'kategori') ? 'active' : '' }} ">
                    <i class="nav-icon far fa-bookmark"></i>
                    <p>Kategori Barang</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/barang') }}" class="nav-link {{ ($activeMenu ==
    'barang') ? 'active' : '' }} ">
                    <i class="nav-icon far fa-list-alt"></i>
                    <p>Data Barang</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/stok') }}" class="nav-link {{ ($activeMenu == 'stok') ?
    'active' : '' }} ">
                    <i class="nav-icon fas fa-cubes"></i>
                    <p>Stok Barang</p>
                </a>
            </li>
            <li class="nav-header">Data Transaksi</li>
            <li class="nav-item">
                <a href="{{ url('/penjualan') }}" class="nav-link {{ ($activeMenu ==
    'penjualan') ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-cash-register"></i>
                    <p>Transaksi Penjualan</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/penjualan_detail') }}" class="nav-link {{ ($activeMenu ==
    'penjualan_detail') ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-money-bill"></i>
                    <p>Detail Transaksi</p>
                </a>
            </li>
            <li class="nav-header">Data Supplier</li>
            <li class="nav-item">
                <a href="{{ url('/supplier') }}" class="nav-link {{ ($activeMenu == 'supplier') ?
    'active' : '' }} ">
                    <i class="nav-icon fas fa-users"></i>
                    <p>Supplier</p>
                </a>
            </li>
        </ul>
    </nav> --}}
    <!-- /.sidebar-menu -->
</div>
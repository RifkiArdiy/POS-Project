<!-- Preloader -->
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble" src="{{asset('adminlte/dist/img/AdminLTELogo.png')}}" alt="AdminLTELogo" height="60"
        width="60">
</div>
<nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- User Info Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" role="button">
                <img src="{{ asset('storage/' . (auth()->user()->foto_profile ?? 'uploads/profile/default-profile.jpg')) }}" alt="User Image"
                    class="img-circle elevation-2" width="32" height="32">
                <span class="ml-2 d-none d-md-inline">{{ auth()->user()->nama }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">{{ auth()->user()->nama }}</span>
                <div class="dropdown-divider"></div>
                <a href="{{ url('/profile') }}" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> Profil Saya
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ url('/logout') }}" class="dropdown-item dropdown-footer">Logout</a>
            </div>
        </li>
    </ul>

</nav>
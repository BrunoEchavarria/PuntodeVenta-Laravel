<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark d-flex justify-content-between">
    <div class="d-flex">
        <!-- Navbar Brand-->
        <a class="navbar-brand d-flex justify-content-center" href="{{ route('panel') }}">
            <img src="{{asset('assets/img/logo-system2.png')}}" width="145px" alt="logo sistema">
        </a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0 m-3" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        {{-- <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <input name="search" class="form-control" type="text" placeholder="Buscar ...." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
            </div>
        </form> --}}
        <!-- Navbar-->
    </div>
    <div>
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="{{ route('profile.index') }}">Configuraciones</a></li>
                    {{-- <li><a class="dropdown-item" href="#!">Registro de actividad</a></li> --}}
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="{{ route('logout') }}">Cerrar sesión</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
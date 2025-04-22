<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta charset="UTF-8">

    <title>Manejo y Seguimiento de Expedientes</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link rel="icon" href="../assets/img/kaiadmin/favicon.ico" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="{{ asset('js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["{{ asset('css/fonts.min.css') }}"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/kaiadmin.min.css') }}" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <!-- <link rel="stylesheet" href="../assets/css/demo.css" /> -->



<!-- Incluir el CSS de Choices.js -->
<link href="http://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" rel="stylesheet" />
<!-- Incluir el JS de Choices.js -->
<script src="http://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>



  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="white">
            <a href="#" class="logo">
              <img src="{{ asset('img/logo.jpg') }}" alt="navbar brand" class="navbar-brand" height="40" />
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div><!--
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>-->
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#dashboard" >
                  <i class="fas fa-home"></i>
                  <p>INVENTARIO</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="dashboard">
                  <ul class="nav nav-collapse">
@auth
    @php
        $perfil = Auth::user()->perfil->descri_perfil;
    @endphp
    @if(in_array($perfil, ['Admin', 'Inventario']))

                    <li>
                      <a href="{{ route('inventario') }}">
                        <span class="sub-item">Registro de Inventario</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('seginventario') }}">
                        <span class="sub-item">Seguimiento</span>
                      </a>
                    </li>
    @endif
@endauth
                    
                  </ul>
                </div>
              </li>
<!--
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Accesos</h4>
              </li>
-->

@auth
    @if(in_array($perfil, ['Admin']))
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#accesos">
                  <i class="fas fa-layer-group"></i>
                  <p>ACCESOS</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="accesos">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="{{ route('personal.index') }}">
                        <span class="sub-item">Personal</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('usuarios.index') }}">
                        <span class="sub-item">Usuarios</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('perfilusuario.index') }}">
                        <span class="sub-item">Asigna Perfil a Usuarios</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
    @endif
@endauth


            </ul>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="white">
              <!--<a href="../index.html" class="logo">
                <img src="{{ asset('img/logo.jpg') }}" alt="navbar brand" class="navbar-brand" />
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>-->
            </div>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
          <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom" >
            <div class="container-fluid">
              <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex" >
              MANEJO Y SEGUIMIENTO DE EXPEDIENTES
              <!--
                <div class="input-group">
                  <div class="input-group-prepend">
                    <button type="submit" class="btn btn-search pe-1">
                      <i class="fa fa-search search-icon"></i>
                    </button>
                  </div>
                  <input type="text" placeholder="Search ..." class="form-control" />
                </div>
              -->  
              </nav>

              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false" >
                    <div class="avatar-sm">
                      <img src="{{ asset('img/user.png') }}" alt="..." class="avatar-img rounded-circle" />
                    </div>
                    <span class="profile-username">
                      <!--<span class="op-7">Hi,</span>-->
                      <!-- <span class="fw-bold">Bienvenido, {{ Auth::user()->usuario }}  {{ Auth::user()->id_usuario }}  {{ Auth::user()->id_personal }}!</span>-->
                      <span class="fw-bold">{{ Auth::user()->personal->apellido_paterno }} {{ Auth::user()->personal->apellido_materno }} <br> {{ Auth::user()->personal->nombres }}</span>
                      

                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                  <!--
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">
                            <img
                              src="../assets/img/profile.jpg"
                              alt="image profile"
                              class="avatar-img rounded"
                            />
                          </div>
                          <div class="u-text">
                            <h4>Hizrian</h4>
                            <p class="text-muted">hello@example.com</p>
                            <a
                              href="profile.html"
                              class="btn btn-xs btn-secondary btn-sm"
                              >View Profile</a
                            >
                          </div>
                        </div>
                      </li>
                      -->
                      <li>
                      	<!--
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">My Profile</a>
                        <a class="dropdown-item" href="#">My Balance</a>
                        <a class="dropdown-item" href="#">Inbox</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Account Setting</a>
                        <div class="dropdown-divider"></div>
                        -->
                          <form method="POST" action="{{ route('usuario.logout') }}">
			      @csrf
			      <button type="submit" class="dropdown-item" style="background: none; border: none; padding: 5px; margin: 0;">
			          <i class="fas fa-sign-out-alt me-2"></i> CERRAR LA SESION
			      </button>
                           </form>
                        <!--<a class="dropdown-item" href="{{ route('usuario.logout') }}"><i class="fas fa-sign-out-alt me-2"></i>CERRAR LA SESION</a>-->
                      </li>
                    </div>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
          <!-- End Navbar -->
        </div>

        <div class="container" >
          <div class="page-inner">
            <!--
            <div class="page-header">
              <h3 class="fw-bold mb-3">Forms</h3>
              <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                  <a href="#">
                    <i class="icon-home"></i>
                  </a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="#">Forms</a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="#">Basic Form</a>
                </li>
              </ul>
            </div>
            -->
            <!--@auth
               <p>Rol: {{ auth()->user()->perfil->id_perfil }} {{ auth()->user()->perfil->descri_perfil ?? 'Sin rol asignado' }}</p>
            @endauth-->
            @yield('content')
            

  
            
          </div>
        </div>

        <footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <nav class="pull-left">
              <ul class="nav">
                <li class="nav-item">
                    Ministerio P&uacute;blico
                </li>
              </ul>
            </nav>
            <div class="copyright">
              &copy; 2025
            </div>
            <!--<div>
              Distributed by
              <a target="_blank" href="https://themewagon.com/">ThemeWagon</a>.
            </div>-->
          </div>
        </footer>
      </div>

    </div>
    
    

    <!--   Core JS Files   -->
    <script src="{{ asset('js/core/popper.min.js') }}"></script>
    <script src="{{ asset('js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/core/jquery-3.7.1.min.js') }}"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

    <!-- Chart JS 
    <script src="../assets/js/plugin/chart.js/chart.min.js"></script>-->

    <!-- jQuery Sparkline 
    <script src="../assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>-->

    <!-- Chart Circle 
    <script src="../assets/js/plugin/chart-circle/circles.min.js"></script>-->

    <!-- Datatables -->
    <script src="{{ asset('js/plugin/datatables/datatables.min.js') }}"></script>

    <!-- Bootstrap Notify 
    <script src="../assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>-->

    <!-- jQuery Vector Maps 
    <script src="../assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="../assets/js/plugin/jsvectormap/world.js"></script>-->

    <!-- Google Maps Plugin 
    <script src="../assets/js/plugin/gmaps/gmaps.js"></script>-->

    <!-- Sweet Alert 
    <script src="../assets/js/plugin/sweetalert/sweetalert.min.js"></script>-->

    <!-- Kaiadmin JS -->
    <script src="{{ asset('js/kaiadmin.min.js') }}"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! 
    <script src="../assets/js/setting-demo2.js"></script>-->

    @stack('scripts')

  </body>
</html>

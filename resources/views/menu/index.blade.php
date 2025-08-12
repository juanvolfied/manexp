<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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




<!-- CSS de Selectize -->
<link rel="stylesheet" href="{{ asset('css/selectize.css') }}">






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
@auth
    @php
        $perfil = optional(Auth::user()->perfil)->descri_perfil;        
    @endphp
    @php
        $menuinventario = in_array($perfil, ['Admin', 'Inventario','Archivo']);
        $puedeVerGrafico = in_array($perfil, ['Admin','Archivo']);
    @endphp
    @if ($menuinventario)
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#dashboard" >
                  <i class="fas fa-home"></i>
                  <p>INVENTARIO</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="dashboard">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="{{ route('inventario') }}">
                        <span class="sub-item">Registro de Inventario</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('inventariov2') }}">
                        <span class="sub-item">Registro de Inventario forma 2</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('seginventario') }}">
                        <span class="sub-item">Seguimiento</span>
                      </a>
                    </li>
      @if($puedeVerGrafico)
                    <li>
                      <a href="{{ route('grafico') }}">
                        <span class="sub-item">Gr&aacute;fico de Avance</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('graficopie') }}">
                        <span class="sub-item">Gr&aacute;fico por Dependencia</span>
                      </a>
                    </li>
      @endif
                    <li>
                      <a href="{{ route('expediente.seguimiento') }}">
                        <span class="sub-item">B&uacute;squeda y Seguimiento de Carpetas Fiscales</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
    @endif <!--menuinventario-->
@endauth
<!--
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Accesos</h4>
              </li>
-->

@auth
    @php
        $menuexpedientes = in_array($perfil, ['Admin','Archivo','Despacho']);
        $puedeRegistrar = in_array($perfil, ['Admin','Despacho']);
        $puedeGenerarGuia = in_array($perfil, ['Admin','Despacho']);
        $puedeRecepcionar = in_array($perfil, ['Admin','Archivo']);
    @endphp
    @if ($menuexpedientes)
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#movimientos">
                  <i class="fas fa-layer-group"></i>
                  <p>EXPEDIENTES</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="movimientos">
                  <ul class="nav nav-collapse">
      @if($puedeRegistrar)
                    <li>
                      <a href="{{ route('expediente.index') }}">
                        <span class="sub-item">Registro de Expedientes</span>
                      </a>
                    </li>
      @endif
      @if($puedeGenerarGuia)
                    <li>
                      <a href="{{ route('internamiento.index') }}">
                        <span class="sub-item">Guia de Internamiento</span>
                      </a>
                    </li>
      @endif
      @if($puedeRecepcionar)
                    <li>
                      <a href="{{ route('internamiento.recepcion') }}">
                        <span class="sub-item">Recepción Guia de Internamiento</span>
                      </a>
                    </li>
      @endif
                    <li>
                      <a href="{{ route('expediente.seguimiento') }}">
                        <span class="sub-item">B&uacute;squeda y Seguimiento de Carpetas Fiscales</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
    @endif <!--menuexpedientes-->


    


    @php
        $menumesapartes = in_array($perfil, ['Admin','mesapartes']);
    @endphp
    @if ($menumesapartes)
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#mesapartes">
                  <i class="fas fa-desktop"></i>
                  <p>MESA DE PARTES</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="mesapartes">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="{{ route('mesapartes.index') }}">
                        <span class="sub-item">Registro de ESCRITOS</span>
                      </a>
                    </li>



                    <li>
                      <a data-bs-toggle="collapse" href="#subnav1">
                        <span class="sub-item">CONSULTA ESCRITOS</span>
                        <span class="caret"></span>
                      </a>
                      <div class="collapse" id="subnav1">
                        <ul class="nav nav-collapse subnav">

                          <li>
                            <a href="{{ route('mesapartes.consultaintervalo') }}">
                              <span class="sub-item">Por intervalo de fechas</span>
                            </a>
                          </li>
                          <li>
                            <a href="{{ route('mesapartes.consulta') }}">
                              <span class="sub-item">Por fecha y fiscal</span>
                            </a>
                          </li>

                        </ul>
                      </div>
                    </li>
<!--
                    <li>
                      <a href="{{ route('mesapartes.consultaintervalo') }}">
                        <span class="sub-item">Consulta de escritos por intervalo de fechas</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('mesapartes.consulta') }}">
                        <span class="sub-item">Consulta de escritos por fecha y fiscal</span>
                      </a>
                    </li>-->

                    <li>
                      <a href="{{ route('mesapartes.showupload') }}">
                        <span class="sub-item">Anexar archivos digitales PDF</span>
                      </a>
                    </li>


                  </ul>
                </div>
              </li>
    @endif <!--menumesapartes-->


    @php
        $menuescritos = in_array($perfil, ['Admin','Despacho']);
    @endphp
    @if ($menuescritos)
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#escritos">
                  <i class="fas fa-file"></i>
                  <p>ESCRITOS</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="escritos">
                  <ul class="nav nav-collapse">

                    <li>
                      <a href="{{ route('mesapartes.consultaescritos') }}">
                        <span class="sub-item">Consulta de escritos</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
    @endif <!--menuescritos-->





    
    @if(in_array($perfil, ['Admin']))
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#mantenimiento">
                  <i class="fas fa-wrench"></i>
                  <p>MANTENIMIENTO</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="mantenimiento">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="{{ route('deppolicial.index') }}">
                        <span class="sub-item">Dependencias Policiales</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('reactivainventario') }}">
                        <span class="sub-item">Reactiva Movimiento de Inventario</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('verdependencias') }}">
                        <span class="sub-item">Selecciona Dependencias para Inventarios</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('backup') }}">
                        <span class="sub-item">Backup</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#accesos">
                  <i class="fas fa-key"></i>
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

              <li class="nav-item">
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <i class="fas fa-sign-out-alt"></i>
                  <p>CERRAR LA SESION</p>
                </a>
                <form id="logout-form" action="{{ route('usuario.logout') }}" method="POST" style="display: none;">
                @csrf
                </form>
              </li>

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
@auth
                      <span class="fw-bold">{{ optional(Auth::user()->personal)->apellido_paterno }} {{ optional(Auth::user()->personal)->apellido_materno }} <br> {{ optional(Auth::user()->personal)->nombres }}</span>
@endauth
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
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>CERRAR LA SESION
                        </a>
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

<script src="{{ asset('js/imask.js') }}"></script>


<!-- JS de Selectize -->
<script src="{{ asset('js/plugin/selectize/selectize.js') }}"></script>


    <!-- jQuery Scrollbar -->
    <script src="{{ asset('js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

    <!-- Chart JS -->
    <script src="{{ asset('js/plugin/chart.js/chart.min.js') }}"></script>

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


@yield('scripts')

<style>
/* Estilos personalizados para tooltips de Bootstrap */
.tooltip-inner {
    background-color: #004085 !important; /* Fondo */
    color: #fff !important;               /* Texto */
    font-size: 14px !important;           /* Tamaño de letra */
    padding: 10px 15px;                   /* Espaciado */
    border-radius: 8px;                   /* Bordes redondeados */
    max-width: 300px;                     /* Ancho máximo */
}

/* Flechita del tooltip */
.bs-tooltip-auto[data-popper-placement^=top] .tooltip-arrow::before,
.bs-tooltip-top .tooltip-arrow::before {
    border-top-color: #004085 !important;
}
</style>
  </body>
</html>

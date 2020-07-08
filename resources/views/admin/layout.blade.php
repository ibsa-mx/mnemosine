<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="generator" content="IBSA">
    <meta name="keyword" content="mnemosine, museos">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Mnemosine') }} @yield('title')</title>
@stack('metadata')
    <!-- Styles -->
    <link href="{{ asset('admin/css/general.css?v=20191218') }}" rel="stylesheet">
    <link href="{{ asset('admin/node_modules/@coreui/icons/css/coreui-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/node_modules/flag-icon-css/css/flag-icon.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendors/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/node_modules/simple-line-icons/css/simple-line-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendors/pace-progress/css/pace.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendors/DataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendors/select2/dist/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{ asset('admin/vendors/select2/dist/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{ asset('admin/vendors/toastr/build/toastr.min.css')}}" rel="stylesheet">
    <link href="{{ asset('admin/vendors/bootstrap-fileinput/css/fileinput.min.css')}}" media="all" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('admin/vendors/daterangepicker/css/daterangepicker.css')}}" rel="stylesheet">

    <link href="{{ asset('admin/vendors/bootstrap-toggle/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
@stack('after_all_styles')

    <script src="{{ asset('admin/node_modules/jquery/dist/jquery.min.js') }}"></script>
@stack('after_jquery')
    <script src="{{ asset('admin/vendors/DataTables/datatables.min.js') }}"> </script>
    <script src="{{ asset('admin/vendors/DataTables/plugins/input.js') }}"> </script>
    <script src="{{asset('admin/vendors/daterangepicker/js/moment.min.js')}}"></script>
    <script src="{{asset('admin/vendors/daterangepicker/js/daterangepicker.min.js')}}"></script>
    <script src="{{ asset('admin/vendors/toastr/build/toastr.min.js') }}"> </script>
@stack('script_head')
@yield('assets')
  </head>
  <body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show{!! (isset($_COOKIE['sidebar_status']) && $_COOKIE['sidebar_status'] == 'minimized') ? ' brand-minimized sidebar-minimized' : '' !!}">
    <header class="app-header navbar" style="background-color: #2f353a !important; border-bottom: 1px solid #24282c;" >
      <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand" href="{{ route('home') }}">
        <img class="navbar-brand-full" src="{{ asset('admin/img/brand/Franz-mayer.png') }}" width="52" height="55" alt="Logo"> <span class="navbar-brand-full text-light" style="font-size: 1.25rem;">Mnemosine</span>
        <img class="navbar-brand-minimized" src="{{ asset('admin/img/brand/Franz-mayer.png') }}" width="52" height="55" alt="Logo">
      </a>
      <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
        <span class="navbar-toggler-icon"></span>
      </button>
      <ul class="nav navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
              <img class="img-avatar" src="{{ asset('admin/img/avatar/mujer1.png') }}" alt="">
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-header text-center">
                <strong>{{ auth()->user()->name }}</strong>
                <span class="badge badge-dark">{{ auth()->user()->roles->first()->name }}</span>
            </div>
            <a class="dropdown-item" href="{{ route('perfil.changePassword') }}">
                <i class="icon-lock text-dark"></i> Cambiar contraseña
            </a>
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); $('#logout-form').submit();">
                <i class="icon-logout text-dark"></i> {{ __('Cerrar sesión') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
          </div>
        </li>
      </ul>
    </header>
    <div class="app-body">
      <div class="sidebar">
        <nav class="sidebar-nav">
          <ul class="nav">
            <li class="nav-item">
              <a class="nav-link" href="{{ route('home') }}">
                <i class="nav-icon icon-speedometer"></i> Inicio
              </a>
            </li>
            @can('ver_consultas')
            <li class="nav-item">
              <a class="nav-link" href="{{ route('consultas') }}">
                <i class="nav-icon icon-eyeglass"></i> Consultas
              </a>
            </li>
            @endcan
            @can('ver_inventario')
            <li class="nav-item">
              <a class="nav-link" href="{{ route('inventario.index') }}">
                <i class="nav-icon fa fa-boxes"></i> Inventario
              </a>
            </li>
            @endcan
            @can('ver_investigacion')
            <li class="nav-item">
              <a class="nav-link" href="{{ route('investigacion.index') }}">
                <i class="nav-icon fa fa-microscope"></i> Investigación
              </a>
            </li>
            @endcan
            @can('ver_restauracion')
            <li class="nav-item">
              <a class="nav-link" href="{{ route('restauracion.index') }}">
                <i class="nav-icon fa fa-history"></i> Restauración
              </a>
            </li>
            @endcan
            @can('ver_movimientos')
            <li class="nav-item nav-dropdown">
              <a class="nav-link nav-dropdown-toggle" href="#">
                <i class="nav-icon fa fa-truck"></i> Movimientos
              </a>
              <ul class="nav-dropdown-items">
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('movimientos.index') }}">
                    <i class="nav-icon fa fa-cogs"></i> Gestionar</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('movimientos.search.index') }}">
                    <i class="nav-icon fa fa-search"></i> Buscar</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('instituciones.index') }}">
                    <i class="nav-icon fas fa-university"></i>Instituciones</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('contactos.index') }}">
                    <i class="nav-icon fas fa-users"></i>Contactos</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('exposiciones.index') }}">
                    <i class="nav-icon fas fa-images"></i>Exposiciones</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('sedes.index') }}">
                    <i class="nav-icon fas fa-warehouse"></i>Sedes</a>
                </li>
              </ul>
            </li>
            @endcan
            @can('ver_reportes')
            <li class="nav-item">
              <a class="nav-link" href="{{ route('reportes.index') }}">
                <i class="nav-icon icon-chart"></i> Reportes
              </a>
            </li>
            @endcan
            @canany(['ver_usuarios', 'ver_roles', 'ver_catalogos', 'ver_configuraciones'])
            <li class="nav-title">Administración</li>
            @endcan
            @canany(['ver_usuarios', 'ver_roles'])
            <li class="nav-item nav-dropdown">
              <a class="nav-link nav-dropdown-toggle" href="#">
                <i class="nav-icon icon-user"></i> Usuarios
              </a>
              <ul class="nav-dropdown-items">
                @can('ver_usuarios')
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('usuarios.index') }}">
                    <i class="nav-icon fa fa-cogs"></i> Gestionar</a>
                </li>
                @endcan
                @can('ver_roles')
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('roles.index') }}">
                    <i class="nav-icon icon-people"></i> Roles de usuario</a>
                </li>
                @endcan
              </ul>
            </li>
            @endcanany
            @can('ver_catalogos')
            <li class="nav-item nav-dropdown">
              <a class="nav-link nav-dropdown-toggle" href="#">
                <i class="nav-icon icon-notebook"></i> Catálogos
              </a>
              <ul class="nav-dropdown-items">
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('catalogos.index')}}">
                    <i class="nav-icon fa fa-cogs"></i> Gestionar</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('generos.index') }}">
                    <i class="nav-icon icon-layers"></i> Géneros</a>
                </li>
              </ul>
            </li>
            @endcan

            {{-- @can('ver_configuraciones')
            <li class="nav-item nav-dropdown">
              <a class="nav-link nav-dropdown-toggle" href="#">
                <i class="nav-icon icon-settings"></i> Configuraciones
              </a>
              <ul class="nav-dropdown-items">
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('campos.index') }}">
                    <i class="nav-icon icon-note"></i> Modificar campos</a>
                </li>
              </ul>
            </li>
            @endcan --}}

          </ul>
        </nav>
        <button class="sidebar-minimizer brand-minimizer" type="button"></button>
      </div>
      <main class="main">
          <ol class="breadcrumb">
              @yield('breadcrumb')
              <li class="breadcrumb-menu d-md-down-none">
                  <div class="h4 m-0 p-0" style="line-height: 1;">
                      <span class="badge badge-primary m-0">{{ config('app.collection_name', 'Colección Franz Mayer') }}</span>
                  </div>
              </li>
          </ol>
          <div class="container-fluid">
              <div class="animated fadeIn"></div>
              @yield('content')
          </div>
      </main>
    </div>
    <footer class="app-footer mt-3">
      <div class="col text-center">
          Mnemosine © <a href="http://ibsaweb.com/" target="_blank">IBSA</a> {{ date("Y") }}
      </div>
    </footer>
    <!-- Scripts -->
    <script src="{{ asset('admin/node_modules/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('admin/node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin/node_modules/pace-progress/pace.min.js') }}"></script>
    <script src="{{ asset('admin/node_modules/perfect-scrollbar/dist/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('admin/node_modules/@coreui/coreui/dist/js/coreui.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/select2/dist/js/select2.min.js') }}"> </script>
    <script src="{{ asset('admin/vendors/select2/dist/js/i18n/es.js') }}"> </script>

    <script src="{{asset('admin/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
    <script src="{{asset('admin/vendors/bootstrap-fileinput/themes/fas/theme.min.js')}}"></script>
    <script src="{{asset('admin/vendors/bootstrap-fileinput/js/locales/es.js')}}"></script>
    <script src="{{asset('admin/vendors/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
    <script src="{{asset('admin/vendors/js.cookie.js')}}"></script>
    <script src="{{asset('admin/js/general.js?v=20200320')}}"></script>
@stack('after_all_scripts')

@php
    //list($modulo, $accion) = explode(".", Route::currentRouteName());
@endphp
  </body>
</html>

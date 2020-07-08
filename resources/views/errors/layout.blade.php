<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="IBSA">
    <meta name="keyword" content="mnemosine, museos">
    <title>{{ config('app.name', 'Mnemosine') }} - Error @yield('error')</title>

    <!-- Icons-->
    <link href="{{ asset('admin/vendors/font-awesome/css/all.min.css') }}" rel="stylesheet">
    <!-- Main styles for this application-->
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">

    <!-- CoreUI and necessary plugins-->
    <script src="{{ asset('admin/node_modules/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/node_modules/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('admin/node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin/node_modules/@coreui/coreui/dist/js/coreui.min.js') }}"></script>
</head>
<body class="app flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 shadow rounded p-3">
                <div class="clearfix">
                    <p class="text-center">
                        <img class="navbar-brand-minimized" src="{{ asset('admin/images/error.png') }}" width="130" height="127" alt="">
                    </p>
                    <h1 class="float-left display-3 mr-4 text-black-50">@yield('error')</h1>
                    <p class="h4" class="pt-3">@yield('titulo')</p>
                    <p class="text-muted">@yield('descripcion')</p>
                </div>
                <div class="text-center">
                    {!! Form::open(['method' => 'get', 'route' => 'consultas.search']) !!}
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Buscar pieza</span>
                        </div>
                        <input type="text" name="keywords" class="form-control" placeholder="Palabras clave" required>
                        <div class="input-group-append">
                            <input type="submit" class="btn btn-primary" value="Ir"/>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    <a href="{{ route('home') }}" class="btn btn-primary">Ir a la página de inicio</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

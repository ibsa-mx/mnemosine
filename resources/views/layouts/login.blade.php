<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="eScire S.A.">
    <meta name="keyword" content="mnemosine, museos">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Mnemosine') }} - Validación</title>

    <link href="{{ asset('admin/node_modules/@coreui/icons/css/coreui-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/node_modules/flag-icon-css/css/flag-icon.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/node_modules/simple-line-icons/css/simple-line-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendors/pace-progress/css/pace.min.css') }}" rel="stylesheet">

    <script src="{{ asset('admin/node_modules/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/node_modules/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('admin/node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin/node_modules/pace-progress/pace.min.js') }}"></script>
    <script src="{{ asset('admin/node_modules/perfect-scrollbar/dist/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('admin/node_modules/@coreui/coreui/dist/js/coreui.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/backstretch/jquery.backstretch.min.js') }}"></script>
</head>
<body class="app flex-row align-items-center">
    @yield('content')

    <script type="text/javascript">
    $.backstretch([
        "{{ asset('admin/images/background-1.jpg') }}",
        "{{ asset('admin/images/background-2.jpg') }}",
        "{{ asset('admin/images/background-3.jpg') }}",
        "{{ asset('admin/images/background-4.jpg') }}",
        "{{ asset('admin/images/background-5.jpg') }}"
    ], {
        fade: 1000,
        duration: 7000,
        preload: 1
    });
    </script>
</body>
</html>

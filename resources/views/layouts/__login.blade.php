<!DOCTYPE html>
<html>
<head>
	<title>Mnemosine</title>

	<!-- Main styles for this application-->
	<link rel="stylesheet" type="text/css" href= "{{ asset('admin/css/login.css') }}">
	<!-- Icons-->

    <!-- Main styles for this application-->

</head>
<body class=" is-preload">
	<header id="header">
        <div class="">
          <h2>Bienvenido a Mnemosine</h2>
        </div>
      </header>
	  @yield('content')
	<script src="{{ asset('admin/node_modules/jquery/dist/jquery.min.js') }}"></script>
	<script src="{{ asset('admin/node_modules/popper.js/dist/umd/popper.min.js') }}"></script>
	<script src="{{ asset('admin/node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('admin/node_modules/pace-progress/pace.min.js') }}"></script>

	<script src="{{ asset('admin/js/login.js') }}"></script>
</body>
</html>
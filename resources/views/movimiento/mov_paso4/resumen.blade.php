@extends('admin.layout')
@section('title', 'Movimientos - Resumen')
@section('breadcrumb')
	<li class="breadcrumb-item">
		<a href="{{route('movimientos.index')}}">Movimientos</a>
	</li>
	<li class="breadcrumb-item">
		Movimientos - Resumen
	</li>
@endsection

@section('content')
@include('movimiento.mov_paso3._form')
@endsection

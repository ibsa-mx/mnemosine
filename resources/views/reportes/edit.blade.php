@extends('admin.layout')

@section('title', '- Reportes, Editar reporte')

@section('breadcrumb')
	<li class="breadcrumb-item">
		<a href="{{route('reportes.index')}}">Reportes</a>
	</li>
	<li class="breadcrumb-item">
		Editando reporte
	</li>
@endsection

@section('content')
	{!! Form::model($reporte, ['method' => 'PUT', 'route' => ['reportes.update',  $reporte->id ], 'files' => true, 'id' => 'frmReporte']) !!}
		@include('reportes._form')
	{!! Form::close() !!}
@endsection

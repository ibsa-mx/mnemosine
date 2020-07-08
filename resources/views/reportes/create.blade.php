@extends('admin.layout')

@section('title', '- Nuevo reporte')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('reportes.index')}}">Reportes</a>
    </li>
    <li class="breadcrumb-item active">
        Nuevo reporte
    </li>
@endsection

@section('content')
    {!! Form::open(['route' => 'reportes.store', 'files' => true, 'id' => 'frmReporte']) !!}
        @include('reportes._form')
    {!! Form::close() !!}
@endsection

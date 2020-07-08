@extends('admin.layout')

@section('title', '- Institución, editando institución')

@section('assets')
    <script src="{{ asset('admin/js/movimientos.js') }}"></script>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('instituciones.index')}}">Instituciones</a>
    </li>
    <li class="breadcrumb-item active">
        Editando institución
    </li>
@endsection

@section('content')
<div class="card card-accent-info">
    <h5 class="card-header mb-3">Institución</h5>
    {!! Form::model($institucion, ['method' => 'PUT', 'route' => ['instituciones.update',  $institucion->id ], 'files' => true, 'id' => 'frmInstitucion']) !!}
        @include('movimiento.institucion._form')

        <div class="text-center mb-3">
            <a href="{{ route('instituciones.index') }}" class="btn btn-secondary">Cancelar</a>
            {!! Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}
</div>
@endsection

@extends('admin.layout')

@section('title', '- Géneros')

@section('breadcrumb')
    <li class="breadcrumb-item active">
        Géneros
    </li>
@endsection

@section('content')
    @can('agregar_catalogos')
        <div class="row flex-row-reverse m-2">
            <a href="{{ route('generos.create') }}" class="btn btn-outline-primary btn-sm pull-right" rel="tooltip" title="Nuevo género"><i class="fas fa-plus"></i> Agregar</a>
        </div>
    @endcan

    <table id="data-table" class="table table-striped table-bordered table-hover dt-responsive" style="width:100%">
        <thead>
            <th>Título</th>
            <th>Descripción</th>
            @canany(['ver_catalogos', 'editar_catalogos', 'eliminar_catalogos'])
                <th>Acciones</th>
            @endcanany
        </thead>
        <tbody>
            @foreach($genders as $gender)
                <tr>
                    <td>{{$gender->title}}</td>
                    <td>{{Str::limit($gender->description, 170)}}</td>
                    <td class="text-center">
                        @can('ver_catalogos')
                            <a href="{{ route("subgeneros.show", $gender->id) }}" class="btn btn-sm btn-outline-primary" data-toggle="tooltip" title="Ver subgéneros"><i class="far fa-eye"></i></a>
                        @endcan
                        @can('editar_catalogos')
                            <a href="{{ route('generos.edit', ['genero' => $gender->id])  }}" class="btn btn-sm btn-outline-primary" rel="tooltip" title="Editar género"><i class="far fa-edit"></i></a>
                        @endcan
                        @can('eliminar_catalogos')
                            {!! Form::open( ['method' => 'delete', 'url' => route('generos.destroy', ['genero' => $gender->id]), 'style' => 'display: inline', 'onSubmit' => 'return confirm("¿Realmente desea eliminar el elemento?")']) !!}
                            <button type="submit" class="btn-delete btn btn-sm btn-outline-danger" rel="tooltip" title="Eliminar género">
                                <i class="far fa-trash-alt"></i>
                            </button>
                            {!! Form::close() !!}
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@include('flash-toastr::message')
@endsection

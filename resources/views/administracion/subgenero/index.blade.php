@extends('admin.layout')

@section('title', '- Administración-Subgénero')

@section('breadcrumb')
    <li class="breadcrumb-item active">
       <a href="/generos">Géneros</a>
    </li>
    <li class="breadcrumb-item">
       Subgéneros de <em>"{{$gender->title}}"</em>
    </li>
@endsection

@section('content')
    @can('agregar_catalogos')
        <div class="row flex-row-reverse m-2">
            <a href="{{ route('subgeneros.create', ['id' => $gender->id]) }}" class="btn btn-outline-primary btn-sm" rel="tooltip" title=""><i class="fas fa-plus"></i> Agregar</a>
        </div>
    @endcan

    <table id="data-table" class="table table-striped table-bordered table-hover dt-responsive" style="width:100%">
        <thead>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </thead>
        <tbody>
            @foreach($subgenders as $subgender)
                <tr>
                    <td>{{$subgender->title}}</td>
                    <td>{{Str::limit($subgender->description, 170)}}</td>
                    <td class="text-center">
                        @can('editar_catalogos')
                            <a href="{{ route('subgeneros.edit', ['subgenero' => $subgender->id])  }}" class="btn btn-sm btn-outline-primary" rel="tooltip" title="Editar subgénero"><i class="far fa-edit"></i></a>
                        @endcan
                        @can('eliminar_catalogos')
                            {!! Form::open( ['method' => 'delete', 'url' => route('subgeneros.destroy', ['subgenero' => $subgender->id]), 'style' => 'display: inline', 'onSubmit' => 'return confirm("¿Realmente desea eliminar el elemento?")']) !!}
                            <button type="submit" class="btn-delete btn btn-sm btn-outline-danger" rel="tooltip" title="Eliminar subgénero">
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

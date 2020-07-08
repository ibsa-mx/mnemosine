@extends('admin.layout')

@section('title', '- Usuarios')

@section('breadcrumb')
    <li class="breadcrumb-item">
        Usuarios
    </li>
    @if ($request->input('page') > 0)
    <li class="breadcrumb-item active">
        Página {{ $request->input('page') }}
    </li>
    @endif
@endsection

@section('content')
    @can('agregar_usuarios')
    <div class="page-action text-right mb-3">
        <a href="{{ route('usuarios.create') }}" class="btn btn-outline-primary btn-sm pull-right" rel="tooltip" title="Nuevo usuario"><i class="fas fa-plus"></i> Agregar</a>
    </div>
    @endcan
    <div class="result-set">
        <table class="table table-bordered table-striped table-hover" id="data-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Corre electrónico</th>
                    <th>Rol</th>
                    <th>Creado</th>
                    @canany(['editar_usuarios', 'eliminar_usuarios'])
                    <th class="text-center">Acciones</th>
                    @endcanany
                </tr>
            </thead>
            <tbody>
            @foreach($result as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>
                        @foreach ($item->roles as $role)
                            <span class="badge badge-primary">{{$role->name}}</span>
                        @endforeach
                    </td>
                    <td>
                        <span rel="tooltip" title="{{ $item->created_at->locale('es_MX')->isoFormat('LLLL') }}">{{ $item->created_at->diffForHumans() }}</span>
                    </td>
                    @canany(['editar_usuarios', 'eliminar_usuarios'])
                    <td class="text-center">
                        @include('shared._actions', [
                            'entity' => 'usuarios',
                            'id' => $item->id,
                            'singular' => 'usuario'
                        ])
                    </td>
                    @endcanany
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @include('flash-toastr::message')
@endsection

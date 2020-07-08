@extends('admin.layout')

@section('title', '- Instituciones')

@section('breadcrumb')
    <li class="breadcrumb-item">
        Instituciones
    </li>
@endsection

@section('content')
    @can('agregar_movimientos')
    <div class="page-action text-right mb-3">
        <a href="{{ route('instituciones.create') }}" class="btn btn-outline-primary btn-sm pull-right" rel="tooltip" title="Nueva pieza"><i class="fas fa-plus"></i> Agregar</a>
    </div>
    @endcan

   <table id="data-table" class="table table-striped table-bordered dt-responsive" style="width:100%">
       <thead>
           <tr>
               <th>Nombre</th>
               <th>Dirección</th>
               <th>Ciudad</th>
               <th>Teléfono</th>
               @canany(['editar_movimientos', 'eliminar_movimientos'])
                   <th>Acciones</th>
               @endcanany
           </tr>
       </thead>
       <tbody>
       	@foreach($institucion as $item)
          <tr>
              <td>{{$item->name}}</td>
              <td>{{$item->address}}</td>
              <td>{{$item->city}}</td>
              <td>{{$item->phone}}</td>
            @canany(['editar_movimientos', 'eliminar_movimientos'])
            <td class="text-center">
            	@include('shared._actions', [
                    'entity' => 'instituciones',
                    'parentModule' => 'movimientos',
                    'id' => $item->id,
                    'singular' => 'institucion',
                    'search' => '0'
                ])
            </td>
			@endcanany
          </tr>
        @endforeach
      </tbody>
    </table>
@include('flash-toastr::message')
@endsection

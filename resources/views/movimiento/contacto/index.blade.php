@extends('admin.layout')
@section('title', '- Contactos')

@section('breadcrumb')
    <li class="breadcrumb-item">
        Contactos
    </li>
@endsection

@section('content')
    @can('agregar_movimientos')
      <div class="page-action text-right mb-3">
          <a href="{{ route('contactos.create') }}" class="btn btn-outline-primary btn-sm pull-right" rel="tooltip" title="Nuevo Contacto"><i class="fas fa-plus"></i> Agregar</a>
      </div>
    @endcan

    <table id="data-table" class="table table-striped table-bordered dt-responsive" style="width:100%">
       <thead>
           <tr>
               <th>Nombre</th>
               <th>Teléfono</th>
               <th>Correo</th>
               <th>Institución</th>
               @canany(['editar_movimientos', 'eliminar_movimientos'])
                   <th>Acciones</th>
               @endcanany
           </tr>
       </thead>
       <tbody>
        @foreach($contactos as $item)
          <tr>
              <td>{{$item->name}} {{$item->last_name}}</td>
              <td>{{$item->phone}}</td>
              <td>{{$item->email}}</td>
              <td>
                @if(($item->institution_id && $instituciones) != null)
                  @foreach($instituciones as $i)
                    @if($item->institution_id == $i->id)
                      {{$i->name}}
                    @endif
                  @endforeach
                @endif
              </td>
            @canany(['editar_movimientos', 'eliminar_movimientos'])
            <td class="text-center">
              @include('shared._actions', [
                    'entity' => 'contactos',
                    'parentModule' => 'movimientos',
                    'id' => $item->id,
                    'singular' => 'contacto',
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

@extends('admin.layout')

@section('title', '- Restauracion')

@section('breadcrumb')
    <li class="breadcrumb-item">
       <a href="{{route('restauracion')}}">Restauración</a>  | Historial
    </li>
@endsection

@section('content')
<div>
		Restauración

		@can ('agregar_inventario')
        <div class="row flex-row-reverse m-3">
        	<a href="/restauracion/create/{{$id}}" class="btn btn-outline-primary" rel="tooltip" title="Nueva restauración" data-target="#piezamodal"><i class="fas fa-plus"></i></a>
        </div>
        @endcan
       <table id="example" class="display" style="width:100%">
	        <thead>
	            <tr>
	                <th>N. Inventario</th>
	                <th>Fecha inicial de tratamiento</th>
	                <th>Fecha final de tratamiento</th>
	                <th>Reporte</th>
	            </tr>
	        </thead>
	        <tbody>
	        	 @foreach($renovations as $r)
	              <tr>
	                <td>{{$piece->inventory_number}}</td>
	                <td>{{$r->initial_date}}</td>
	                <td>{{$r->end_date}}</td>
	                <td class="text-center">
	         			<a href="../../restauracion/info/{{$r->id}}" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Ver detalles de la restauracion"><i class="far fa-eye"></i></a>
	                </td>
	              </tr>
	             @endforeach
	        </tbody>
	    </table>
	    </table>
    </div>
@endsection

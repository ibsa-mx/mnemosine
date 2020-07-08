@extends('admin.layout')
@section('title', '- Ver reporte')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('reportes.index')}}">Reportes</a>
    </li>
    <li class="breadcrumb-item active">
        <a href="{{route('reportes.show', $id)}}">Ver reporte</a>
    </li>
    <li class="breadcrumb-item">
    	Cédula 2. Artes decorativas
    </li>
@endsection

@section('content')
<div class="text-center mb-3">
    <a href="{{route('reportes.descargarCedula', '2')}}" class="btn btn-outline-primary btn-sm"><i class="fas fa-file-word"></i> Exportar a Word</a>
</div>

@if($piezas != null)
	@foreach($piezas as $pieza)
        @continue(!isset($pieza->research))
	<div class="d-flex justify-content-center">
		<div class="card border-info b-3 t-3 text-center" style="max-width: 80rem; width: 70%">
		  <div class="card-body text-center">
		  	<ul class="list-group list-group-flush">
			    <li class="list-group-item">
                    <h5 class="card-text font-italic">
				 		{{$pieza->research->title}}
			    	</h5>
                    <p>
                        (<em>@isset($pieza->research->place_of_creation){{$pieza->research->place_of_creation->title}}, @endisset @isset($pieza->research->period->title) {{$pieza->research->period->title}}@endisset</em>)
                    </p>
			    </li>
			    <li class="list-group-item">
			    	<p><!--Fecha de ejecución-->
						{{$pieza->research->creation_date}}
			    	</p>
			    	<p><!--Técnica-->
			    		{{$pieza->research->technique}}
			    	</p>
				    <p>
					    {{($pieza->height) ? $pieza->height: ''}}
					    {{($pieza->width) ? ' x '. $pieza->width : ''}}
					    {{($pieza->depth) ? ' x '. $pieza->depth: ''}}
						{{($pieza->diameter) ? ' ø '. $pieza->diameter: ''}}
					</p>
                    <p>{{$pieza->research->adquisition_source}}</p>
				    <p>No. procedencia: {{($pieza->origin_number) ? $pieza->origin_number: ''}}</p>
				    <p>No. inventario: {{($pieza->inventory_number) ? $pieza->inventory_number: ''}}</p>
				    <p>No. catálogo: {{($pieza->catalog_number) ?  $pieza->catalog_number: ''}}</p>
			    </li>
			    <li class="list-group-item">
			    	<p>
						{{isset($pieza->location->name) ? $pieza->location->name : ''}}
			    	</p>
			    </li>
		  	</ul>
		  </div>
		</div>
	</div>
	@endforeach
@endif

@endsection

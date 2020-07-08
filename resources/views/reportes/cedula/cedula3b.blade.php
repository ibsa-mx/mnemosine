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
    	Cédula 3. Artes decorativas
    </li>
@endsection

@section('content')
<div class="text-center mb-3">
    <a href="{{route('reportes.descargarCedula', '3b')}}" class="btn btn-outline-primary btn-sm"><i class="fas fa-file-word"></i> Exportar a Word</a>
</div>

@if($piezas != null)
	@foreach($piezas as $pieza)
        @continue(!isset($pieza->research))
	<div class="d-flex justify-content-center">
		<div class="card border-info b-3 t-3 text-center" style="max-width: 80rem; width: 70%">
		  <div class="card-body text-center">
		  	<ul class="list-group list-group-flush">
			    <li class="list-group-item">
                    <h5>
				 		{{$pieza->research->title}}
			    	</h5>
					<p>
						@isset($pieza->research->place_of_creation)
                            {{$pieza->research->place_of_creation->title}}
                        @endisset
					</p>
					<p>
			    		{{$pieza->research->technique}}
			    	</p>
			    </li>
		  	</ul>
		  </div>
		</div>
	</div>
	@endforeach
@endif

@endsection

<a href="{{route('consultas.detalle', $id)}}" class="btn btn-sm btn-outline-primary" data-toggle="tooltip" data-placement="top" title="Ver pieza"><i class="far fa-eye"></i></a>
@if (!$research_info)
    @can('agregar_investigacion')
        <a href="{{ route('investigacion.create', ['id' => $id]) }}" class="btn btn-outline-primary btn-sm" rel="tooltip" title="Agregar investigación"><i class="fas fa-plus"></i></a>
    @endcan
@else
    @include('shared._actions', [
        'entity' => 'investigacion',
        'id' => $id,
        'singular' => 'investigación',
    ])
@endif

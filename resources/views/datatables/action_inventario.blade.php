<a href="{{route('consultas.detalle', $id)}}" class="btn btn-sm btn-outline-primary" data-toggle="tooltip" data-placement="top" title="Ver pieza"><i class="far fa-eye"></i></a>
@include('shared._actions', [
    'entity' => 'inventario',
    'id' => $id,
    'singular' => 'pieza',
])

<a href="{{route('consultas.detalle', $id)}}" class="btn btn-sm btn-outline-primary" data-toggle="tooltip" data-placement="top" title="Ver pieza"><i class="far fa-eye"></i></a>
@can('agregar_restauracion')
    <a href="{{ route('restauracion.create', ['pieceId' => $id]) }}" class="btn btn-outline-primary btn-sm" rel="tooltip" title="Agregar restauración"><i class="fas fa-plus"></i></a>
@endcan
@if ($restoration_info)
    @can('ver_restauracion')
        <a href="{{ route('restauracion.listRecords', ['pieceId' => $id])  }}" class="btn btn-sm btn-outline-primary" rel="tooltip" title="Editar historial de restauración"><i class="far fa-edit"></i></a>
    @endcan
@endif

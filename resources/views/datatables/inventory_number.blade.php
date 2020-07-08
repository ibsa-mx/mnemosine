{{$piece->inventory_number}}
@if (in_array($moduleName, array('consultas', 'inventario')))
    @if ($piece->created_at->timestamp > $yesterday)
        <br/><span class="badge badge-success float-right mb-n2 mr-n2">Nuevo</span>
    @elseif ($piece->updated_at->timestamp > $yesterday)
        <br/><span class="badge badge-primary float-right mb-n2 mr-n2">Editado</span>
    @endif
@endif

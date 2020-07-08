@if (!is_null($piece->location_id))
    @if ($piece->location_id == 0)
        <strong class='text-danger'>En prestamo</strong>
    @elseif (!isset($piece->location->name))
        <em>N/A</em>
    @else
        @if ($moduleName == 'consultas')
            <a href="{!! route('consultas.search', $piece->location->name) !!}">
                {{$piece->location->name}}
            </a>
        @else
            {{$piece->location->name}}
        @endif
    @endif
@endif

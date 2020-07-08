@if (!is_null($subgender))
    <a href="{{route('consultas.search', $subgender["title"])}}">{{$subgender["title"]}}</a>
@endif

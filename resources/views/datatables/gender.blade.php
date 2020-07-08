@if (!is_null($gender))
    <a href="{{route('consultas.search', $gender["title"])}}">{{$gender["title"]}}</a>
@endif

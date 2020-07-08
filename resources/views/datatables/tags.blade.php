@isset($tags)
    @foreach (explode(",", $tags) as $tag)
        <a href="{{route('consultas.search', $tag)}}" class="badge badge-warning">{{$tag}}</a><br />
    @endforeach
@endisset

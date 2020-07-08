<div class="bg-gray-100 texto-grande">
    <div class="bg-secondary">
        {{$btLabel}} <button class="btn btn-link btn-lg p-0 clipboard" data-toggle="tooltip" title="Copiar al portapapeles" data-clipboard-target="#{{$btId}}"><i class="far fa-clipboard"></i></button>
    </div>
    <span id="{{$btId}}">{!! nl2br($btText) !!}</span>
</div>

@isset($photographs)
    @php
    $aux = 0;
    @endphp
    @foreach ($photographs as $photography)
        @continue($photography['module_id'] != $module['id'])
        <a href="{!! Storage::url('') !!}{!! config('fileuploads.'. $module['nameEN'] .'.photographs.originals') !!}/{{$photography['file_name']}}" class="{{ $aux++ == 0 ? '' : 'd-none' }}" data-toggle="lightbox" data-gallery="gallery-{{$module['id']}}-{{$photography['piece_id']}}" data-title="{{$photography['description']}}" data-footer="Fotógrafo: {{$photography['photographer']}}<br>Fecha: {{$photography['photographed_at']->locale('es_MX')->isoFormat('LL')}}">
            <img src="{!! Storage::url('') !!}{!! config('fileuploads.'. $module['nameEN'] .'.photographs.thumbnails') !!}/{{$photography['file_name']}}" class="img-thumbnail" alt="{{$photography['description']}}">
        </a>
    @endforeach
@endisset

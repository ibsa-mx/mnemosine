<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <span class="nav-link disabled pl-0"><b class="text-primary">{{$idx+1}}</b></span>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="chicago-tab-{{$idx+1}}" data-toggle="tab" href="#chicago-{{$idx+1}}" role="tab" aria-controls="chicago-{{$idx+1}}" aria-selected="false">Chicago</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" id="apa-tab-{{$idx+1}}" data-toggle="tab" href="#apa-{{$idx+1}}" role="tab" aria-controls="apa-{{$idx+1}}" aria-selected="true">APA</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="iso-690-tab-{{$idx+1}}" data-toggle="tab" href="#iso-690-{{$idx+1}}" role="tab" aria-controls="iso-690-{{$idx+1}}" aria-selected="false">ISO 690</a>
    </li>
    <li class="nav-item ml-5">
        <span class="nav-link disabled"><span class="badge badge-info">{{ ((int)$bibliography->reference_type_id > 0) ? $referenceTypesArray[$bibliography->reference_type_id] : 'Tipo de referencia no definido' }}</span></span>
    </li>
</ul>
<div class="tab-content mb-3">
    <div class="tab-pane fade" id="chicago-{{$idx+1}}" role="tabpanel" aria-labelledby="chicago-tab-{{$idx+1}}">
        {{$bibliography->author}}, {{!empty($bibliography->article) ? '"'.$bibliography->article.'"' : ''}} {{!empty($bibliography->chapter) ? '"'.$bibliography->chapter.'"' : ''}} {{!empty($bibliography->editor) ? 'En '.$bibliography->editor : ''}}
        <em>{{$bibliography->title}}</em>. {{!empty($bibliography->pages) ? $bibliography->pages.',' : ''}} {{ !empty($bibliography->city_country) ? $bibliography->city_country : '' }} {{ !empty($bibliography->city_country) && !empty($bibliography->editorial) ? ':' : '' }}
        {{!empty($bibliography->editorial) ? $bibliography->editorial.',':''}} {{!empty($bibliography->vol_no) ? $bibliography->vol_no.',' : ''}} {{$bibliography->publication_date}}. {{$bibliography->identifier}} {{ !empty($bibliography->webpage) ? $bibliography->webpage : ''}}
    </div>
    <div class="tab-pane fade show active" id="apa-{{$idx+1}}" role="tabpanel" aria-labelledby="apa-tab-{{$idx+1}}">
        {{$bibliography->author}} ({{$bibliography->publication_date}}). {{$bibliography->article}} {{$bibliography->chapter}} {{!empty($bibliography->editor) ? 'En '.$bibliography->editor : ''}} <em>{{$bibliography->title}}</em>. {{ !empty($bibliography->city_country) ? $bibliography->city_country : '' }}
        {{ !empty($bibliography->city_country) && !empty($bibliography->editorial) ? ':' : '' }} {{$bibliography->editorial}} {{!empty($bibliography->vol_no) ? $bibliography->vol_no.',' : ''}} {{!empty($bibliography->pages) ? $bibliography->pages.'.' : ''}} {{$bibliography->identifier}}
        {{ !empty($bibliography->webpage) ? 'Recuperado de '.$bibliography->webpage : ''}}
    </div>
    <div class="tab-pane fade" id="iso-690-{{$idx+1}}" role="tabpanel" aria-labelledby="iso-690-tab-{{$idx+1}}">
        {{$bibliography->author}}, {{!empty($bibliography->article) ? $bibliography->article : ''}} {{!empty($bibliography->chapter) ? $bibliography->chapter : ''}} {{!empty($bibliography->editor) ? 'En '.$bibliography->editor : ''}}
        <em>{{$bibliography->title}}</em>. {{ !empty($bibliography->city_country) ? $bibliography->city_country : '' }} {{ !empty($bibliography->city_country) && !empty($bibliography->editorial) ? ':' : '' }}
        {{!empty($bibliography->editorial) ? $bibliography->editorial.',':''}} {{$bibliography->publication_date}}. {{!empty($bibliography->vol_no) ? $bibliography->vol_no.',' : ''}} {{!empty($bibliography->pages) ? $bibliography->pages.',' : ''}}  {{$bibliography->identifier}}
        {{ !empty($bibliography->webpage) ? 'Disponible en: '.$bibliography->webpage : ''}}
    </div>
</div>

<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <span class="nav-link disabled pl-0"><b class="text-primary">{{$idx+1}}</b></span>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="chicago-tab-fn-{{$idx+1}}" data-toggle="tab" href="#chicago-fn-{{$idx+1}}" role="tab" aria-controls="chicago-fn-{{$idx+1}}" aria-selected="false">Chicago</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" id="apa-tab-fn-{{$idx+1}}" data-toggle="tab" href="#apa-fn-{{$idx+1}}" role="tab" aria-controls="apa-fn-{{$idx+1}}" aria-selected="true">APA</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="iso-690-tab-fn-{{$idx+1}}" data-toggle="tab" href="#iso-690-fn-{{$idx+1}}" role="tab" aria-controls="iso-690-fn-{{$idx+1}}" aria-selected="false">ISO 690</a>
    </li>
</ul>
<div class="tab-content mb-3">
    <div class="tab-pane fade" id="chicago-fn-{{$idx+1}}" role="tabpanel" aria-labelledby="chicago-tab-fn-{{$idx+1}}">
        {{$footnote->author}}, {{isset($footnote->article) ? '"'.$footnote->article.'"' : ''}} {{isset($footnote->chapter) ? '"'.$footnote->chapter.'"' : ''}}
        <em>{{$footnote->title}}</em>. {{isset($footnote->pages) ? $footnote->pages.',' : ''}} {{ isset($footnote->city_country) ? $footnote->city_country : '' }} {{ isset($footnote->city_country) && isset($footnote->editorial) ? ':' : '' }}
        {{isset($footnote->editorial) ? $footnote->editorial.',':''}} {{isset($footnote->vol_no) ? $footnote->vol_no.',' : ''}} {{$footnote->publication_date}}.
        <br /><em class="text-muted">{{$footnote->description}}</em>
    </div>
    <div class="tab-pane fade show active" id="apa-fn-{{$idx+1}}" role="tabpanel" aria-labelledby="apa-tab-fn-{{$idx+1}}">
        {{$footnote->author}} ({{$footnote->publication_date}}). {{$footnote->article}} {{$footnote->chapter}} <em>{{$footnote->title}}</em>. {{ isset($footnote->city_country) ? $footnote->city_country : '' }}
        {{ isset($footnote->city_country) && isset($footnote->editorial) ? ':' : '' }} {{$footnote->editorial}} {{isset($footnote->vol_no) ? $footnote->vol_no.',' : ''}} {{isset($footnote->pages) ? $footnote->pages.'.' : ''}}
        <br /><em class="text-muted">{{$footnote->description}}</em>
    </div>
    <div class="tab-pane fade" id="iso-690-fn-{{$idx+1}}" role="tabpanel" aria-labelledby="iso-690-tab-fn-{{$idx+1}}">
        {{$footnote->author}}, {{isset($footnote->article) ? $footnote->article : ''}} {{isset($footnote->chapter) ? $footnote->chapter : ''}}
        <em>{{$footnote->title}}</em>. {{ isset($footnote->city_country) ? $footnote->city_country : '' }} {{ isset($footnote->city_country) && isset($footnote->editorial) ? ':' : '' }}
        {{isset($footnote->editorial) ? $footnote->editorial.',':''}} {{$footnote->publication_date}}. {{isset($footnote->vol_no) ? $footnote->vol_no.',' : ''}} {{isset($footnote->pages) ? $footnote->pages.',' : ''}}
        <br /><em class="text-muted">{{$footnote->description}}</em>
    </div>
</div>

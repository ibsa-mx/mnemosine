<?xml version="1.0"?>
<metadata xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/">
@if(!is_null($piece->research))
    <dc:title>{{$piece->research->title}}</dc:title>
    @if(!empty($piece->research->creation_date))
        <dc:date>{{$piece->research->creation_date}}</dc:date>
    @endif
    @isset($authorNames)
        @foreach ($authorNames as $authorName)
            <dc:creator>{{$authorName}}</dc:creator>
        @endforeach
    @endisset
@endif
    <dc:description>{{$piece->description_origin}}</dc:description>
    <dc:language>es</dc:language>
    <dc:format.width>{{$piece->width}}</dc:format.width>
    <dc:format.height>{{$piece->height}}</dc:format.height>
    <dc:identifier>{{$piece->origin_number}}</dc:identifier>
    <dc:identifier>{{$piece->inventory_number}}</dc:identifier>
    <dc:identifier>{{$piece->catalog_number}}</dc:identifier>
    <dc:publisher>{{$publisher}}</dc:publisher>
    <dc:subject>{{$gender}}</dc:subject>
    <dc:subject>{{$subgender}}</dc:subject>
</metadata>

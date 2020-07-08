<div id="bibliography-clones"></div>

<div id="bibliography-elements" class="d-none">
    <div class="row border border-secondary rounded py-2 mb-3 bg-gray-400 mx-1">
        {!! Form::hidden('bibliography_id_bd[]', null) !!}
        <div class="col-6">
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">Tipo de referencia</span>
                </div>
                <select class="form-control" name="bibliography_reference_type_id[]">
                    <option selected="selected" disabled="disabled" value="0">Seleccione una opción</option>
                @foreach($refTypes as $refType)
                    <option value="{{$refType->id}}">
                        {{$refType->title}}
                    </option>
                @endforeach
                </select>
            </div>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">Autor(es)</span>
                </div>
                {!! Form::textarea('bibliography_author[]', null, ['class' => 'form-control', 'placeholder' => '', 'rows' => '2']) !!}
            </div>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">Título de la <br />publicación</span>
                </div>
                {!! Form::textarea('bibliography_title[]', null, ['class' => 'form-control', 'placeholder' => '', 'rows' => '2']) !!}
            </div>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">Ciudad, país</span>
                </div>
                {!! Form::textarea('bibliography_city_country[]', null, ['class' => 'form-control', 'placeholder' => '', 'rows' => '2']) !!}
            </div>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">Páginas</span>
                </div>
                {!! Form::text('bibliography_pages[]', null, ['class' => 'form-control', 'placeholder' => '']) !!}
            </div>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">Volumen y/o número</span>
                </div>
                {!! Form::text('bibliography_vol_no[]', null, ['class' => 'form-control', 'placeholder' => '']) !!}
            </div>
            <div class="input-group">
                <div class="input-group-prepend mb-2">
                    <span class="input-group-text">Fecha de publicación</span>
                </div>
                {!! Form::text('bibliography_publication_date[]', null, ['class' => 'form-control', 'placeholder' => '']) !!}
            </div>
        </div>

        <div class="col-6">
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">Artículo <br/>o ensayo</span>
                </div>
                {!! Form::textarea('bibliography_article[]', null, ['class' => 'form-control', 'placeholder' => '', 'rows' => '2']) !!}
            </div>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">Capítulo o <br />agregado</span>
                </div>
                {!! Form::textarea('bibliography_chapter[]', null, ['class' => 'form-control', 'placeholder' => '', 'rows' => '2']) !!}
            </div>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">Editor</span>
                </div>
                {!! Form::textarea('bibliography_editor[]', null, ['class' => 'form-control', 'placeholder' => '', 'rows' => '2']) !!}
            </div>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">Editorial</span>
                </div>
                {!! Form::textarea('bibliography_editorial[]', null, ['class' => 'form-control', 'placeholder' => '', 'rows' => '2']) !!}
            </div>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">Página web</span>
                </div>
                {!! Form::url('bibliography_webpage[]', null, ['class' => 'form-control', 'placeholder' => 'http://ejemplo.com']) !!}
            </div>
            <div class="input-group">
                <div class="input-group-prepend mb-2">
                    <span class="input-group-text">Identificador</span>
                </div>
                {!! Form::text('bibliography_identifier[]', null, ['class' => 'form-control', 'placeholder' => 'DOI, Handle, ELocationID']) !!}
            </div>
        </div>
        <div class="text-light ml-3 mb-3">
            <strong class="text-danger">*</strong> Ingrese los datos sin signos de puntuación para el estilo de citación (comas, puntos, comillas)
        </div>
        <div class="col-12 text-center">
            <button class="btn btn-danger bibliography_delete"><i class="far fa-trash-alt"></i> Eliminar bibliografía</button>
        </div>
    </div>
</div>
<div class="text-center mb-2">
    <button class="btn btn-success" id="bibliography-add"><i class="fas fa-plus"></i> Agregar bibliografía</button>
</div>

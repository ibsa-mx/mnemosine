<div id="footnote-elements" class="d-none">
    <div class="row border border-secondary rounded py-2 mb-3 bg-gray-400 mx-1">
        {!! Form::hidden('footnote_id_bd[]', null) !!}
        <div class="col-6">
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">Autor(es)</span>
                </div>
                {!! Form::textarea('footnote_author[]', null, ['class' => 'form-control', 'placeholder' => '', 'rows' => '2']) !!}
            </div>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">Título de la <br />publicación</span>
                </div>
                {!! Form::textarea('footnote_title[]', null, ['class' => 'form-control', 'placeholder' => '', 'rows' => '2']) !!}
            </div>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">Ciudad, país</span>
                </div>
                {!! Form::textarea('footnote_city_country[]', null, ['class' => 'form-control', 'placeholder' => '', 'rows' => '2']) !!}
            </div>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">Volumen y/o número</span>
                </div>
                {!! Form::text('footnote_vol_no[]', null, ['class' => 'form-control', 'placeholder' => '']) !!}
            </div>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">Descripción</span>
                </div>
                {!! Form::textarea('footnote_description[]', null, ['class' => 'form-control', 'placeholder' => '', 'rows' => '2']) !!}
            </div>
        </div>
        <div class="col-6">
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">Artículo <br/>o ensayo</span>
                </div>
                {!! Form::textarea('footnote_article[]', null, ['class' => 'form-control', 'placeholder' => '', 'rows' => '2']) !!}
            </div>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">Capítulo o <br />agregado</span>
                </div>
                {!! Form::textarea('footnote_chapter[]', null, ['class' => 'form-control', 'placeholder' => '', 'rows' => '2']) !!}
            </div>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">Editorial</span>
                </div>
                {!! Form::textarea('footnote_editorial[]', null, ['class' => 'form-control', 'placeholder' => '', 'rows' => '2']) !!}
            </div>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">Páginas</span>
                </div>
                {!! Form::text('footnote_pages[]', null, ['class' => 'form-control', 'placeholder' => '']) !!}
            </div>
            <div class="input-group">
                <div class="input-group-prepend mb-2">
                    <span class="input-group-text">Fecha de publicación</span>
                </div>
                {!! Form::text('footnote_publication_date[]', today()->format('Y-m-d'), ['class' => 'form-control', 'placeholder' => '']) !!}
            </div>
        </div>
        <div class="col-12 text-center">
            <button class="btn btn-danger footnote_delete"><i class="far fa-trash-alt"></i> Eliminar nota al pie</button>
        </div>
    </div>
</div>
<div id="footnote-clones"></div>
<div class="text-center mb-2">
    <button class="btn btn-success" id="footnote-add"><i class="fas fa-plus"></i> Agregar nota al pie</button>
</div>

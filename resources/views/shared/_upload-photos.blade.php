<div id="photo-elements" class="d-none">
    <div class="row border border-secondary rounded py-2 mb-3 bg-gray-400 mx-1">
        <div class="col-4">
            <div class="form-group mb-0">
                {!! Form::hidden('photo_id_bd[]', null) !!}
                <input type="file" name="photo_file[]" accept="image/*" />
            </div>
        </div>
        <div class="col-8 align-self-center">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Descripción de la toma</span>
                </div>
                {!! Form::textarea('photo_description[]', null, ['class' => 'form-control', 'placeholder' => '', 'rows' => '5']) !!}
            </div>
            <div class="input-group">
                <div class="input-group-prepend mb-3">
                    <span class="input-group-text">Fecha de la toma</span>
                </div>
                {!! Form::text('photo_date[]', today()->format('Y-m-d'), ['class' => 'form-control', 'placeholder' => '', 'autocomplete' => 'off']) !!}
            </div>
            <div class="input-group">
                <div class="input-group-prepend mb-3">
                    <span class="input-group-text">Nombre del fotógrafo</span>
                </div>
                {!! Form::text('photo_author[]', null, ['class' => 'form-control', 'placeholder' => '']) !!}
            </div>
            <div class="text-center">
                <button class="btn btn-danger photo_delete"><i class="far fa-trash-alt"></i> Eliminar foto</button>
            </div>
        </div>
    </div>
</div>
<div id="photo-clones"></div>
<div class="text-center mb-2">
    <button class="btn btn-success" id="photo-add"><i class="fas fa-plus"></i> Agregar foto</button>
</div>

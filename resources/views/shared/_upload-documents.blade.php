<div id="document-clones"></div>

<div id="document-elements" class="d-none">
    <div class="row border border-secondary rounded py-2 mb-3 bg-gray-400 mx-1">
        <div class="col-8">
            <div class="form-group mb-0">
                {!! Form::hidden('document_id_bd[]', null) !!}
                <input type="file" name="document_file[]" accept=".doc, .docx, .xml, .xlsx, .xls, .ppt, .pptx, .html, .txt, .pdf, application/msword" />
            </div>
        </div>
        <div class="col-4 align-self-center">
            <div class="input-group">
                <div class="input-group-prepend mb-3">
                    <span class="input-group-text">Nombre</span>
                </div>
                {!! Form::text('document_name[]', null, ['class' => 'form-control', 'placeholder' => 'Para identificar al archivo']) !!}
            </div>
            <div class="text-center">
                <button class="btn btn-danger document_delete"><i class="far fa-trash-alt"></i> Eliminar documento</button>
            </div>
        </div>
    </div>
</div>

<div class="text-center mb-2">
    <button class="btn btn-success" id="document-add"><i class="fas fa-plus"></i> Agregar documento</button>
</div>

@push('after_all_styles')
    <link href="{{ asset('admin/vendors/bootstrap-fileinput/themes/explorer-fas/theme.min.css')}}" media="all" rel="stylesheet" type="text/css"/>
@endpush

@push('after_all_scripts')
    <script src="{{asset('admin/vendors/bootstrap-fileinput/themes/explorer-fas/theme.min.js')}}"></script>
@endpush

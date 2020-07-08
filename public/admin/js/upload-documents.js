$(function() {
    var document_id = 1;
    var document_template = $( $("#document-elements").html() );

    $("#document-add").on('click', function(e){
        $('#document-clones').append( document_template.clone().attr('id','document-clone-' + document_id) );
        $("#document-clone-" + document_id + ' .document_delete').data('parent', document_id);
        $("#document-clone-" + document_id + ' input[type="file"]').fileinput(fileDocumentsInitOptions);

        document_id++;
        return false;
    });

    $("#document-clones").on('click', ".document_delete", function(e){
        // guardar en un input hidden cuales son los ids de documentos que estaban en la base de datos
        var idDocumentBD = $("#document-clone-" + $(this).data('parent') + ' input[name="document_id_bd[]"]').val();
        var idsDeletedBD = $('input[name="document_ids_bd_deleted"]').val(); //un solo campo para todos
        if(idsDeletedBD != ''){
            $('input[name="document_ids_bd_deleted"]').val(idsDeletedBD + "," + idDocumentBD);
        } else{
            $('input[name="document_ids_bd_deleted"]').val(idDocumentBD);
        }

        // se elimina el div de la documento
        $("#document-clone-" + $(this).data('parent')).remove();
        return false;
    });

    // se agrega el primer clon
    $("#document-add").trigger("click");

    // si esta editando debera estar declarada la variable documentsBD
    if (typeof documentsBD != "undefined"){
        var mimeTypes = {
            "text/plain": "text",
            "application/pdf": "pdf",
            "application/xml": "text",
            "text/html": "html",
            "application/msword": "other",
            "application/vnd.ms-excel": "other",
            "application/vnd.ms-powerpoint": "other",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document": "other",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet": "other",
            "application/vnd.openxmlformats-officedocument.presentationml.presentation": "other",
        };
        var iteracion = 1;
        $.each(documentsBD, function(index, documentBD){
            // se agregan clones solo si es la segunda iteracion o posterior
            if(iteracion > 1) $("#document-add").trigger("click");

            // se agregan los datos en los campos correspondientes
            $("#document-clone-" + iteracion + ' input[name="document_id_bd[]"]').val(documentBD[0].id);
            $("#document-clone-" + iteracion + ' input[name="document_name[]"]').val(documentBD[0].name);

            // se modifica el input file para que muestre las imagenes
            $("#document-clone-" + iteracion + ' input[type="file"]').fileinput('destroy');
            var fileType = mimeTypes[documentBD[0].mime_type];
            console.log(fileType);
            var fileEditOptions = $.extend({}, fileDocumentsInitOptions, {
                initialPreview: urlStorageDocuments + documentBD[0].file_name,
                initialPreviewAsData: true,
                initialPreviewConfig: [
                    {type: fileType, downloadUrl: urlStorageDocuments + documentBD[0].file_name, size: documentBD[0].size, key: 1},
                ],
                fileActionSettings:{
                    showRemove: false,
                    showDrag: false,
                    showUpload: false,
                },
                overwriteInitial: true,
            });
            $("#document-clone-" + iteracion + ' input[type="file"]').fileinput(fileEditOptions);

            iteracion++;
        });  // termina .each
    }
});

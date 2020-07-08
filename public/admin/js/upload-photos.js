$(function() {
    var photo_id = 1;
    var photo_template = $( $("#photo-elements").html() );

    $("#photo-add").on('click', function(e){
        $('#photo-clones').append( photo_template.clone().attr('id','photo-clone-' + photo_id) );
        $("#photo-clone-" + photo_id + ' .photo_delete').data('parent', photo_id);
        $("#photo-clone-" + photo_id + ' input[type="file"]').fileinput(filePhotosInitOptions);

        $("#photo-clone-" + photo_id + ' input[name="photo_date[]"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 1901,
            maxYear: parseInt(moment().format('YYYY'),10),
            locale: {
                format: 'YYYY-MM-DD'
            }
        });

        photo_id++;
        return false;
    });

    $("#photo-clones").on('click', ".photo_delete", function(e){
        // guardar en un input hidden cuales son los ids de fotos que estaban en la base de datos
        var idPhotoBD = $("#photo-clone-" + $(this).data('parent') + ' input[name="photo_id_bd[]"]').val();
        var idsDeletedBD = $('input[name="photo_ids_bd_deleted"]').val(); //un solo campo para todos
        if(idsDeletedBD != ''){
            $('input[name="photo_ids_bd_deleted"]').val(idsDeletedBD + "," + idPhotoBD);
        } else{
            $('input[name="photo_ids_bd_deleted"]').val(idPhotoBD);
        }

        // se elimina el div de la foto
        $("#photo-clone-" + $(this).data('parent')).remove();
        return false;
    });

    // se agrega el primer clon
    $("#photo-add").trigger("click");

    // si esta editando debera estar declarada la variable photosBD
    if (typeof photosBD != "undefined"){
        var iteracion = 1;
        $.each(photosBD, function(index, photoBD){
            // se agregan clones solo si es la segunda iteracion o posterior
            if(iteracion > 1) $("#photo-add").trigger("click");

            // se agregan los datos en los campos correspondientes
            $("#photo-clone-" + iteracion + ' input[name="photo_id_bd[]"]').val(photoBD[0].id);
            $("#photo-clone-" + iteracion + ' textarea[name="photo_description[]"]').text(photoBD[0].description);
            $("#photo-clone-" + iteracion + ' input[name="photo_date[]"]').val(photoBD[0].photographed_at.split(" ")[0]);
            $("#photo-clone-" + iteracion + ' input[name="photo_author[]"]').val(photoBD[0].photographer);

            // se modifica el input file para que muestre las imagenes
            $("#photo-clone-" + iteracion + ' input[type="file"]').fileinput('destroy');
            var fileEditOptions = $.extend({}, filePhotosInitOptions, {
                initialPreview: urlStoragePhotographs + photoBD[0].file_name,
                initialPreviewAsData: true,
                initialPreviewConfig: [
                    {type: "image", downloadUrl: urlStoragePhotographs + photoBD[0].file_name, size: photoBD[0].size, key: 1},
                ],
                fileActionSettings:{
                    showRemove: false,
                    showDrag: false,
                    showUpload: false,
                },
                overwriteInitial: true,
            });
            $("#photo-clone-" + iteracion + ' input[type="file"]').fileinput(fileEditOptions);

            iteracion++;
        });  // termina .each
    }
});

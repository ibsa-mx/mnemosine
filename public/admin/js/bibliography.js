$(function() {
    var bibliography_id = 1;
    var bibliography_template = $( $("#bibliography-elements").html() );

    $("#bibliography-add").on('click', function(e){
        $('#bibliography-clones').append( bibliography_template.clone().attr('id','bibliography-clone-' + bibliography_id) );

        $("#bibliography-clone-" + bibliography_id + ' .bibliography_delete').data('parent', bibliography_id);
        $("#bibliography-clone-" + bibliography_id + ' select[name="bibliography_reference_type_id[]"]').select2({
            theme: 'bootstrap4',
            placeholder: 'Seleccione una opción'
        });

        bibliography_id++;
        return false;
    });

    $("#bibliography-clones").on('click', ".bibliography_delete", function(e){
        // guardar en un input hidden cuales son los ids de fotos que estaban en la base de datos
        var idbibliographyBD = $("#bibliography-clone-" + $(this).data('parent') + ' input[name="bibliography_id_bd[]"]').val();
        var idsDeletedBD = $('input[name="bibliography_ids_bd_deleted"]').val(); //un solo campo para todos
        if(idsDeletedBD != ''){
            $('input[name="bibliography_ids_bd_deleted"]').val(idsDeletedBD + "," + idbibliographyBD);
        } else{
            $('input[name="bibliography_ids_bd_deleted"]').val(idbibliographyBD);
        }

        // se elimina el div de la bibliografia
        $("#bibliography-clone-" + $(this).data('parent')).remove();
        return false;
    });

    // se agrega el primer clon
    //$("#bibliography-add").trigger("click");

    // si esta editando debera estar declarada la variable bibliographysBD
    if (typeof bibliographysBD != "undefined"){
        var iteracion = 1;
        $.each(bibliographysBD, function(index, bibliographyBD){
            // se agregan clones solo si es la segunda iteracion o posterior
            $("#bibliography-add").trigger("click");

            // se agregan los datos en los campos correspondientes
            $("#bibliography-clone-" + iteracion + ' input[name="bibliography_id_bd[]"]').val(bibliographyBD[0].id);

            // TODO seleccionar la opcion de acuerdo al id
            $("#bibliography-clone-" + iteracion + ' select[name="bibliography_reference_type_id[]"]').val(bibliographyBD[0].reference_type_id);
            $("#bibliography-clone-" + iteracion + ' select[name="bibliography_reference_type_id[]"]').trigger('change');

            $("#bibliography-clone-" + iteracion + ' textarea[name="bibliography_title[]"]').text(bibliographyBD[0].title);
            $("#bibliography-clone-" + iteracion + ' textarea[name="bibliography_author[]"]').text(bibliographyBD[0].author);
            $("#bibliography-clone-" + iteracion + ' textarea[name="bibliography_article[]"]').text(bibliographyBD[0].article);
            $("#bibliography-clone-" + iteracion + ' textarea[name="bibliography_chapter[]"]').text(bibliographyBD[0].chapter);
            $("#bibliography-clone-" + iteracion + ' textarea[name="bibliography_editorial[]"]').text(bibliographyBD[0].editorial);
            $("#bibliography-clone-" + iteracion + ' textarea[name="bibliography_editor[]"]').text(bibliographyBD[0].editor);
            $("#bibliography-clone-" + iteracion + ' textarea[name="bibliography_city_country[]"]').text(bibliographyBD[0].city_country);

            $("#bibliography-clone-" + iteracion + ' input[name="bibliography_vol_no[]"]').val(bibliographyBD[0].vol_no);
            $("#bibliography-clone-" + iteracion + ' input[name="bibliography_pages[]"]').val(bibliographyBD[0].pages);
            $("#bibliography-clone-" + iteracion + ' input[name="bibliography_identifier[]"]').val(bibliographyBD[0].identifier);
            $("#bibliography-clone-" + iteracion + ' input[name="bibliography_publication_date[]"]').val(bibliographyBD[0].publication_date);
            $("#bibliography-clone-" + iteracion + ' input[name="bibliography_webpage[]"]').val(bibliographyBD[0].webpage);

            iteracion++;
        });  // termina .each
    }
});

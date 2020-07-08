$(function() {
    var footnote_id = 1;
    var footnote_template = $( $("#footnote-elements").html() );

    $("#footnote-add").on('click', function(e){
        $('#footnote-clones').append( footnote_template.clone().attr('id','footnote-clone-' + footnote_id) );

        $("#footnote-clone-" + footnote_id + ' .footnote_delete').data('parent', footnote_id);

        footnote_id++;
        return false;
    });

    $("#footnote-clones").on('click', ".footnote_delete", function(e){
        // guardar en un input hidden cuales son los ids que estaban en la base de datos
        var idfootnoteBD = $("#footnote-clone-" + $(this).data('parent') + ' input[name="footnote_id_bd[]"]').val();
        var idsDeletedBD = $('input[name="footnote_ids_bd_deleted"]').val(); //un solo campo para todos
        if(idsDeletedBD != ''){
            $('input[name="footnote_ids_bd_deleted"]').val(idsDeletedBD + "," + idfootnoteBD);
        } else{
            $('input[name="footnote_ids_bd_deleted"]').val(idfootnoteBD);
        }

        // se elimina el div
        $("#footnote-clone-" + $(this).data('parent')).remove();
        return false;
    });

    // se agrega el primer clon
    //$("#footnote-add").trigger("click");

    // si esta editando debera estar declarada la variable footnotesBD
    if (typeof footnotesBD != "undefined"){
        var iteracion = 1;
        $.each(footnotesBD, function(index, footnoteBD){
            // se agregan clones solo si es la segunda iteracion o posterior
            $("#footnote-add").trigger("click");

            // se agregan los datos en los campos correspondientes
            $("#footnote-clone-" + iteracion + ' input[name="footnote_id_bd[]"]').val(footnoteBD[0].id);

            $("#footnote-clone-" + iteracion + ' textarea[name="footnote_title[]"]').text(footnoteBD[0].title);
            $("#footnote-clone-" + iteracion + ' textarea[name="footnote_author[]"]').text(footnoteBD[0].author);
            $("#footnote-clone-" + iteracion + ' textarea[name="footnote_article[]"]').text(footnoteBD[0].article);
            $("#footnote-clone-" + iteracion + ' textarea[name="footnote_chapter[]"]').text(footnoteBD[0].chapter);
            $("#footnote-clone-" + iteracion + ' textarea[name="footnote_editorial[]"]').text(footnoteBD[0].editorial);
            $("#footnote-clone-" + iteracion + ' textarea[name="footnote_city_country[]"]').text(footnoteBD[0].city_country);
            $("#footnote-clone-" + iteracion + ' textarea[name="footnote_description[]"]').text(footnoteBD[0].description);

            $("#footnote-clone-" + iteracion + ' input[name="footnote_vol_no[]"]').val(footnoteBD[0].vol_no);
            $("#footnote-clone-" + iteracion + ' input[name="footnote_pages[]"]').val(footnoteBD[0].pages);
            $("#footnote-clone-" + iteracion + ' input[name="footnote_publication_date[]"]').val(footnoteBD[0].publication_date);

            iteracion++;
        });  // termina .each
    }
});

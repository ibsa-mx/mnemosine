$(function() {
    // clones
    var location_id = 1;
    var location_template = $( $("#location-elements").html() );

    $("#btn-add-location").on('click', function(e){
        if($('#count_locations').val() >= $('#count_pieces').val()){
            e.preventDefault();
            toastr.warning("No puede haber más ubicaciones que piezas");
            return;
        }
        if($('#count_selected').val() >= $('#count_pieces').val()){
            e.preventDefault();
            toastr.warning("Ya selecciono todas las piezas");
            return;
        }
        $('#location-clones').append( location_template.clone().attr('id','location-clone-' + location_id) );
        $("#location-clone-" + location_id + ' .location_delete').data('parent', location_id);
        $("#location-clone-" + location_id + ' .select-piezas').data('parent', location_id);

        $('#location-clone-' + location_id + ' .text-arrival-date').attr('name', 'arrival_date_' + location_id);
        $('#location-clone-' + location_id + ' .select-mueble').attr('name', 'tags_' + location_id + '[]');
        $('#location-clone-' + location_id + ' .select-piezas').attr('name', 'pieces_ids_' + location_id + '[]');
        $('#location-clone-' + location_id + ' .select-piezas').attr('required', true);
        $('#location-clone-' + location_id + ' .select-ubicacion').attr('name', 'location_id_' + location_id);
        $('#location_numbers').val(($('#location_numbers').val() == '' ? '' : $('#location_numbers').val() + ',') + location_id);
        $('#count_locations').val(parseInt($('#count_locations').val()) + 1);

        $('#location-clone-' + location_id + ' .span-ubicacion').html(location_id);

        // se desactivan opciones del select para las piezas
        $('.hidden-piece-fields').each(function(index){
            if($(this).val() == 1){
                $('#location-clone-' + location_id + ' .select-piezas .option-' + $(this).data('piece-id')).prop("disabled", true);
            }
        });

        // se prepara el calendario
        $('#location-clone-' + location_id + ' .text-arrival-date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 1901,
            maxYear: parseInt(moment().format('YYYY'),10),
            locale: {
                format: 'YYYY-MM-DD'
            }
        });

        // se preparan los select2
        $('#location-clone-' + location_id + ' .select-mueble').select2({
    		theme: 'bootstrap4',
    		placeholder: 'Palabras separadas por coma o punto y coma',
    		tags: true,
    		tokenSeparators: [',', ';'],
    		createTag: function (params) {
    			var term = $.trim(params.term).replace(/[\,\;]/g, '');
    			if (term == '') {
    				return null;
    			}
    			return {
    				id: term,
    				text: term,
    				newTag: true // add additional parameters
    			}
    		}
    	});
        $('#location-clone-' + location_id + ' .select-piezas').select2({
            theme: 'bootstrap4',
            allowClear: false,
            placeholder: 'Seleccione una o varias opciones',
            closeOnSelect: false,
            scrollAfterSelect: true
        });
        $('#location-clone-' + location_id + ' .select-ubicacion').select2({
            theme: 'bootstrap4',
            placeholder: 'Seleccione una opción2'
        });

        location_id++;
        return false;
    });

    $("#location-clones").on('click', ".location_delete", function(e){
        // guardar en un input hidden cuales son los ids de ubicaciones que estaban en la base de datos
        // var idLocationBD = $("#location-clone-" + $(this).data('parent') + ' input[name="location_id_bd[]"]').val();
        // var idsDeletedBD = $('input[name="location_ids_bd_deleted"]').val(); //un solo campo para todos
        // if(idsDeletedBD != ''){
        //     $('input[name="location_ids_bd_deleted"]').val(idsDeletedBD + "," + idLocationBD);
        // } else{
        //     $('input[name="location_ids_bd_deleted"]').val(idLocationBD);
        // }

        $('#location_numbers_deleted').val(($('#location_numbers_deleted').val() == '' ? '' : $('#location_numbers_deleted').val() + ',') + $(this).data('parent'));
        $('#count_locations').val(parseInt($('#count_locations').val()) - 1);

        // se elimina el clone
        $("#location-clone-" + $(this).data('parent')).remove();
        return false;
    });

    //$("#location-clones").on('change', ".select-piezas", function(e){
    //    $(this).data('selected', )
        //$(this).data('parent')
        //$('#location-clone-' + $(this).data('parent'))
    //    $('#location-clone-' + $(this).data('parent') + ' .select-piezas option:selected').map(function(){
    //        $('#location-clone-' + '2' + ' .select-piezas .option-' + this.value).prop("disabled", true);
            //return this.value;
    //    });//.get().join(",");
    //});
    var map = [];

    $("#location-clones").on('change', ".select-piezas", function(){
        var myId = $(this).data('parent');
        var thisOptions = $('#location-clone-' + $(this).data('parent') + ' .select-piezas option:selected').map(function() {return this.value}).get();
        var comp = thisOptions;

        map[myId] = (typeof $(this).data('map') == "undefined") ? [] : $(this).data('map');
        set1 = map[myId].filter(function(i) {
            return comp.indexOf(i) < 0;
        });
        set2 = comp.filter(function(i) {
            return map[myId].indexOf(i) < 0;
        });
        lastOption = (set1.length ? set1 : set2)[0];
        $(this).data('map', comp);

        var locations = $('#location_numbers').val().split(',');
        var locationsDeletedTxt = $('#location_numbers_deleted').val() + ',' + myId;
        var locationsDeleted = locationsDeletedTxt.split(',');

        locationsToChange = locations.filter(function(i) {
            return locationsDeleted.indexOf(i) < 0;
        });

        if(set1.length){
            // an option was UNSELECTED, let's ENABLE it in the other selects
            $.each(locationsToChange, function(key, value){
                $('#location-clone-' + value + ' .select-piezas .option-' + lastOption).prop("disabled", false);
                $("#hidden-piece-" + lastOption).val('0');
                $('#count_selected').val(parseInt($('#count_selected').val()) - 1);
            });
        } else{
            // an option was SELECTED, let's DISABLE it in the other selects
            $.each(locationsToChange, function(key, value){
                $('#location-clone-' + value + ' .select-piezas .option-' + lastOption).prop("disabled", true);
                $("#hidden-piece-" + lastOption).val('1');
                $('#count_selected').val(parseInt($('#count_selected').val()) + 1);
            });
        }
    });

    $('#btn-submit').on('click', function (e) {
        $('frmRetrieval').trigger('submit');
    });
});

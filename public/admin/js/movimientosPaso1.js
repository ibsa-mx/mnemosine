$(function() {
    var sedeMultiple = $("#movement_itinerant").val() == '1' ? true : false;

    // by default (create) is external
    if($("#movement_type_init").val() == 'internal'){
        //hide select institution, itinerant
        $("#div_itinerant").hide();
        $("#inst").hide();
        $("#sede_div").hide();
    } else{
        //hide label internal institution
        $('#linst').hide();
    }

    $("#institucion").change(function(event) {
        var selectValues = $(this).val().join(',');
        if(selectValues.length > 0){
            $.get('/mov1ContactAjax/'+selectValues+'',function(response, state){
                $('#contacto').empty();
                $('#contacto_resguardo').empty();
                for (var i = 0; i<response.length; i++) {
                    var nombre = response[i].name + ' ' + ((response[i].last_name != null) ? response[i].last_name : '');
                    $('#contacto').append('<option value="'+response[i].id+'">'+nombre+'</option>');
                    $('#contacto_resguardo').append('<option value="'+response[i].id+'">'+nombre+'</option>');
                }
            });
            $.get('/mov1ExhibitionAjax/'+selectValues+'',function(response, state){
                $('#exposicion').empty();
                $('#exposicion').append('<option selected="selected" disabled="disabled">Seleccione una opción </option>');
                for (var i = 0; i<response.length; i++) {
                    $('#exposicion').append('<option value="'+response[i].id+'">'+response[i].name+'</option>');
                }
            });
            $.get('/mov1VenuesAjax/'+selectValues+'',function(response, state){
                $('#sede').empty();
                if(!sedeMultiple){
                    $('#sede').append('<option selected="selected" disabled="disabled">Seleccione una opción </option>');
                }
                for (var i = 0; i<response.length; i++) {
                    $('#sede').append('<option value="'+response[i].id+'">'+response[i].name+'</option>');
                }
            });
        } else{
            $('#contacto').empty();
            $('#contacto_resguardo').empty();
            $('#exposicion').empty();
            $('#sede').empty();
        }
    });

    // Toggle to internal
    //---------------------------------------------------------------
    $('#internal').on('click', function(){
        $('#itinerante').prop('checked', false);
        $('#div_itinerant').fadeOut("slow");
        $('#sede').attr('multiple', false);
        sedeMultiple = false;
        $('#sede').select2({
            theme: 'bootstrap4',
            placeholder: 'Seleccione una opción'
        });

        $('#inst').hide();
        $('#sede_div').hide();
        $('#linst').fadeIn("slow");

        $('#institucion').val('');

        var id_institucion = $("#internal_institution_id").val();

        $.get('/contactAjax/'+id_institucion+'',function(response, state){
            $('#contacto').empty();

            for (var i = 0; i<response.length; i++) {
                var nombre = response[i].name + ' ' + ((response[i].last_name != null) ? response[i].last_name : '');
                $('#contacto').append('<option value="'+response[i].id+'">'+nombre+'</option>');
                $('#contacto_resguardo').append('<option value="'+response[i].id+'">'+nombre+'</option>');
            }
        });

        $.get('/mov1ExhibitionAjax/'+id_institucion+'', function(response, state){
            $('#exposicion').empty();
            $('#exposicion').append('<option selected="selected" disabled="disabled">Seleccione una opción </option>');
            for (var i = 0; i<response.length; i++) {
                $('#exposicion').append('<option value="'+response[i].id+'">'+response[i].name+'</option>');
            }
        });
        $.get('/mov1VenuesAjax/'+id_institucion+'', function(response, state){
            $('#sede').empty();
            $('#sede').append('<option selected="selected" disabled="disabled">Seleccione una opción </option>');
            for (var i = 0; i<response.length; i++) {
                $('#sede').append('<option value="'+response[i].id+'">'+response[i].name+'</option>');
            }
        });

    });


    // Toggle to external
    //---------------------------------------------------------------
    $('#external').on('click', function(){
        $('#div_itinerant').fadeIn("slow");
        $('#linst').hide(); //label de Institución
        $('#inst').fadeIn("slow");
        $('#sede_div').fadeIn("slow");

        $.get('/institutionsAjax/1',function(response, state){
            //console.log(response);
            $('#institucion').empty();
            for (var i = 0; i<response.length; i++) {
                // no mostrar institucion interna entre las opciones
                if($("#internal_institution_id").val() == response[i].id) continue;

                $('#institucion').append('<option value="'+response[i].id+'">'+response[i].name+'</option>');
            }
        });
        $('#institucion').val('');
        $('#institucion').trigger('change');
    });


    // Toggle itinerante
    //---------------------------------------------------------------
    $('#itinerante').change(function(event) {
        $('#sede').val('');
        if ($('#itinerante').prop('checked')) {
            $('#sede').attr('multiple', true);
            sedeMultiple = true;
        }else{
            $('#sede').attr('multiple', false);
            sedeMultiple = false;
        }

        $('#sede').select2({
            theme: 'bootstrap4',
            placeholder: 'Seleccione una opción'
        });
        //$('#institucion').trigger('change');
    });


    // Init date modals
    //---------------------------------------------------------------
    $('input[name="departure_date"], input[name="start_exposure"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,
        maxYear: parseInt(moment().format('YYYY'),10),
        locale: {
            format: 'YYYY-MM-DD'
        }
    });

    $('input[name="end_exposure"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,
        maxYear: parseInt(moment().format('YYYY'),10),
        autoUpdateInput: false,
        locale: {
            format: 'YYYY-MM-DD',
            cancelLabel: 'Limpiar'
        }
    });

    $('input[name="end_exposure"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD'));
    });
});

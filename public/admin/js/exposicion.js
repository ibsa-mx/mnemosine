$(function() {
    $("#institucion").change(function(event) {
        $.get('/contactAjax/'+event.target.value+'',function(response, state){
            //console.log(response);
            $('#contacto').empty();
            $('#exposicion').empty();
            $('#contacto').append('<option selected="selected" disabled="disabled">Seleccione una opción</option>');
            for (var i = 0; i<response.length; i++) {
                var nombre = response[i].name + ' ' + ((response[i].last_name != null) ? response[i].last_name : '');
                $('#contacto').append('<option value="'+response[i].id+'">'+nombre+'</option>');
            }
        });
        $.get('/exhibitionAjax/'+event.target.value+'',function(response, state){
            //console.log(response);
            $('#exposicion').empty();
            $('#exposicion').append('<option selected="selected" disabled="disabled">Seleccione una opción </option>');
            for (var i = 0; i<response.length; i++) {
                $('#exposicion').append('<option value="'+response[i].id+'">'+response[i].name+'</option>');
            }
        });
        $.get('/venuesAjax/'+event.target.value+'',function(response, state){
            //console.log(response);
            $('#sede').empty();
            //$('#sede').append('<option selected="selected" disabled="disabled">Seleccione una opción </option>');
            for (var i = 0; i<response.length; i++) {
                $('#sede').append('<option value="'+response[i].id+'">'+response[i].name+'</option>');
            }
        });
    });
});

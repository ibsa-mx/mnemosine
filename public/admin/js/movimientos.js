$( document ).ready(function() {
  $("#country").change(function(event){
        $.get('/stateAjax/'+event.target.value+'',function(response, state){
            $('#state').empty();
            $('#state').append('<option selected="selected" disabled="disabled">Seleccione una opción</option');
            for (var i = 0; i<response.length; i++) {
                $('#state').append('<option value="'+response[i].id+'">'+response[i].name+'</option>');
            }
        });
    });
});

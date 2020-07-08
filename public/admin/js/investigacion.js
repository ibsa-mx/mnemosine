$(function() {
    $('#genero').change(function(event){
        $.get('/investigacionSubgenderAjax/'+event.target.value+'',function(response, state){
            $('#subgenero').empty();
            $('#subgenero').append('<option selected="selected" disabled="disabled">Seleccione una opción</option');
            for (var i = 0; i<response.length; i++) {
                $('#subgenero').append('<option value="'+response[i].id+'">'+response[i].title+'</option>');
            }
        });
    });
});

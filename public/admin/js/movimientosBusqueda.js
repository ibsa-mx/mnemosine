$(function() {
    $('#radio_institution').on('click', function(e){
        $('#select_exhibition').hide();
        $('#select_venue').hide();
        $('#select_institution').show();
    });

    $('#radio_exhibition').on('click', function(e){
        $('#select_exhibition').show();
        $('#select_venue').hide();
        $('#select_institution').hide();
    });

    $('#radio_venue').on('click', function(e){
        $('#select_exhibition').hide();
        $('#select_venue').show();
        $('#select_institution').hide();
    });
});

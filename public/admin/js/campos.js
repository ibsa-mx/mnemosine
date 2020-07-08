$(document).ready(function () {
    var moduloActivo = 1;
    $( ".sortable" ).sortable({
        placeholder: "ui-state-highlight"
    });
    $( ".sortable" ).disableSelection();
    $.widget.bridge('uibutton', $.ui.button);
    $.widget.bridge('uitooltip', $.ui.tooltip);

    $("#modulo").on("change", function(e){
        $(".modulo").addClass("d-none");
        $("#modulo_" + $(this).val()).removeClass("d-none");
        moduloActivo = modulos[$(this).val()];
        console.log(moduloActivo);
    });

    $("#asignarPermisos").on("change", function(e){
        if($(this).prop('checked')){
            $('#verPermisos').removeClass("d-none");
            $('#campoObligatorio').prop('checked', false);
            $('#campoObligatorio').prop('disabled', true);
            $('#campoObligatorioLabel').addClass('text-muted');
        } else{
            $('#verPermisos').addClass("d-none");
            if(!$("#asignarGeneros").prop('checked')){
                $('#campoObligatorio').prop('disabled', false);
                $('#campoObligatorioLabel').removeClass('text-muted');
            }
        }
    });

    $("#asignarGeneros").on("change", function(e){
        if($(this).prop('checked')){
            $('#verGeneros').removeClass("d-none");
            $('#campoObligatorio').prop('checked', false);
            $('#campoObligatorio').prop('disabled', true);
            $('#campoObligatorioLabel').addClass('text-muted');
        } else{
            $('#verGeneros').addClass("d-none");
            if(!$("#asignarPermisos").prop('checked')){
                $('#campoObligatorio').prop('disabled', false);
                $('#campoObligatorioLabel').removeClass('text-muted');
            }
        }
    });
});

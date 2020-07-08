var stepper1 = null;
$(document).ready(function () {
    var i = j = 0;
    //$(".js-example-basic-single").select2({theme: 'bootstrap4'});
    $(".select_filtro").hide();
    $(".select_filtro_una").hide();

    stepper1 = new Stepper(document.querySelector('#stepper1'));
    $('.select2-multiple').select2({ //apply select2 to my element
        allowClear: true
    });

    //Se pone un parent y se le añade  todasAux para guardar un id... esto hace que cada vez que se clone se genere un id diferente para cada campo  y condición... para más adelante filtrar correctamente.

    var todasAux = 0;
    var todasTemplate = $($('#contenedor_todas').html());
    $('#agregar-condicion-todas').click(function(){  
        console.log('ya entro');
        todasAux++;  
        var clonedDiv = todasTemplate.clone().attr('id', 'todas-las-condiciones-' + todasAux);
        clonedDiv.find(".eliminar-todas").data('parent', todasAux); 
        clonedDiv.find(".campo-todas").data('parent', todasAux); 
        clonedDiv.find(".condicion-todas").data('parent', todasAux); 
        $("#nuevas-condiciones-todas").append(clonedDiv);
    }); 

    //muestra los datos de "AGREGAR CONDICIÓN TODAS LA PRIMERA VEZ"
    $('#agregar-condicion-todas').trigger('click');

    $("#nuevas-condiciones-todas").on('click', '.eliminar-todas', function(){ 
        var parentId = $(this).data('parent'); 
      $("#todas-las-condiciones-" + parentId).remove();
    });

    $("#contenedor_todas-1").on('click', '.eliminar-todas', function(){ 
        var parentId = $(this).data('parent'); 
      $("#contenedor_todas-1" + parentId).remove();
    });

    $("#nuevas-condiciones-todas").on('change', '.condicion-todas', function(event){
        var parentId = $(this).data('parent');
        if (($("#todas-las-condiciones-"+ parentId +" .condicion-todas").val() == 'igual') || ($("#todas-las-condiciones-"+ parentId +" .condicion-todas").val() == 'diferente')) 
        {
            $("#todas-las-condiciones-"+ parentId +" .select_filtro").show();
            $("#todas-las-condiciones-"+ parentId +" .filtros-todas").hide();
        }
        else if (($("#todas-las-condiciones-"+ parentId +" .condicion-todas").val() == 'vacio') || ($("#todas-las-condiciones-"+ parentId +" .condicion-todas").val() == 'novacio')) 
        {
            $("#todas-las-condiciones-"+ parentId +" .filtros-todas").hide();
            $("#todas-las-condiciones-"+ parentId +" .select_filtro").hide();
        }
        else{
            $("#todas-las-condiciones-"+ parentId +" .select_filtro").hide();
            $("#todas-las-condiciones-"+ parentId +" .filtros-todas").show();
        }

    });


    $("#nuevas-condiciones-todas").on('change', '.campo-todas', function(event){
        var parentId = $(this).data('parent');
        $.get('/filtrosAjax/'+event.target.value+'',function(response,state){
            $("#todas-las-condiciones-"+ parentId +" .select_filtro").empty();
            //alert(j);
            //console.log(response['type'], response['result'][1].id, response['result'][1].code);
            $("#todas-las-condiciones-"+ parentId +" .select_filtro").append('<option selected="selected" disabled="disabled">--Seleccione una opción--</option>');
            switch(response['type'])
             {
                case 'procedencia':
                    for(var i = 0; i<response['result'].length; i++) {
                        $("#todas-las-condiciones-"+ parentId +" .select_filtro").append('<option value="'+response['result'][i].id+'">'+response['result'][i].id+'. '+response['result'][i].code+'</option>');
                    }
                    break;
                case 'inventario':
                    for(var i = 0; i<response['result'].length; i++) {
                        $("#todas-las-condiciones-"+ parentId +" .select_filtro").append('<option value="'+response['result'][i].id+'">'+response['result'][i].inventory_number+'</option>');
                    }
                    break;
                case 'catalogo':
                    for(var i = 0; i<response['result'].length; i++) {
                        $("#todas-las-condiciones-"+ parentId +" .select_filtro").append('<option value="'+response['result'][i].id+'">'+response['result'][i].catalog_number+'</option>');
                    }
                    break;
                case 'genero':
                case 'subgender':
                case 'ubicacion':
                case 'tipo':
                console.log(response);
                    for(var i = 0; i<response['result'].length; i++) {
                        $("#todas-las-condiciones-"+ parentId +" .select_filtro").append('<option value="'+response['result'][i].id+'">'+response['result'][i].title+'</option>');
                    }
                    break;
                case 'h':
                    for (let prop in response['result']) {
                         $("#todas-las-condiciones-"+ parentId +" .select_filtro").append('<option value="'+prop+'">'+prop+'</option>');
                     }
                    break;
                case 'w':
                    for (let prop in response['result']) {
                         $("#todas-las-condiciones-"+ parentId +" .select_filtro").append('<option value="'+prop+'">'+prop+'</option>');
                     }
                    break;
                case 'd':
                    for (let prop in response['result']) {
                         $("#todas-las-condiciones-"+ parentId +" .select_filtro").append('<option value="'+prop+'">'+prop+'</option>');
                     }
                    break;
                case 'c':
                    for (let prop in response['result']) {
                         $("#todas-las-condiciones-"+ parentId +" .select_filtro").append('<option value="'+prop+'">'+prop+'</option>');
                     }
                    break;
                case 'hb':
                    for (let prop in response['result']) {
                         $("#todas-las-condiciones-"+ parentId +" .select_filtro").append('<option value="'+prop+'">'+prop+'</option>');
                     }
                    break;
                case 'wb':
                    for (let prop in response['result']) {
                         $("#todas-las-condiciones-"+ parentId +" .select_filtro").append('<option value="'+prop+'">'+prop+'</option>');
                     }
                    break;
                case 'db':
                    for (let prop in response['result']) {
                         $("#todas-las-condiciones-"+ parentId +" .select_filtro").append('<option value="'+prop+'">'+prop+'</option>');
                     }
                    break;
                case 'cb':
                    for (let prop in response['result']) {
                         $("#todas-las-condiciones-"+ parentId +" .select_filtro").append('<option value="'+prop+'">'+prop+'</option>');
                     }
                    break;
                case 'descripcion':
                    for(var i = 0; i<response['result'].length; i++) {
                        $("#todas-las-condiciones-"+ parentId +" .select_filtro").append('<option value="'+response['result'][i].id+'">'+response['result'][i].description_origin+'</option>');
                    }
                    break;
                case 'avaluo':
                console.log(response['result']);
                 for (let prop in response['result']) {
                     $("#todas-las-condiciones-"+ parentId +" .select_filtro").append('<option value="'+prop+'">'+prop+'</option>');
                 }

                    break;
                default:
                    alert('no hay información');
                    break;
             }
            
        });
    });  // hasta aquí todo bien... 

    var conunaAux = 0;
    var conunaTemplate = $($('#contenedor_conuna').html());
    $('#agregar-condicion-conuna').click(function(){  
        conunaAux++;  
        var clonedDiv = conunaTemplate.clone().attr('id', 'conuna-las-condiciones-' + conunaAux);
        clonedDiv.find(".eliminar-conuna").data('parent', conunaAux); 
        clonedDiv.find(".campo-conuna").data('parent', conunaAux); 
        clonedDiv.find(".condicion-conuna").data('parent', conunaAux); 
        $("#nuevas-condiciones-conuna").append(clonedDiv);
    }); 

    //muestra los datos de "AGREGAR CONDICIÓN CONUNA LA PRIMERA VEZ"
    $('#agregar-condicion-conuna').trigger('click');

    $("#nuevas-condiciones-conuna").on('click', '.eliminar-conuna', function(){ 
        var parentId = $(this).data('parent'); 
      $("#conuna-las-condiciones-" + parentId).remove();
    });


    $("#nuevas-condiciones-conuna").on('change', '.condicion-conuna', function(event){
        var parentId = $(this).data('parent');
        if (($("#conuna-las-condiciones-"+ parentId +" .condicion-conuna").val() == 'igual') || ($("#conuna-las-condiciones-"+ parentId +" .condicion-conuna").val() == 'diferente')) 
        {
            $("#conuna-las-condiciones-"+ parentId +" .select_filtro_una").show();
            $("#conuna-las-condiciones-"+ parentId +" .filtros-conuna").hide();
        }
        else if (($("#conuna-las-condiciones-"+ parentId +" .condicion-conuna").val() == 'vacio') || ($("#conuna-las-condiciones-"+ parentId +" .condicion-conuna").val() == 'novacio')) 
        {
            $("#conuna-las-condiciones-"+ parentId +" .filtros-conuna").hide();
            $("#conuna-las-condiciones-"+ parentId +" .select_filtro_una").hide();
        }
        else{
            $("#conuna-las-condiciones-"+ parentId +" .select_filtro_una").hide();
            $("#conuna-las-condiciones-"+ parentId +" .filtros-conuna").show();
        }

    });
    


    $("#nuevas-condiciones-conuna").on('change', '.campo-conuna', function(event){
        var parentId = $(this).data('parent');
        $.get('/filtrosAjax/'+event.target.value+'',function(response,state){
            $("#conuna-las-condiciones-"+ parentId +" .select_filtro_una").empty();
            //alert(j);
            //console.log(response['type'], response['result'][1].id, response['result'][1].code);
            $("#conuna-las-condiciones-"+ parentId +" .select_filtro_una").append('<option selected="selected" disabled="disabled">--Seleccione una opción--</option>');
            switch(response['type'])
             {
                case 'procedencia':
                    for(var i = 0; i<response['result'].length; i++) {
                        $("#conuna-las-condiciones-"+ parentId +" .select_filtro_una").append('<option value="'+response['result'][i].id+'">'+response['result'][i].id+'. '+response['result'][i].code+'</option>');
                    }
                    break;
                case 'inventario':
                    for(var i = 0; i<response['result'].length; i++) {
                        $("#conuna-las-condiciones-"+ parentId +" .select_filtro_una").append('<option value="'+response['result'][i].id+'">'+response['result'][i].inventory_number+'</option>');
                    }
                    break;
                case 'catalogo':
                    for(var i = 0; i<response['result'].length; i++) {
                        $("#conuna-las-condiciones-"+ parentId +" .select_filtro_una").append('<option value="'+response['result'][i].id+'">'+response['result'][i].catalog_number+'</option>');
                    }
                    break;
                case 'genero':
                case 'subgender':
                case 'ubicacion':
                case 'tipo':
                console.log(response);
                    for(var i = 0; i<response['result'].length; i++) {
                        $("#conuna-las-condiciones-"+ parentId +" .select_filtro_una").append('<option value="'+response['result'][i].id+'">'+response['result'][i].title+'</option>');
                    }
                    break;
                case 'h':
                    for(var i = 0; i<response['result'].length; i++) {
                        if (response['result'][i].height != null) {
                            $("#conuna-las-condiciones-"+ parentId +" .select_filtro_una").append('<option value="'+response['result'][i].piece_id+'">'+response['result'][i].height+' cm</option>');
                        }
                        if (response['result'][i].height_with_base != null) {
                            $("#conuna-las-condiciones-"+ parentId +" .select_filtro_una").append('<option value="'+response['result'][i].piece_id+'">'+response['result'][i].height_with_base+' cm</option>');
                        }
                    }
                    break;
                case 'w':
                    for(var i = 0; i<response['result'].length; i++) {
                        if (response['result'][i].width != null) {
                            $("#conuna-las-condiciones-"+ parentId +" .select_filtro_una").append('<option value="'+response['result'][i].piece_id+'">'+response['result'][i].width+' cm</option>');
                        }
                        if (response['result'][i].width_with_base != null) {
                            $("#conuna-las-condiciones-"+ parentId +" .select_filtro_una").append('<option value="'+response['result'][i].piece_id+'">'+response['result'][i].width_with_base+' cm</option>');
                        }
                    }
                    break;
                case 'd':
                    for(var i = 0; i<response['result'].length; i++) {
                        if (response['result'][i].depth != null) {
                            $("#conuna-las-condiciones-"+ parentId +" .select_filtro_una").append('<option value="'+response['result'][i].piece_id+'">'+response['result'][i].depth+' cm</option>');
                        }
                        if (response['result'][i].depth_with_base != null) {
                            $("#conuna-las-condiciones-"+ parentId +" .select_filtro_una").append('<option value="'+response['result'][i].piece_id+'">'+response['result'][i].depth_with_base+' cm</option');
                        }
                    }
                    break;
                case 'c':
                    for(var i = 0; i<response['result'].length; i++) {
                        if (response['result'][i].diameter != null) {
                            $("#conuna-las-condiciones-"+ parentId +" .select_filtro_una").append('<option value="'+response['result'][i].piece_id+'">'+response['result'][i].diameter+' cm</option>');
                        }
                        if (response['result'][i].diameter_with_base != null) {
                            $("#conuna-las-condiciones-"+ parentId +" .select_filtro_una").append('<option value="'+response['result'][i].piece_id+'">'+response['result'][i].diameter_with_base+' cm</option>');
                        }
                    }
                    break;
                case 'descripcion':
                    for(var i = 0; i<response['result'].length; i++) {
                        $("#conuna-las-condiciones-"+ parentId +" .select_filtro_una").append('<option value="'+response['result'][i].id+'">'+response['result'][i].description_origin+'</option');
                    }
                    break;
                case 'avaluo':

                console.log(response['result']);
                 for (let prop in response['result']) {
                     $("#conuna-las-condiciones-"+ parentId +" .select_filtro_una").append('<option value="'+prop+'">'+prop+'</option>');
                 }

                    break;
                default:
                    alert('no hay información');
                    break;
             }
            
        });
    });

    if(($('#modulo').val() != '') && ($('#modulo').val() != null))
    {
        $('#btn-sig1').attr('disabled', false); 
        console.log($('#modulo').val());
    }
    if(($('#columnas').val() != '') && ($('#columnas').val() != null))
    {
        $('#btn-sig2').attr('disabled', false);
        //console.log('hola soy las columnas'); //si entran las
        console.log($('#columnas').val());
    }
    if(($('#todas_num').val() != '') || ($('#todas_num').val() != null))
    {
        var temp_todas = $('#todas_num').val();
        $todasAux = $('#todas_num').val();
        console.log('soy aux');
        console.log($todasAux);
        console.log($('#todas_num').val());
    }

});


$(document).on('change', '#modulo', function()
 {
    var1 = $(this).val();
    var2 = $("#name").val();
    if((var2 && var1) !=""){
        $('#btn-sig1').attr('disabled', false); 
    }else{
        $('#p').text("LLenar los campos correspondientes  *");
    } 

});



$(document).on('change', '#columnas', function()
{
    var3 = $(this).val();
    if (var3 !="") {
        $('#btn-sig2').attr('disabled', false);
    }

});



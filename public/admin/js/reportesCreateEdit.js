$(document).ready(function () {
    var columnasSelect = $('select[name="columnas[]"]').bootstrapDualListbox({
        nonSelectedListLabel: 'No mostrar en el reporte',
        selectedListLabel: 'Mostrar en el reporte',
        moveAllLabel: 'Mover todas',
        filterPlaceHolder: 'Filtrar',
        removeAllLabel: 'Quitar todas',
        selectorMinimalHeight: 200,
        infoText: '{0} columnas',
        infoTextFiltered: '<span class="badge badge-warning">Filtrados</span> {0} de {1}',
        infoTextEmpty: '0 columnas',
        filterTextClear: 'Mostrar todos',
    });

    var el = document.getElementById('ordenColumnas');
    var sortable = Sortable.create(el, {
        ghostClass: "active",
        onChange: function (/**Event*/evt) {
    		$("#columnasOrdenadas").val(sortable.toArray());
        },
    });

    $('#columnas').on("change", function(e){
        $("#ordenColumnas").empty();
        $("#columnas option:selected").each(function(index){
            $("#ordenColumnas").append('<li data-id="'+ $(this).val() +'" class="list-group-item list-group-item-secondary"><i class="fas fa-arrows-alt"></i> '+ $(this).text().trim() +'</li>');
        });
        $("#columnasOrdenadas").val(sortable.toArray());
    });

    $("#chk_custom_order").on("change", function(){
        $("#ordenColumnas").toggle();
    });


    var i = j = 0;
    $(".select_filtro").hide();
    $(".select_filtro_una").hide();

    stepper1 = new Stepper(document.querySelector('#stepper1'));

    $("#radio_select_custom").on("click", function(){
        $("#content_select_custom").show();
    });

    $("#radio_select_all_except").on("click", function(){
        $("#content_select_custom").show();
    });

    $("#radio_select_all").on("click", function(){
        $("#content_select_custom").hide();
    });

    $("#chk_lending_list").on("change", function(){
        $("#div_lending_list").toggle();
    });

    $('#btn-sig1, #btn-sig1-alt').click(function(event){
        if($('#reportName').val() == ''){
            $('#reportName').focus();
            toastr.error("Por favor ingrese un nombre para el reporte");
            return;
        }
        if($('#columnas').val() == ''){
            $('#columnas').focus();
            toastr.error("Por favor seleccione al menos una columna para el reporte");
            return;
        }
        stepper1.next();
    });

    $('#btn-sig2').click(function(event){
        stepper1.next();
    });

    $('input[name="exhibition_date_ini"], input[name="exhibition_date_fin"]').daterangepicker({
		singleDatePicker: true,
		showDropdowns: true,
		minYear: 1901,
		maxYear: parseInt(moment().format('YYYY'),10),
		locale: {
			format: 'YYYY-MM-DD'
		}
	});

    // $('#btn-generar-reporte, #btn-generar-reporte-alt').click(function(event){
    //
    // });

    $("#frmReporte").submit(function(event){
        if($("#radio_select_custom").is(':checked') || $("#radio_select_all_except").is(':checked')){
            if($('#piezas_id').val() == ''){
                event.preventDefault();
                toastr.error("Por favor seleccione al menos una pieza para el reporte");
                return;
            }
            var countUnSelected = $("#piezas_id option:not(:selected)[value!='']").length;
            if(countUnSelected > 0){
                var countSelected = $("#piezas_id option:selected[value!='']").length;
                if(!confirm("Se cargarán " + countSelected + " piezas, y " + countUnSelected + " serán eliminadas, ¿deseas continuar?")){
                    event.preventDefault();
                    return;
                }
            }
        }

        $("#btn-generar-reporte").prop('disabled', true);
        $("#btn-generar-reporte-alt").prop('disabled', true);
        $("#hidden_pieces_ids").val($("#piezas_id").val().join());
        $("#piezas_id").remove();
        $('#dataTableBuilder input[type="checkbox"]').remove();
    });

    $('#btn-cargar-piezas').click(function(event) {
        $('.p_id').each(function(index, el) {
            if ($(el).prop('checked') && $(el).data("loaded") != 1){
                var elId = $(el).prop('id').split("_");
                $('#piezas_id').append('<option selected="selected" value="'+ elId[1] +'">'+ $(el).data("inventory-number") + " / " + $(el).data("catalog-number") +'</option>');
                $(el).data("loaded", 1);
            }
        });
        $("#piezas_id").trigger("change");
    });

    window.LaravelDataTables["dataTableBuilder"].on('draw', function () {
		$('input[name="pieces_id[]"]').each(function( index ) {
            var elId = $(this).prop('id').split("_");
			if($.inArray(elId[1], $("#piezas_id").val()) >= 0){
                $(this).prop("checked", true);
                $(this).data("loaded", 1);
            }
		});
        $("#selectAll").prop('checked', false);
	});

    $(document).on("change", "#selectAll", function() {
        $('input[name="pieces_id[]"]').prop('checked', $("#selectAll").is(':checked'));
	});

    $(document).on("change", "#piezas_id", function() {
		$("#piezasCargadas").html($("#piezas_id :selected").length);
	});

    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox({
            alwaysShowClose: true,
            showArrows: true,
            onContentLoaded: function(ev) {
                setTimeout(function() {
                    var toAppend = $('<a href="'+ $('.ekko-lightbox').find('.img-fluid').prop('src') +'" class="btn btn-success mr-2" download><i class="fas fa-download"></i></a>');
                    $('.ekko-lightbox').find('.modal-footer').prepend(toAppend);
                }, 1000);
            }
        });
    });
});

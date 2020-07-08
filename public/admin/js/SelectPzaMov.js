$(function() {
	$(document).on("change", "#selectAll", function() {
        $('input[name="pieces_id[]"]').prop('checked', $("#selectAll").is(':checked'));
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

	$(document).on("change", "#piezas_id", function() {
		$("#piezasCargadas").html($("#piezas_id :selected").length);
	});

	$("#frmMovimientop2").submit(function(event){
        if($('#piezas_id').val() == ''){
            event.preventDefault();
            toastr.error("Por favor seleccione al menos una pieza");
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

        $("#guardarMovimiento").prop('disabled', true);
        $("#hidden_pieces_ids").val($("#piezas_id").val().join());
        $("#piezas_id").remove();
        $('#dataTableBuilder input[type="checkbox"]').remove();
    });

	window.LaravelDataTables["dataTableBuilder"].on('draw', function () {
		$('input[name="pieces_id[]"]').each(function( index ) {
			if($(this).data("indeterminate") == '1'){
				$(this).prop('indeterminate', true);
				$(this).prop('disabled', true);
			}
		});
		$("#selectAll").prop('checked', false);
	});
});

$(document).ready(function() {
	// $("#frmReportecd1").on('submit', function (e) {
	// 	if(!$('input[name="check_p[]"]').prop("checked")){
	// 		toastr.warning("Debe seleccionar al menos una pieza para generar la cédula");
	// 		return false;
	// 	}
	// });

	if ($('#check_all').prop('checked')) {
		$('.check_p').prop('checked', true);
	}

	$('#check_all').change(function(event) {
		if ($('#check_all').prop('checked')) {
			$('.check_p').prop('checked', true);
		}
		else{
			$('.check_p').prop('checked', false);
		}
	});

	$('.check_p').change(function(event) {
		if ($("#check_all").prop('checked')) {
			$("#check_all").prop('checked', false);
		}
		if ($(this).prop('checked') === false) {

			$(this).prop('checked', false);
			console.log('cambie a apagado');
		  }
		  else{
			$(this).prop('checked', true);
			console.log('cambie a encendido');
		  }
	});

});

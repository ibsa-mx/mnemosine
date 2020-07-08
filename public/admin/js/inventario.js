$(function() {
	var confirmedAppraisalChange = false;
	$("#frmPieza").on("submit", function(event){
		if($('input[name="appraisal"]').val() != $('input[name="current_appraisal"]').val() && !confirmedAppraisalChange){
			// se modifico el avaluo
			event.preventDefault();
			$('#confirmAppraisal').modal('show');
			return false;
		}
	});
	$("#btnConfirmAppraisal").on("click", function(e){
		if(!$('input[name="chkConfirmAppraisal"]').prop('checked')){
			// en caso de que no, se regresa al valor original
			$('input[name="appraisal"]').val($('input[name="current_appraisal"]').val());
		} else{
			confirmedAppraisalChange = true;
		}
		$("#frmPieza").trigger("submit");

	});

	$('#genero').change(function(event){
        $.get('/subgenderAjax/'+event.target.value+'',function(response, state){
            //console.log(response);
            $('#subgenero').empty();
            $('#subgenero').append('<option selected="selected" disabled="disabled">Seleccione una opción</option');
            for (var i = 0; i<response.length; i++) {
                $('#subgenero').append('<option value="'+response[i].id+'">'+response[i].title+'</option>');
            }
        });
    });

	$('input[name="admitted_at"]').daterangepicker({
		singleDatePicker: true,
		showDropdowns: true,
		minYear: 1901,
		maxYear: parseInt(moment().format('YYYY'),10),
		locale: {
			format: 'YYYY-MM-DD'
		}
	});

	$('input[name="base_or_frame"]').on('change', function(e){
		if (this.value == 'base') {
			$(".label-measure-type").text("base");
		} else if(this.value == 'frame'){
			$(".label-measure-type").text("marco");
		}
	});
});

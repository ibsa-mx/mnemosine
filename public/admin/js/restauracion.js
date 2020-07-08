$(function() {
	$('input[name="treatment_date"]').daterangepicker({
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
	})
});

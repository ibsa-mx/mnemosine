$(function() {
    $("#selectAll").on("change", function(){
        $('input[name="check_p[]"]').prop('checked', $(this).is(':checked'));
    });

    $('#btn-cargar-piezas').click(function(event) {
        $('input[name="check_p[]"]').each(function(index, el) {
            if ($(el).prop('checked') && $(el).data("loaded") != 1){
                var elId = $(el).prop('id').split("_");
                $('#piezas_id').append('<option selected="selected" value="'+ elId[1] +'">'+ $(el).data("inventory-number") + " / " + $(el).data("catalog-number") +'</option>');
                $(el).data("loaded", 1);
                //$('#selectAll').prop('checked', false);
            }
        });
    });

    // DataTables
    var table = $('#data-table-report').on( 'init.dt', function (e, settings, json) {
        table.buttons().container().appendTo('#data-table-report_wrapper .col-md-6:eq(0)');
        $("#data-table-report_wrapper .btn").removeClass("btn-secondary").addClass("btn-outline-primary");
        $("#data-table-report_wrapper .btn").each(function(index, element){
            $(this).attr("title", $(this).find('i').attr("title"));
            $(this).tooltip();
            $(this).find('i').attr("title", "");
        });
        $('#data-table-report_wrapper .col-md-6:eq(0)').after(`
            <div class="col-sm-12 col-md-3 mb-1">
            <div class="input-group border border-primary rounded">
            <div class="input-group-prepend">
            <button class="btn btn-primary" id="recordsShow">Ver</button>
            </div>
            <input type="number" class="form-control text-center" id="recordsNumber" value="200"/>
            <div class="input-group-append">
            <span class="input-group-text">filas</span>
            </div>
            </div>
            </div>`);
            $('#data-table-report_wrapper .col-md-6:eq(0)').removeClass("col-md-6").addClass("col-md-5");
            $('#data-table-report_wrapper .col-md-6:eq(0)').removeClass("col-md-6").addClass("col-md-4 mt-1");
            $('#data-table-report_wrapper input[type="search"]').removeClass("form-control-sm").addClass("border border-primary");

            // paginacion
            var pageInfo = table.page.info();
            $('#recordsShow').on('click', function () {
                table.page.len($("#recordsNumber").val()).draw();
            } );

            $("#recordsNumber").on('keyup', function (e) {
                // 38 = up arrow, 39 = right arrow
                if (e.which === 39) {
                    this.value++;
                }
                // 37 = left arrow, 40 = down arrow
                else if (e.which === 37 && this.value > 1) {
                    e.preventDefault();
                    this.value--;
                    // 13 = enter
                } else if(e.which === 13){
                    $("#recordsShow").trigger("click");
                }
                if(this.value < 1){
                    this.value = 1;
                } else if(this.value > pageInfo.recordsTotal){
                    this.value = pageInfo.recordsTotal;
                }
            });
        } ).DataTable( {
            lengthChange: false,
            pageLength: 200,
            stateSave: false,
            pagingType: "input",
            columnDefs: [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 2, targets: -1 },
                { targets: 0, className: 'noVis' },
                { targets: -1, searchable: false, printable: false, exportable: false },
                { targets: -2, searchable: false, printable: false, exportable: false },
            ],
            buttons: [ 'excel', 'pdf', 'copy', {
                extend: 'print',
                autoPrint: true
            }, {
                extend: 'colvis',
                columns: ':not(.noVis)',
                postfixButtons: [ 'colvisRestore' ]
            } ],
            "language": {
                "url": "/admin/vendors/DataTables/Spanish.json",
                buttons: {
                    colvis: '<i class="fas fa-columns" title="Mostrar u ocultar columnas"></i>',
                    print: '<i class="fas fa-print" title="Imprimir"></i>',
                    excel: '<i class="far fa-file-excel" title="Exportar a Excel"></i>',
                    colvisRestore: '<b>Restaurar</b>',
                    copy: '<i class="far fa-clipboard" title="Copiar al portapapeles"></i>',
                    pdf: '<i class="far fa-file-pdf" title="Exportar a PDF"></i>',
                    copyTitle: 'Copiado al portapapeles',
                    copyKeys: 'Presione <i>ctrl</i> + <i>C</i> para copiar los datos de la tabla al portapapeles. <br> <br> Para cancelar, haga clic en este mensaje o presione Esc.',
                    copySuccess: {
                        _: '%d filas se han copiado',
                        1: 'Una fila se ha copiado'
                    }
                }
            },
        } );

        window.LaravelDataTables["dataTableBuilder"].on('draw', function () {
            $('#selectAll').prop('checked', false);
    		$('input[name="check_p[]"]').each(function( index ) {
                var elId = $(this).prop('id').split("_");
    			if($.inArray(elId[1], $("#piezas_id").val()) >= 0){
                    $(this).prop("checked", true);
                    $(this).data("loaded", 1);
                }
    		});
    	});
});

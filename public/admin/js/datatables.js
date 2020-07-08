var table = $('#data-table, #example').on( 'init.dt', function (e, settings, json) {
    table.buttons().container().appendTo('#data-table_wrapper .col-md-6:eq(0)');
    $("#data-table_wrapper .btn").removeClass("btn-secondary").addClass("btn-outline-primary");
    $("#data-table_wrapper .btn").each(function(index, element){
        $(this).attr("title", $(this).find('i').attr("title"));
        $(this).tooltip();
        $(this).find('i').attr("title", "");
    });
    $('#data-table_wrapper .col-md-6:eq(0)').after(`
        <div class="col-sm-12 col-md-3 mb-1">
            <div class="input-group border border-primary rounded">
                <div class="input-group-prepend">
                    <button class="btn btn-primary" id="recordsShow">Ver</button>
                </div>
                <input type="number" class="form-control text-center" id="recordsNumber" value="10"/>
                <div class="input-group-append">
                    <span class="input-group-text">filas</span>
                </div>
            </div>
        </div>`);
    $('#data-table_wrapper .col-md-6:eq(0)').removeClass("col-md-6").addClass("col-md-5");
    $('#data-table_wrapper .col-md-6:eq(0)').removeClass("col-md-6").addClass("col-md-4 mt-1");
    $('#data-table_wrapper input[type="search"]').removeClass("form-control-sm").addClass("border border-primary");

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
    pageLength: 10,
    stateSave: false,
    pagingType: "input",
    columnDefs: [
        { responsivePriority: 1, targets: 0 },
        { responsivePriority: 2, targets: -1 },
        { targets: 0, className: 'noVis' },
        { targets: 1, className: 'noVis' },
        { targets: -1, className: 'noVis', searchable: false },
        { targets: -2, searchable: false },
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
        "url": "{{ asset('admin/vendors/DataTables/Spanish.json') }}",
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

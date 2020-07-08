(function(window,$){window.LaravelDataTables=window.LaravelDataTables||{};window.LaravelDataTables["%1$s"]=$("#%1$s").on( 'init.dt', function (e, settings, json) {
    window.LaravelDataTables["%1$s"].buttons().container().appendTo('#dataTableBuilder_wrapper .col-md-6:eq(0)');
    $("#dataTableBuilder_wrapper .btn").removeClass("btn-secondary").addClass("btn-outline-primary");
    $("#dataTableBuilder_wrapper .btn").each(function(index, element){
        $(this).attr("title", $(this).find('i').attr("title"));
        $(this).tooltip();
        $(this).find('i').attr("title", "");
    });
    if(window.LaravelDataTables["%1$s"].page.len() > 0){
        $('#dataTableBuilder_wrapper .col-md-6:eq(0)').after(`
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
        $('#dataTableBuilder_wrapper .col-md-6:eq(0)').removeClass("col-md-6").addClass("col-md-5");
        $('#dataTableBuilder_wrapper .col-md-6:eq(0)').removeClass("col-md-6").addClass("col-md-4 mt-1");
        $('#dataTableBuilder_wrapper input[type="search"]').removeClass("form-control-sm").addClass("border border-primary");
        $('#dataTableBuilder_wrapper input[type="search"]').prop('placeholder', 'Palabras clave');
        $('#dataTableBuilder_wrapper input[type="search"]').prop('title', '<i class="fas fa-question text-danger"></i> Ayuda');
        $('#dataTableBuilder_wrapper input[type="search"]').data('toggle', 'popover');
        $('#dataTableBuilder_wrapper input[type="search"]').data('trigger', 'focus');
        $('#dataTableBuilder_wrapper input[type="search"]').data('content', 'Si desea ampliar la búsqueda puede usar los comodines: <br><b class="text-primary">&#37;</b> sustituye a 1 o varios caracteres<br><b class="text-primary">_</b> &nbsp;&nbsp;sustituye a 1 solo caracter');
        $('#dataTableBuilder_wrapper input[type="search"]').data('html', true);
        $('#dataTableBuilder_wrapper input[type="search"]').popover();


        var pageInfo = window.LaravelDataTables["%1$s"].page.info();
        $('#recordsShow').on('click', function (e) {
            e.preventDefault();
            window.LaravelDataTables["%1$s"].page.len($("#recordsNumber").val()).draw();
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
                e.preventDefault();
                return false;
            }
            if(this.value < 1){
                this.value = 1;
            } else if(this.value > pageInfo.recordsTotal){
                this.value = pageInfo.recordsTotal;
            }
        });

        $(document).on('keyup keypress keydown', function (e) {
            if(e.which === 13){
                e.preventDefault();
                return false;
            }
        });
    }

    } ).DataTable(%2$s);})(window,jQuery);

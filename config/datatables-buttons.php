<?php

return [
    /*
     * Namespaces used by the generator.
     */
    'namespace' => [
        /*
         * Base namespace/directory to create the new file.
         * This is appended on default Laravel namespace.
         * Usage: php artisan datatables:make User
         * Output: App\DataTables\UserDataTable
         * With Model: App\User (default model)
         * Export filename: users_timestamp
         */
        'base' => 'DataTables',

        /*
         * Base namespace/directory where your model's are located.
         * This is appended on default Laravel namespace.
         * Usage: php artisan datatables:make Post --model
         * Output: App\DataTables\PostDataTable
         * With Model: App\Post
         * Export filename: posts_timestamp
         */
        'model' => '',
    ],

    /*
     * Set Custom stub folder
     */
    //'stub' => '/resources/custom_stub',

    /*
     * PDF generator to be used when converting the table to pdf.
     * Available generators: excel, snappy
     * Snappy package: barryvdh/laravel-snappy
     * Excel package: maatwebsite/excel
     */
    'pdf_generator' => 'snappy',

    /*
     * Snappy PDF options.
     */
    'snappy' => [
        'options' => [
            'no-outline'    => true,
            'margin-left'   => '0',
            'margin-right'  => '0',
            'margin-top'    => '10mm',
            'margin-bottom' => '10mm',
        ],
        'orientation' => 'landscape',
    ],

    /*
     * Default html builder parameters.
     */
    'parameters' => [
        'pagingType' => "input",
        'lengthChange' => false,
        //'ordering' => false,
        'pageLength' => 10,
        'searchDelay' => 1000,
        'stateSave' => true,
        'buttons' => [
            'excel',
            'copy',
            'print',
            'reset',
            //'reload',
            [
                'extend' => 'colvis',
                'columns' => ':not(.noVis)',
                'postfixButtons' => [ 'colvisRestore' ]
            ]
        ],
        'columnDefs' => [
            [ 'targets' => 0, 'className' => 'noVis' ],
            [ 'targets' => 0, 'responsivePriority' => 1 ],
            [ 'targets' => -1, 'className' => 'noVis actions' ],
            [ 'targets' => [-1, -2], 'responsivePriority' => 2 ],
            [ 'targets' => [-2, -3, -4, -5], 'className' => 'picture-list' ],
            [ 'targets' => [5, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25], 'visible' => false ],
        ],
        'initComplete' => "function () {
            this.api().columns().every(function () {
                var column = this;
                if($(column.footer()).attr('class') == 'noVis actions'){
                    var button = document.createElement(\"button\");
                    $(button).html('<i class=\"fas fa-broom\"></i>');
                    $(button).addClass('btn btn-sm btn-outline-dark');
                    $(button).attr('title', 'Quitar filtros');
                    $(button).appendTo($(column.footer()).empty())
                    .on('click', function () {
                        $(\".buttons-reset\").trigger('click');
                    });
                } else{
                    var input = document.createElement(\"input\");
                    $(input).appendTo($(column.footer()).empty())
                    .on('keyup change clear', function () {
                        column.search($(this).val(), false, false, true).draw();
                    });
                }
            });
        }",
        "language" => [
            "url" => "/admin/vendors/DataTables/Spanish.json",
            'buttons' => [
                'colvis' => '<i class="fas fa-eye" title="Mostrar u ocultar columnas"></i>',
                'colvisRestore' => '<b>Restaurar columnas</b>',
                'copyTitle' => 'Copiado al portapapeles',
                'copyKeys' => 'Presione <i>ctrl</i> + <i>C</i> para copiar los datos de la tabla al portapapeles. <br> <br> Para cancelar, haga clic en este mensaje o presione Esc.',
                'copySuccess' => [
                    '_' => '%d filas se han copiado',
                    '1' => 'Una fila se ha copiado'
                ]
            ]
        ]
    ],
];

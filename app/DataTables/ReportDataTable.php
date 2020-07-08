<?php

namespace Mnemosine\DataTables;

use Mnemosine\Piece;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Carbon;
use Mnemosine\DataTables\DataArrayTransformer;

class ReportDataTable extends DataTable
{
    private $pieces_ids;
    private $columns;
    private $columnsSelected;
    private $report;

    /**
     * Custom Export class handler.
     *
     * @var string
     */
    protected $exportClass = DataTablesExportExcel::class;

    /**
     * Available button actions. When calling an action, the value will be used
     * as the function name (so it should be available)
     *
     * @var array
     */
    protected $actions = ['print', 'csv', 'excel', 'pdf', 'word'];

    /**
     * Word export type writer.
     *
     * @var string
     */
    protected $wordWriter = 'Docx';

    /**
    * Pops last column from $multiArray and return poped column like an array
    * @param array $multiArray
    * @return array
    */
    protected function multi_array_search(&$multiArray, $key){
        $tmpArray = array();
        foreach ($multiArray as $idx => $childrenArray) {
            $tmpArray[] = $childrenArray[$key];
            //unset($multiArray[$key][array_key_last($childrenArray)]);
        }
        return $tmpArray;
    }

    /**
     * Export results to Word file.
     *
     * @return void
     */
    public function word()
    {
        $ext = '.' . strtolower($this->wordWriter);
        $data = $this->getDataForExport();

        if(is_array($data)){
            $this->buildWordFile($data, $ext);
        }
    }

    protected function buildWordFile($data, $ext){
        set_time_limit(0);
        $headers = array_keys($data[0]);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $sectionStyle = array(
            'orientation' => 'landscape',
            'marginTop' => 720,
            'marginLeft' => 720,
            'marginRight' => 720,
            'marginBottom' => 720,
            'pageSizeW' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(11),
            'pageSizeH' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(8.5)
        );
        $section = $phpWord->addSection($sectionStyle);

        $tableStyle = array(
            'borderColor' => '000000',
            'borderSize'  => 6,
            'cellMargin'  => 50
        );
        $firstRowStyle = array('bgColor' => 'C10007', 'color' => 'FFFFFF');
        $phpWord->addTableStyle('obras', $tableStyle, $firstRowStyle);
        $table = $section->addTable('obras');

        // se ponen los encabezados
        $table->addRow();
        foreach ($headers as $key => $header) {
            $table->addCell(1440)->addText($header, array('size' => 11, 'bold' => true));
        }

        // se agregan los datos
        foreach ($data as $fila) {
            $table->addRow();
            foreach ($fila as $key => $value) {
                switch ($key) {
                    case 'Avalúo':
                        $value = "$" . number_format($value, 2, '.', ',');
                        $table->addCell(1440)->addText($value);
                        break;
                    case 'Foto de investigación':
                    case 'Foto de inventario':
                        if(!empty($value)){
                            $imageUrl = public_path($value);
                        } else{
                            $imageUrl = public_path("/storage/inventario/blank.png");
                        }
                        $table->addCell(1440)->addImage($imageUrl);
                        break;
                    default:
                        $value = str_replace("\n", '</w:t><w:br/><w:t>', htmlspecialchars($value));
                        $table->addCell(1440)->addText($value);
                        break;
                }
            }
        }

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . preg_replace("/[^a-zA-Z0-9]+/", "-", $this->getFilename()) . $ext . '"');
        $objWriter->save('php://output');
    }



    /*
    * BEGIN OF
    * LARAVEL DATATABLES METHODS CUSTOMIZED
    */
    protected function buildExcelFile()
    {
        set_time_limit(0);
        $dataForExportArr = $this->getDataForExport();

        // add column with consecutive number
        foreach($dataForExportArr as $key => $data){
            $dataForExportArr[$key] = array_reverse($dataForExportArr[$key], true);
            $dataForExportArr[$key]["No."] = (string)($key+1);
            $dataForExportArr[$key] = array_reverse($dataForExportArr[$key], true);
        }

        $photoInventory = $photoResearch = array();
        $photoInventoryPostion = $photoResearchPostion = -1;
        $tmpArray = $dataForExportArr[array_key_first($dataForExportArr)];

        if(array_key_exists('Foto de inventario', $tmpArray)){
            $photoInventory = $this->multi_array_search($dataForExportArr, 'Foto de inventario');
            $photoInventoryPostion = array_search('Foto de inventario', array_keys($tmpArray));
        }
        if(array_key_exists('Foto de investigación', $tmpArray)){
            $photoResearch = $this->multi_array_search($dataForExportArr, 'Foto de investigación');
            $photoResearchPostion = array_search('Foto de investigación', array_keys($tmpArray));
        }

        return new $this->exportClass($dataForExportArr, $photoInventory, $photoResearch, $photoInventoryPostion, $photoResearchPostion, $this->report);
    }

    protected function getDataForExport()
    {
        $columns = $this->exportColumns();

        return $this->mapResponseToColumns($columns, 'exportable');
    }

    private function exportColumns()
    {
        return is_array($this->exportColumns) ? $this->toColumnsCollection($this->exportColumns) : $this->getExportColumnsFromBuilder();
    }

    protected function mapResponseToColumns($columns, $type)
    {
        $transformer = new DataArrayTransformer;
        $arrMap = array_map(function ($row) use ($columns, $type, $transformer) {
            return $transformer->transform($row, $columns, $type);
        }, $this->getAjaxResponseData());

        return array_map(function ($row) use ($columns, $type, $transformer) {
            return $transformer->transform($row, $columns, $type);
        }, $this->getAjaxResponseData());
    }
    /*
    * END OF
    * LARAVEL DATATABLES METHODS CUSTOMIZED
    */







    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $module = ['name' => 'inventario', 'id' => '4', 'nameEN' => 'inventory'];
        $moduleName = 'inventario';

        $dataTable = datatables($query)
            // ->editColumn('appraisal', function (Piece $piece) {
            //         return "$" . number_format($piece->appraisal, 2, ".", ",");
            //     })
            ->editColumn('tags', 'datatables.tags')
            ->editColumn('inventory_number', function (Piece $piece) use ($moduleName) {
                    $yesterday = Carbon::now()->subHours(24)->timestamp;
                    return view('datatables.inventory_number', compact('piece', 'yesterday', 'moduleName'));
                })
            ->editColumn('measure_without', function (Piece $piece) use ($moduleName) {
                    return view('datatables.measure_without', compact('piece'));
                })
            ->editColumn('measure_with', function (Piece $piece) use ($moduleName) {
                    return view('datatables.measure_with', compact('piece'));
                })
            ->editColumn('research.title', function (Piece $piece) use ($moduleName) {
                    return (isset($piece->research['title'])) ? $piece->research['title'] : '';
                })
            ->editColumn('research.technique', function (Piece $piece) use ($moduleName) {
                    return (isset($piece->research['technique'])) ? $piece->research['technique'] : '';
                })
            ->editColumn('research.materials', function (Piece $piece) use ($moduleName) {
                    return (isset($piece->research['materials'])) ? $piece->research['materials'] : '';
                })
            ->editColumn('research.acquisition_form', function (Piece $piece) use ($moduleName) {
                    return (isset($piece->research['acquisition_form'])) ? $piece->research['acquisition_form'] : '';
                })
            ->editColumn('research.acquisition_source', function (Piece $piece) use ($moduleName) {
                    return (isset($piece->research['acquisition_source'])) ? $piece->research['acquisition_source'] : '';
                })
            ->editColumn('research.acquisition_date', function (Piece $piece) use ($moduleName) {
                    return (isset($piece->research['acquisition_date'])) ? $piece->research['acquisition_date'] : '';
                })
            ->editColumn('research.firm_description', function (Piece $piece) use ($moduleName) {
                    return (isset($piece->research['firm_description'])) ? $piece->research['firm_description'] : '';
                })
            ->editColumn('research.short_description', function (Piece $piece) use ($moduleName) {
                    return (isset($piece->research['short_description'])) ? $piece->research['short_description'] : '';
                })
            ->editColumn('research.formal_description', function (Piece $piece) use ($moduleName) {
                    return (isset($piece->research['formal_description'])) ? $piece->research['formal_description'] : '';
                })
            ->editColumn('research.observation', function (Piece $piece) use ($moduleName) {
                    return (isset($piece->research['observation'])) ? $piece->research['observation'] : '';
                })
            ->editColumn('research.publications', function (Piece $piece) use ($moduleName) {
                    return (isset($piece->research['publications'])) ? $piece->research['publications'] : '';
                })
            ->editColumn('research.card', function (Piece $piece) use ($moduleName) {
                    return (isset($piece->research['card'])) ? $piece->research['card'] : '';
                })
            ->editColumn('location.name', function (Piece $piece) use ($moduleName) {
                    $html = "";
                    if (isset($piece->location_id)){
                        if ($piece->location_id == 0){
                            $html = "<em class='text-danger'>En prestamo</em>";
                        } elseif (isset($piece->location->name)) {
                            $html = $piece->location->name;
                        }
                    }
                    return $html;
                })
            ->editColumn('research.authors', function (Piece $piece) use ($moduleName) {
                    $html = "";
                    if (isset($piece->research->author_ids) && !is_null($piece->research->author_ids)) {
                        $html = implode(" ", $piece->research->authors);
                    }
                    return $html;
                })
            ->editColumn('research.period.title', function (Piece $piece) use ($moduleName) {
                    $html = "";
                    if (isset($piece->research->period->title) && !is_null($piece->research->period_id)) {
                        $html = $piece->research->period->title;
                    }
                    return $html;
                })
            ->editColumn('research.creation_date', function (Piece $piece) use ($moduleName) {
                    $html = "";
                    if (isset($piece->research->creation_date) && !is_null($piece->research->creation_date)) {
                        $html = $piece->research->creation_date;
                    }
                    return $html;
                })
            ->editColumn('research.set.title', function (Piece $piece) use ($moduleName) {
                    $html = "";
                    if (isset($piece->research->set->title) && !is_null($piece->research->set_id)) {
                        $html = $piece->research->set->title;
                    }
                    return $html;
                })
            ->editColumn('research.place_of_creation.title', function (Piece $piece) use ($moduleName) {
                    $html = "";
                    if (isset($piece->research->place_of_creation->title) && !is_null($piece->research->place_of_creation_id)) {
                        $html = $piece->research->place_of_creation->title;
                    }
                    return $html;
                })
            ->editColumn('gender.title', function (Piece $piece) use ($moduleName) {
                    return $piece->gender["title"];
                })
            ->editColumn('subgender.title', function (Piece $piece) use ($moduleName) {
                    return $piece->subgender["title"];
                });
        $dataTable->editColumn('photo_research', function (Piece $piece) {
                $photographs = $piece->photography;
                $module = ['name' => 'investigacion', 'id' => '2', 'nameEN' => 'research'];
                return view('datatables.photographs', compact('photographs', 'module'));
            });
        $dataTable->editColumn('photo_inventory', function (Piece $piece) {
                $photographs = $piece->photography;
                return view('datatables.photographs_inventory', compact('photographs'));
            });

        $dataTable->addColumn('checkbox', function (Piece $piece) {
            return view('datatables.action_reportes', compact('piece'));
        });

        /*$dataTable->filterColumn('research.period.title', function($query, $keyword) {
            $sql = "EXISTS( SELECT catalog_elements.title FROM researchs, catalog_elements WHERE pieces.id = researchs.piece_id AND researchs.period_id = catalog_elements.id AND LOWER(catalog_elements.title) LIKE ? AND catalog_elements.deleted_at IS NULL AND researchs.deleted_at IS NULL )";
            $query->whereRaw($sql, ["%{$keyword}%"]);
        })->filterColumn('research.place_of_creation.title', function($query, $keyword) {
            $sql = "EXISTS( SELECT catalog_elements.title FROM researchs, catalog_elements WHERE pieces.id = researchs.piece_id AND researchs.place_of_creation_id = catalog_elements.id AND LOWER(catalog_elements.title) LIKE ? AND catalog_elements.deleted_at IS NULL AND researchs.deleted_at IS NULL )";
            $query->whereRaw($sql, ["%{$keyword}%"]);
        })->filterColumn('research.authors', function($query, $keyword) {
            $sql = "EXISTS( SELECT catalog_elements.title FROM researchs, catalog_elements WHERE pieces.id = researchs.piece_id AND FIND_IN_SET(catalog_elements.id, researchs.author_ids) AND LOWER(catalog_elements.title) LIKE ? AND catalog_elements.deleted_at IS NULL AND researchs.deleted_at IS NULL )";
            $query->whereRaw($sql, ["%{$keyword}%"]);
        });*/

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Mnemosine\Piece $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        if($this->report->select_type == "all"){
            $query = Piece::with([
                'gender:id,title',
                'subgender:id,title',
                'type_object:id,title',
                'photography',
                'research',
                'location:id,name'
            ])->select('pieces.*');
        } else if($this->report->select_type == "all_except"){
            $query = Piece::with([
                'gender:id,title',
                'subgender:id,title',
                'type_object:id,title',
                'photography',
                'research',
                'location:id,name'
            ])->select('pieces.*')->whereNotIn('id', $this->pieces_ids);
        } else{
            $query = Piece::with([
                'gender:id,title',
                'subgender:id,title',
                'type_object:id,title',
                'photography',
                'research',
                'location:id,name'
            ])->select('pieces.*')->whereIn('id', $this->pieces_ids);
        }
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $builder = $this->builder()
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->parameters($this->getBuilderParameters());

        return $builder;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $params = [
            ['data' => 'inventory_number', 'name' => 'inventory_number', 'title' => 'No. inventario', 'width' => '120px'],
            ['data' => 'catalog_number', 'name' => 'catalog_number', 'title' => 'No. catálogo', 'width' => '90px'],
            ['data' => 'origin_number', 'name' => 'origin_number', 'title' => 'No. procedencia', 'width' => '115px'],
            ['data' => 'appraisal', 'name' => 'appraisal', 'title' => 'Avalúo', 'orderable' => false],
            ['data' => 'description_origin', 'name' => 'description_origin', 'title' => 'Descripción', 'orderable' => false],
            ['data' => 'measure_without', 'name' => 'measure_without', 'title' => 'Medidas sin base/marco', 'orderable' => false],
            ['data' => 'measure_with', 'name' => 'measure_with', 'title' => 'Medidas con base/marco', 'orderable' => false],
            ['data' => 'gender.title', 'name' => 'gender.title', 'title' => 'Género', 'orderable' => false],
            ['data' => 'subgender.title', 'name' => 'subgender.title', 'title' => 'Subgénero', 'orderable' => false],
            ['data' => 'type_object.title', 'name' => 'type_object.title', 'title' => 'Tipo de objeto', 'orderable' => false],
            ['data' => 'location.name', 'name' => 'location.name', 'title' => 'Ubicación', 'orderable' => false],
            ['data' => 'tags', 'name' => 'tags', 'title' => 'Mueble', 'searchable' => true, 'width' => '150px', 'orderable' => false],
            // Investigacion
            ['data' => 'research.title', 'name' => 'research.title', 'title' => 'Título', 'orderable' => false],
            ['data' => 'research.authors', 'name' => 'research.authors', 'title' => 'Autor(es)', 'orderable' => false],
            ['data' => 'research.set.title', 'name' => 'research.set.title', 'title' => 'Conjunto', 'orderable' => false],
            ['data' => 'research.technique', 'name' => 'research.technique', 'title' => 'Técnica', 'orderable' => false],
            ['data' => 'research.materials', 'name' => 'research.materials', 'title' => 'Materiales', 'orderable' => false],
            ['data' => 'research.place_of_creation.title', 'name' => 'research.place_of_creation.title', 'title' => 'Procedencia', 'orderable' => false],
            ['data' => 'research.creation_date', 'name' => 'research.creation_date', 'title' => 'Fecha de creación', 'orderable' => false],
            ['data' => 'research.period.title', 'name' => 'research.period.title', 'title' => 'Epoca', 'orderable' => false],
            ['data' => 'research.acquisition_form', 'name' => 'research.acquisition_form', 'title' => 'Proveniencia-Forma', 'orderable' => false],
            ['data' => 'research.acquisition_source', 'name' => 'research.acquisition_source', 'title' => 'Proveniencia-Fuente/lugar', 'orderable' => false],
            ['data' => 'research.acquisition_date', 'name' => 'research.acquisition_date', 'title' => 'Proveniencia-Fecha', 'orderable' => false],
            ['data' => 'research.firm_description', 'name' => 'research.firm_description', 'title' => 'Firmas o marcas-Descripción', 'orderable' => false],
            ['data' => 'research.short_description', 'name' => 'research.short_description', 'title' => 'Descripción abreviada', 'orderable' => false],
            ['data' => 'research.formal_description', 'name' => 'research.formal_description', 'title' => 'Descripción formal', 'orderable' => false],
            ['data' => 'research.observation', 'name' => 'research.observation', 'title' => 'Observaciones', 'orderable' => false],
            ['data' => 'research.publications', 'name' => 'research.publications', 'title' => 'Publicaciones en las que aparece la obra', 'orderable' => false],
            ['data' => 'research.card', 'name' => 'research.card', 'title' => 'Cédula', 'orderable' => false],
            // Fotos, deben estar al final
            ['data' => 'photo_research', 'name' => 'photography.file_name', 'title' => 'Foto de investigación', 'width' => '114px', 'printable' => true, 'orderable' => false, 'exportable' => true, 'searchable' => false],
            ['data' => 'photo_inventory', 'name' => 'photography.file_name', 'title' => 'Foto de inventario', 'width' => '114px', 'printable' => true, 'orderable' => false, 'exportable' => true, 'searchable' => false],
        ];

        // se filtra para mostrar solo las columnas solicitadas
        $columnsSelectedFlip = array_flip($this->columnsSelected);
        $paramsAux = array();
        foreach ($params as $key => $param) {
            if(in_array($param['data'], $this->columnsSelected)){
                $paramsAux[$columnsSelectedFlip[$param['data']]] = $param;
            }
        }
        $params = $paramsAux;
        ksort($params);

        // Checkbox
        $params[] = ['data' => 'checkbox', 'name' => 'checkbox', 'title' => '<div class="custom-control custom-checkbox"><input type="checkbox" name="selectAll" class="custom-control-input" id="selectAll"/><label class="custom-control-label" for="selectAll"></label></div>', 'width' => '32px', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false];

        return $params;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return $this->report->name . "-" . date('Ymd-His');
    }

    public function setData($report, $pieces_ids, $columns, $columnsSelected){
        $this->pieces_ids = $pieces_ids;
        $this->columns = $columns;
        $this->columnsSelected = $columnsSelected;
        $this->report = $report;
    }

    // personalizar la configuracion de datatables
    protected function getBuilderParameters()
    {
        return [
            'lengthChange' => false,
            //'searchDelay' => 500,
            'stateSave' => false,
            //'ordering' => false,
            // 'pageLength' => -1,
            // 'paging' => false,
            'searching' => false,
            'buttons' => [
                'excel',
                'word',
                'copy',
                'print',
                'reset',
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
                [ 'targets' => -2, 'className' => 'picture-list' ],
                //[ 'targets' => 5, 'visible' => false ],
            ],
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
        ];
    }
}

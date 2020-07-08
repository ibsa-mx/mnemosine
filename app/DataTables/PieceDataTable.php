<?php

namespace Mnemosine\DataTables;

use Mnemosine\Piece;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Mnemosine\DataTables\DataArrayTransformer;

class PieceDataTable extends DataTable
{
    protected $calledByModule;
    // special behavior needed for consults
    protected $isConsults = false;
    protected $isMovements = false;
    protected $isResearch = false;
    protected $isRestoration = false;
    private $pieceIds;


    /**
     * Custom Export class handler.
     *
     * @var string
     */
    protected $exportClass = DataTablesExportExcelNormal::class;

    /**
    * Pops last column from $multiArray and return poped column like an array
    * @param array $multiArray
    * @return array
    */
    protected function multi_array_pop(&$multiArray){
        $tmpArray = array();
        foreach ($multiArray as $key => $childrenArray) {
            $tmpArray[] = $childrenArray[array_key_last($childrenArray)];
            unset($multiArray[$key][array_key_last($childrenArray)]);
        }
        return $tmpArray;
    }

    /*
    * BEGIN OF
    * LARAVEL DATATABLES METHODS CUSTOMIZED
    */
    protected function buildExcelFile()
    {
        set_time_limit(0);
        $dataForExportArr = $this->getDataForExport();
        foreach ($dataForExportArr  as $key => $dataExport) {
            $inventoryParts = explode("  ", $dataExport["No. inventario"], 2);
            $dataForExportArr[$key]["No. inventario"] = $inventoryParts[0];

            // add column with consecutive number
            $dataForExportArr[$key] = array_reverse($dataForExportArr[$key], true);
            $dataForExportArr[$key]["No."] = (string)($key+1);
            $dataForExportArr[$key] = array_reverse($dataForExportArr[$key], true);
        }

        $photoInventory = $photoResearch = array();
        $tmpArray = $dataForExportArr[array_key_first($dataForExportArr)];

        if(array_key_exists('Foto de inventario', $tmpArray)){
            $photoInventory = $this->multi_array_pop($dataForExportArr);
        }
        if(array_key_exists('Foto de investigación', $tmpArray)){
            $photoResearch = $this->multi_array_pop($dataForExportArr);
        }

        return new $this->exportClass($dataForExportArr, $photoInventory, $photoResearch);
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



    public function limitTooltip($string, $limit = 100){
        return (strlen($string) > $limit) ? "<span rel='tooltip' title='" .addslashes($string). "'>" . Str::limit($string, $limit) . "</span>" : $string;
    }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $module = $this->getCalledByModule();
        $moduleName = ($this->isConsults) ? 'consultas' : ($this->isMovements ? 'movimientos' : $module['name']);

        $dataTable = datatables($query)
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
                    return (isset($piece->research['title'])) ? $this->limitTooltip($piece->research['title']) : '';
                })
            ->editColumn('research.technique', function (Piece $piece) use ($moduleName) {
                    return (isset($piece->research['technique'])) ? $this->limitTooltip($piece->research['technique']) : '';
                })
            ->editColumn('research.materials', function (Piece $piece) use ($moduleName) {
                    return (isset($piece->research['materials'])) ? $this->limitTooltip($piece->research['materials']) : '';
                })
            ->editColumn('research.acquisition_form', function (Piece $piece) use ($moduleName) {
                    return (isset($piece->research['acquisition_form'])) ? $this->limitTooltip($piece->research['acquisition_form']) : '';
                })
            ->editColumn('research.acquisition_source', function (Piece $piece) use ($moduleName) {
                    return (isset($piece->research['acquisition_source'])) ? $this->limitTooltip($piece->research['acquisition_source']) : '';
                })
            ->editColumn('research.acquisition_date', function (Piece $piece) use ($moduleName) {
                    return (isset($piece->research['acquisition_date'])) ? $this->limitTooltip($piece->research['acquisition_date']) : '';
                })
            ->editColumn('research.firm_description', function (Piece $piece) use ($moduleName) {
                    return (isset($piece->research['firm_description'])) ? $this->limitTooltip($piece->research['firm_description']) : '';
                })
            ->editColumn('research.short_description', function (Piece $piece) use ($moduleName) {
                    return (isset($piece->research['short_description'])) ? $this->limitTooltip($piece->research['short_description']) : '';
                })
            ->editColumn('research.formal_description', function (Piece $piece) use ($moduleName) {
                    return (isset($piece->research['formal_description'])) ? $this->limitTooltip($piece->research['formal_description']) : '';
                })
            ->editColumn('research.observation', function (Piece $piece) use ($moduleName) {
                    return (isset($piece->research['observation'])) ? $this->limitTooltip($piece->research['observation']) : '';
                })
            ->editColumn('research.publications', function (Piece $piece) use ($moduleName) {
                    return (isset($piece->research['publications'])) ? $this->limitTooltip($piece->research['publications']) : '';
                })
            ->editColumn('research.card', function (Piece $piece) use ($moduleName) {
                    return (isset($piece->research['card'])) ? $this->limitTooltip($piece->research['card']) : '';
                })
            ->editColumn('location.name', function (Piece $piece) use ($moduleName) {
                    $html = "";
                    if (isset($piece->location_id)){
                        if ($piece->location_id == 0){
                            $html = "<em class='text-danger'>En prestamo</em>";
                        } elseif (isset($piece->location->name)) {
                            if ($moduleName == 'consultas') {
                                $html = "<a href='" . route('consultas.search', $piece->location->name) . "'>" . $piece->location->name . "</a>";
                            } else{
                                $html = $piece->location->name;
                            }
                        }
                    }
                    return $html;
                })
            ->editColumn('research.authors', function (Piece $piece) use ($moduleName) {
                    $html = "";
                    if (isset($piece->research->author_ids) && !is_null($piece->research->author_ids)) {
                        if ($moduleName == 'consultas') {
                            foreach ($piece->research->authors as $author) {
                                $html .= "<a href='" . route('consultas.search', $author) . "'>" . $author . "</a> ";
                            }
                        } else{
                            $html = implode(" ", $piece->research->authors);
                        }
                    }
                    return $html;
                })
            ->editColumn('research.period.title', function (Piece $piece) use ($moduleName) {
                    $html = "";
                    if (isset($piece->research->period->title) && !is_null($piece->research->period_id)) {
                        if ($moduleName == 'consultas') {
                            $html = "<a href='" . route('consultas.search', $piece->research->period->title) . "'>" . $piece->research->period->title . "</a>";
                        } else{
                            $html = $piece->research->period->title;
                        }
                    }
                    return $html;
                })
            ->editColumn('research.place_of_creation.title', function (Piece $piece) use ($moduleName) {
                    $html = "";
                    if (isset($piece->research->place_of_creation->title) && !is_null($piece->research->place_of_creation_id)) {
                        if ($moduleName == 'consultas') {
                            $html = "<a href='" . route('consultas.search', $piece->research->place_of_creation->title) . "'>" . $piece->research->place_of_creation->title . "</a>";
                        } else{
                            $html = $piece->research->place_of_creation->title;
                        }
                    }
                    return $html;
                })
            ->editColumn('type_object.title', function (Piece $piece) use ($moduleName) {
                    return ($moduleName == 'consultas') ? '<a href="' . route('consultas.search', $piece->type_object["title"]) . '">'. $piece->type_object["title"] . '</a>' : $piece->type_object["title"];
                })
            ->editColumn('gender.title', function (Piece $piece) use ($moduleName) {
                    return ($moduleName == 'consultas') ? '<a href="' . route('consultas.search', $piece->gender["title"]) . '">'. $piece->gender["title"] . '</a>' : $piece->gender["title"];
                })
            ->editColumn('subgender.title', function (Piece $piece) use ($moduleName) {
                    return ($moduleName == 'consultas') ? '<a href="' . route('consultas.search', $piece->subgender["title"]) . '">'. $piece->subgender["title"] . '</a>' : $piece->subgender["title"];
                });
        if($this->isResearch || $this->isRestoration){
            $dataTable->editColumn('photography2', function (Piece $piece) use ($module) {
                    $photographs = $piece->photography;
                    return view('datatables.photographs', compact('photographs', 'module'));
                });
        }
        $dataTable->editColumn('photography', function (Piece $piece) {
                $photographs = $piece->photography;
                return view('datatables.photographs_inventory', compact('photographs'));
            });

        if($this->isMovements){
            $dataTable->addColumn('checkbox', function (Piece $piece) {
                // $id = $piece->id;
                // $in_exhibition = $piece->in_exhibition;
                $pieceIds = $this->pieceIds;
                return view('datatables.action_movimientos', compact('pieceIds', 'piece'));
            });
        } else{
            $dataTable->addColumn('action', 'datatables.action_'.$moduleName);
        }

        $dataTable->filterColumn('research.place_of_creation.title', function($query, $keyword) {
            $sql = "EXISTS( SELECT catalog_elements.title FROM researchs, catalog_elements WHERE pieces.id = researchs.piece_id AND researchs.place_of_creation_id = catalog_elements.id AND LOWER(catalog_elements.title) LIKE LOWER(?) AND catalog_elements.deleted_at IS NULL AND researchs.deleted_at IS NULL )";
            $query->whereRaw($sql, ["{$keyword}"]);
        })->filterColumn('research.period.title', function($query, $keyword) {
            $sql = "EXISTS( SELECT catalog_elements.title FROM researchs, catalog_elements WHERE pieces.id = researchs.piece_id AND researchs.period_id = catalog_elements.id AND LOWER(catalog_elements.title) LIKE LOWER(?) AND catalog_elements.deleted_at IS NULL AND researchs.deleted_at IS NULL )";
            $query->whereRaw($sql, ["{$keyword}"]);
        })->filterColumn('research.authors', function($query, $keyword) {
            $sql = "EXISTS( SELECT catalog_elements.title FROM researchs, catalog_elements WHERE pieces.id = researchs.piece_id AND FIND_IN_SET(catalog_elements.id, researchs.author_ids) AND LOWER(catalog_elements.title) LIKE LOWER(?) AND catalog_elements.deleted_at IS NULL AND researchs.deleted_at IS NULL )";
            $query->whereRaw($sql, ["{$keyword}"]);
        });

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
        $query = Piece::with([
            'gender:id,title',
            'subgender:id,title',
            'type_object:id,title',
            'photography',
            'research',
            'location:id,name'
        ])->select('pieces.*');

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
        if(!$this->isMovements){
            $builder->addAction(['title' => '', 'printable' => false, 'exportable' => false, 'width' => '75px']);
        }

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
            ['data' => 'gender.title', 'name' => 'gender.title', 'title' => 'Género'],
            ['data' => 'subgender.title', 'name' => 'subgender.title', 'title' => 'Subgénero'],
            ['data' => 'type_object.title', 'name' => 'type_object.title', 'title' => 'Tipo de objeto'],
            ['data' => 'location.name', 'name' => 'location.name', 'title' => 'Ubicación', 'orderable' => false],
            ['data' => 'tags', 'name' => 'tags', 'title' => 'Mueble', 'searchable' => true, 'width' => '150px', 'orderable' => false],
            ['data' => 'description_origin', 'name' => 'description_origin', 'title' => 'Descripción', 'orderable' => false],
            ['data' => 'research.title', 'name' => 'research.title', 'title' => 'Título', 'orderable' => false],
            ['data' => 'research.authors', 'name' => 'research.authors', 'title' => 'Autor(es)', 'orderable' => false],
            ['data' => 'research.period.title', 'name' => 'research.period.title', 'title' => 'Epoca', 'orderable' => false],
            ['data' => 'research.place_of_creation.title', 'name' => 'research.place_of_creation.title', 'title' => 'Procedencia', 'orderable' => false],
            ['data' => 'research.technique', 'name' => 'research.technique', 'title' => 'Técnica', 'orderable' => false],
            ['data' => 'research.materials', 'name' => 'research.materials', 'title' => 'Materiales', 'orderable' => false],
            ['data' => 'research.acquisition_form', 'name' => 'research.acquisition_form', 'title' => 'Proveniencia-Forma', 'orderable' => false],
            ['data' => 'research.acquisition_source', 'name' => 'research.acquisition_source', 'title' => 'Proveniencia-Fuente/lugar', 'orderable' => false],
            ['data' => 'research.acquisition_date', 'name' => 'research.acquisition_date', 'title' => 'Proveniencia-Fecha', 'orderable' => false],
            ['data' => 'research.firm_description', 'name' => 'research.firm_description', 'title' => 'Firmas o marcas-Descripción', 'orderable' => false],
            ['data' => 'research.short_description', 'name' => 'research.short_description', 'title' => 'Descripción abreviada', 'orderable' => false],
            ['data' => 'research.formal_description', 'name' => 'research.formal_description', 'title' => 'Descripción formal', 'orderable' => false],
            ['data' => 'research.observation', 'name' => 'research.observation', 'title' => 'Observaciones', 'orderable' => false],
            ['data' => 'research.publications', 'name' => 'research.publications', 'title' => 'Publicaciones en las que aparece la obra', 'orderable' => false],
            ['data' => 'research.card', 'name' => 'research.card', 'title' => 'Cédula', 'orderable' => false],
            ['data' => 'measure_without', 'name' => 'measure_without', 'title' => 'Medidas sin base/marco', 'orderable' => false, 'searchable' => false],
            ['data' => 'measure_with', 'name' => 'measure_with', 'title' => 'Medidas con base/marco', 'orderable' => false, 'searchable' => false]
        ];
        if($this->isResearch || $this->isRestoration){
            array_push($params, ['data' => 'photography2', 'name' => 'photography.file_name', 'title' => 'Foto de ' . $this->getCalledByModule()['name'], 'width' => '114px', 'printable' => true, 'orderable' => false, 'exportable' => true, 'searchable' => false]);
        }
        array_push($params, ['data' => 'photography', 'name' => 'photography.file_name', 'title' => 'Foto de inventario', 'width' => '114px', 'printable' => true, 'orderable' => false, 'exportable' => true, 'searchable' => false]);
        if($this->isMovements){
            $checkbox = <<<EOL
    <div class='custom-control custom-checkbox' title='Seleccionar piezas de la página actual' rel='tooltip'>
        <input type='checkbox' id='selectAll' class='custom-control-input' />
        <label class='custom-control-label' for='selectAll'></label>
    </div>
EOL;
            array_push($params, ['data' => 'checkbox', 'name' => 'checkbox', 'title' => $checkbox, 'width' => '32px', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false]);
        }
        return $params;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Piezas_' . date('Y-m-d_H-i-s');
    }

    public function setCalledByModule($moduleName, $moduleId, $moduleNameEN, $pieceIds = null){
        switch ($moduleName) {
            case 'consultas':
                $moduleName = 'inventario';
                $moduleId = 1;
                $this->isConsults = true;
                break;
            case 'movimientos':
                $moduleName = 'inventario';
                $moduleId = 1;
                $this->isMovements = true;
                break;
            case 'investigacion':
                $this->isResearch = true;
                break;
            case 'restauracion':
                $this->isRestoration = true;
                break;
        }
        if(!is_null($pieceIds)) $this->pieceIds = $pieceIds;
        $this->calledByModule = ['name' => $moduleName, 'id' => $moduleId, 'nameEN' => $moduleNameEN];
    }

    public function getCalledByModule(){
        return $this->calledByModule;
    }
}

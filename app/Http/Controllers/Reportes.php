<?php

namespace Mnemosine\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mnemosine\DataTables\PieceDataTable;
use Mnemosine\DataTables\ReportDataTable;
use Mnemosine\Authorizable;
use Mnemosine\Piece;
use Mnemosine\Gender;
use Mnemosine\Subgender;
use Mnemosine\Catalog;
use Mnemosine\Catalog_element;
use Mnemosine\Exhibition;
use Mnemosine\Report;
use Mnemosine\Research;
use Mnemosine\Module;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;
use Exception;

//
class Reportes extends Controller
{
    use Authorizable;

    private $columnas = array();
    protected $moduleName = "movimientos";

    function __construct(){
        $this->columnas = array(
            //'Inventario' => array(
                'inventory_number' => 'No. inventario',
                'catalog_number' => 'No. catálogo',
                'origin_number' => 'No. procedencia',
                'description_origin' => 'Descripción',
                'gender.title' => 'Género', //id
                'subgender.title' => 'Subgénero', //id
                'type_object.title' => 'Tipo de objeto', //id
                'tags' => 'Mueble',
                'appraisal' => 'Avalúo',
                'location.name' => 'Ubicación', //id
                'measure_without' => 'Medidas sin base/marco',
                'measure_with' => 'Medidas con base/marco',
                'photo_inventory' => 'Foto de inventario',
            //'Investigación' => array(
                'research.title' => 'Título',
                'research.authors' => 'Autor(es)', //ids
                'research.set.title' => 'Conjunto', //id
                'research.technique' => 'Técnica',
                'research.materials' => 'Materiales',
                'research.place_of_creation.title' => 'Procedencia', //id
                'research.creation_date' => 'Fecha de creación',
                'research.period.title' => 'Época',
                'research.acquisition_form' => 'Proveniencia - Forma',
                'research.acquisition_source' => 'Proveniencia - Fuente/lugar',
                'research.acquisition_date' => 'Proveniencia - Fecha',
                //'research.firm' => 'Firmas o marcas', //special
                'research.firm_description' => 'Firmas o marcas - Descripción', //special
                'research.short_description' => 'Descripción abreviada',
                'research.formal_description' => 'Descripción formal',
                'research.observation' => 'Observaciones',
                'research.publications' => 'Publicaciones en las que aparece la obra',
                'photo_research' => 'Foto de investigación',
                'research.card' => 'Cédula'
            );
        asort($this->columnas);
    }

    public function index()
    {
        $reports = Report::with(['creator', 'updater'])->get();
        return view('reportes.index', compact('reports'));
    }

    public function create(PieceDataTable $dataTable)
    {
        $columnas = $this->columnas;

        $ids = array();
        $dataTable->setCalledByModule($this->moduleName, $this->getModuleId(), 'inventory', $ids);

        return $dataTable->render('reportes.create', compact('columnas'));
    }

    public function edit($id, PieceDataTable $dataTable)
    {
        $reporte = Report::findOrFail($id);
        $columnas = $this->columnas;
        $columnasSeleccionadas = explode(',', $reporte->columns);
        $ids = array_filter(explode(',', $reporte->pieces_ids), 'strlen');
        $piezas = count($ids) > 0 ? Piece::find($ids) : collect();

        $dataTable->setCalledByModule($this->moduleName, $this->getModuleId(), 'inventory', $ids);

        return $dataTable->render('reportes.edit', compact('reporte', 'columnas', 'columnasSeleccionadas', 'piezas', 'ids'));
    }

    public function show($id, ReportDataTable $dataTable)
    {
        $reporte = Report::with(['creator', 'updater'])->findOrFail($id);

        $columnas = $this->columnas;
        $columnasSeleccionadas = explode(',', $reporte->columns);
        $pieces_ids = explode(",", $reporte->pieces_ids);

        $dataTable->setData($reporte, $pieces_ids, $columnas, $columnasSeleccionadas);
        return $dataTable->render('reportes.show', compact('reporte'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'Nombre', 'name'=>'required',
            'Piezas', 'pieces_ids'=>'required',
            'Columnas', 'columnas'=>'required']
        );

        $report = new Report();
        $report->fill($request->only('name', 'description', 'select_type', 'institution', 'exhibition', 'exhibition_date_ini', 'exhibition_date_fin', 'pieces_ids'));
        $report->lending_list = (bool)$request->input('lending_list');
        $report->custom_order = (bool)$request->input('custom_order');
        if((bool)$request->input('custom_order')){
            // ya viene como cadena separada por comas
            $report->columns = trim($request->input("columnas_ordenadas"));
        } else{
            $report->columns = implode(",", $request->input('columnas'));
        }
        $report->save();

        flash()->success('El reporte se creo con exito');
        return redirect()->route('reportes.show', ['id' => $report->id]);
    }

    public function update(Request $request, $id) {
        $validate = $request->validate([
            'Nombre', 'name'=>'required',
            'Piezas', 'pieces_ids'=>'required',
            'Columnas', 'columnas'=>'required']
        );

        $report = Report::findOrFail($id);
        $report->fill($request->only('name', 'description', 'select_type', 'institution', 'exhibition', 'exhibition_date_ini', 'exhibition_date_fin', 'pieces_ids'));
        $report->lending_list = (bool)$request->input('lending_list');
        $report->custom_order = (bool)$request->input('custom_order');
        if((bool)$request->input('custom_order')){
            // ya viene como cadena separada por comas
            $report->columns = trim($request->input("columnas_ordenadas"));
        } else{
            $report->columns = implode(",", $request->input('columnas'));
        }
        $report->save();

        flash()->success('El reporte se modifico con exito');
        return redirect()->route('reportes.show', ['id' => $report->id]);
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $reporte = Report::findOrFail($id);
        $reporte->Delete();
        flash()->success('Se elimino correctamente');
        return redirect()->route('reportes.index');
    }

    public function cedula(Request $request, $id){
        $this->validateCedula($request);
        $piezas = Piece::findOrFail($request->input('pieces_ids'));

        switch($request->btn_cedula){
            case '1':
                $this->generateDocxC1($piezas);
                return view('reportes.cedula.cedula1', compact('id', 'piezas'));
                break;
            case '2':
                $this->generateDocxC2($piezas);
                return view('reportes.cedula.cedula2', compact('id', 'piezas'));
                break;
            case '3a':
                $this->generateDocxC3a($piezas);
                return view('reportes.cedula.cedula3a', compact('id', 'piezas'));
                break;
            case '3b':
                $this->generateDocxC3b($piezas);
                return view('reportes.cedula.cedula3b', compact('id', 'piezas'));
                break;
            case '4':
                $reporte = Report::findOrFail($id);
                $this->generateDocxC4($piezas, $reporte);
                return view('reportes.cedula.cedula4', compact('id', 'piezas', 'reporte'));
                break;
            default:
                return redirect()->route('reportes.show', $id);
        }
    }

    private function countResearchs($pieces){
        $count = 0;
        foreach ($pieces as $key => $piece) {
            if(isset($piece->research)) $count++;
        }
        return $count;
    }

    public function generateDocxC1($piezas){
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/reportes/template_cedula1.docx'));
        $templateProcessor->cloneBlock('cedula', $this->countResearchs($piezas), true);

        $maxReplacements = 1;
        foreach ($piezas as $pieza) {
            if(!isset($pieza->research->piece_id) || empty($pieza->research->piece_id)) continue;

            $templateProcessor->setValue('autor', count($pieza->research->authors) > 0 ? implode(",", $pieza->research->authors) : '', $maxReplacements);
            $templateProcessor->setValue('lugar_creacion', isset($pieza->research->place_of_creation) ? $pieza->research->place_of_creation->title . ', ' : '', $maxReplacements);
            $templateProcessor->setValue('epoca', isset($pieza->research->period) ? $pieza->research->period->title : '', $maxReplacements);
            $templateProcessor->setValue('titulo', $pieza->research->title, $maxReplacements);
            $templateProcessor->setValue('fecha_creacion', $pieza->research->creation_date, $maxReplacements);
            $templateProcessor->setValue('tecnica', $pieza->research->technique, $maxReplacements);
            $templateProcessor->setValue('medidas', $pieza->height . ' x ' . $pieza->width . ' x ' . $pieza->depth . ' ø ' . $pieza->diameter, $maxReplacements);
            $templateProcessor->setValue('procedencia', $pieza->research->adquisition_source, $maxReplacements);

            $templateProcessor->setValue('numero_proc', $pieza->origin_number, $maxReplacements);
            $templateProcessor->setValue('numero_inv', $pieza->inventory_number, $maxReplacements);
            $templateProcessor->setValue('numero_cat', $pieza->catalog_number, $maxReplacements);
            $templateProcessor->setValue('ubicacion', isset($pieza->location->name) ? $pieza->location->name : '', $maxReplacements);

            $templateProcessor->setValue('espacio', '', $maxReplacements);
        }
        $templateProcessor->saveAs(storage_path('app/reportes/cedula1.docx'));
        return;
    }

    public function generateDocxC2($piezas){
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/reportes/template_cedula2.docx'));
        $templateProcessor->cloneBlock('cedula', $this->countResearchs($piezas), true);

        $maxReplacements = 1;
        foreach ($piezas as $pieza) {
            if(!isset($pieza->research->piece_id) || empty($pieza->research->piece_id)) continue;

            $templateProcessor->setValue('titulo', $pieza->research->title, $maxReplacements);
            $templateProcessor->setValue('lugar_creacion', isset($pieza->research->place_of_creation) ? $pieza->research->place_of_creation->title . ', ' : '', $maxReplacements);
            $templateProcessor->setValue('epoca', isset($pieza->research->period) ? $pieza->research->period->title : '', $maxReplacements);
            $templateProcessor->setValue('tecnica', $pieza->research->technique, $maxReplacements);
            $templateProcessor->setValue('medidas', $pieza->height . ' x ' . $pieza->width . ' x ' . $pieza->depth . ' ø ' . $pieza->diameter, $maxReplacements);
            $templateProcessor->setValue('procedencia', $pieza->research->adquisition_source, $maxReplacements);
            $templateProcessor->setValue('numero_proc', $pieza->origin_number, $maxReplacements);
            $templateProcessor->setValue('numero_inv', $pieza->inventory_number, $maxReplacements);
            $templateProcessor->setValue('numero_cat', $pieza->catalog_number, $maxReplacements);
            $templateProcessor->setValue('ubicacion', isset($pieza->location->name) ? $pieza->location->name : '', $maxReplacements);

            $templateProcessor->setValue('espacio', '', $maxReplacements);
        }
        $templateProcessor->saveAs(storage_path('app/reportes/cedula2.docx'));
        return;
    }

    public function generateDocxC3a($piezas){
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/reportes/template_cedula3a.docx'));
        $templateProcessor->cloneBlock('cedula', $this->countResearchs($piezas), true);

        $maxReplacements = 1;
        foreach ($piezas as $pieza) {
            if(!isset($pieza->research->piece_id) || empty($pieza->research->piece_id)) continue;

            $templateProcessor->setValue('autor', count($pieza->research->authors) > 0 ? implode(",", $pieza->research->authors) : '', $maxReplacements);
            $templateProcessor->setValue('epoca', isset($pieza->research->period) ? $pieza->research->period->title : '', $maxReplacements);
            $templateProcessor->setValue('lugar_creacion', isset($pieza->research->place_of_creation) ? $pieza->research->place_of_creation->title . ', ' : '', $maxReplacements);
            $templateProcessor->setValue('titulo', $pieza->research->title, $maxReplacements);
            $templateProcessor->setValue('fecha_creacion', $pieza->research->creation_date, $maxReplacements);
            $templateProcessor->setValue('tecnica', $pieza->research->technique, $maxReplacements);
            $templateProcessor->setValue('espacio', '', $maxReplacements);
        }
        $templateProcessor->saveAs(storage_path('app/reportes/cedula3a.docx'));
        return;
    }

    public function generateDocxC3b($piezas){
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/reportes/template_cedula3b.docx'));
        $templateProcessor->cloneBlock('cedula', $this->countResearchs($piezas), true);

        $maxReplacements = 1;
        foreach ($piezas as $pieza) {
            if(!isset($pieza->research->piece_id) || empty($pieza->research->piece_id)) continue;

            $templateProcessor->setValue('epoca', isset($pieza->research->period) ? $pieza->research->period->title : '', $maxReplacements);
            $templateProcessor->setValue('lugar_creacion', isset($pieza->research->place_of_creation) ? $pieza->research->place_of_creation->title . ', ' : '', $maxReplacements);
            $templateProcessor->setValue('titulo', $pieza->research->title, $maxReplacements);
            $templateProcessor->setValue('tecnica', $pieza->research->technique, $maxReplacements);
            $templateProcessor->setValue('espacio', '', $maxReplacements);

        }
        $templateProcessor->saveAs(storage_path('app/reportes/cedula3b.docx'));
        return;
    }

    public function generateDocxC4($piezas, $reporte){
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/reportes/template_cedula4.docx'));
        $templateProcessor->cloneBlock('cedula', $this->countResearchs($piezas), true, true);

        foreach($piezas as $idx => $pieza) {
            if(!isset($pieza->research->piece_id) || empty($pieza->research->piece_id)) continue;
            $idx++;

            $imagen = storage_path() . "/app/public/" . config('fileuploads.inventory.photographs.thumbnails') . "/" . $pieza->photography[0]->file_name;
            $templateProcessor->setValue('numero#' . $idx, $idx);
            $templateProcessor->setImageValue('imagen#' . $idx, $imagen);

            $columnas = "";
            foreach (explode(",", $reporte->columns) as $index => $column){
                $campo = explode(".", $column);
                if(($column == "photo_inventory") || ($column == "photo_research")){
                    continue;
                }
                switch(count($campo)){
                    case 1:
                        switch($column){
                            case "measure_with":
                                $columnas .= $pieza->height_with_base . " x " . $pieza->width_with_base . " x " . $pieza->depth_with_base . " ø " . $pieza->diameter_with_base . " cm";
                                break;
                            case "measure_without":
                                $columnas .= $pieza->height . " x " . $pieza->width . " x " . $pieza->depth . " ø " . $pieza->diameter . " cm";
                                break;
                            case "appraisal":
                                $columnas .= "&#36;" . number_format($pieza->{$campo[0]}, 2);
                                break;
                            case "tags":
                                $columnas .= implode(", ", $pieza->{$campo[0]});
                                break;
                            default:
                                if(empty($pieza->{$campo[0]})) continue 3;
                                $columnas .= $pieza->{$campo[0]};
                        }
                        break;
                    case 2:
                        if(empty($pieza->{$campo[0]}->{$campo[1]})) continue 2;
                        if($column == "research.authors"){
                            $columnas .= implode(", ", $pieza->{$campo[0]}->{$campo[1]});
                        } else {
                            $columnas .= $pieza->{$campo[0]}->{$campo[1]};
                        }
                        break;
                    case 3:
                        if(empty($pieza->{$campo[0]}->{$campo[1]}->{$campo[2]})) continue 2;
                        $columnas .= $pieza->{$campo[0]}->{$campo[1]}->{$campo[2]};
                        break;
                }
                $columnas .= "</w:t><w:br/><w:t>";
            }
            $templateProcessor->setValue('columnas#' . $idx, $columnas);

            $templateProcessor->setValue('columna_cedula#' . $idx, isset($pieza->research->card) ? $pieza->research->card . ', ' : '');
        }
        $templateProcessor->saveAs(storage_path('app/reportes/cedula4.docx'));
        return;
    }

    public function descargarCedula($cedula){
        header("Content-Disposition: attachment; filename=cedula" . $cedula . ".docx; charset=iso-8859-1");
        echo file_get_contents(storage_path("app/reportes/cedula". $cedula .".docx"));
    }

    /**
    * Retrieves the id of the module this class belongs
    *
    * @return integer
    */
    public function getModuleId(){
        return (integer)Module::where('name', $this->moduleName)
        ->where('active', 1)
        ->first()
        ->id;
    }

    public function validateCedula(Request $request) {
        $customMessages = [
            'pieces_ids.required' => 'Debe seleccionar al menos una pieza para generar la cédula',
            'btn_cedula.required' => 'No se pudo determinar que tipo de cédula solicito',
        ];
        $this->validate($request, [
            'pieces_ids' => 'required',
            'btn_cedula' => 'required',
        ], $customMessages);
    }
}

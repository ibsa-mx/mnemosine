<?php

namespace Mnemosine\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Mnemosine\Piece;
use Mnemosine\DataTables\PieceDataTable;
use Mnemosine\Gender;
use Mnemosine\Subgender;
use Mnemosine\Dimension;
use Mnemosine\Catalog;
use Mnemosine\Catalog_element;
use Mnemosine\Photography;
use Mnemosine\Document;
use Mnemosine\Research;
use Mnemosine\Restoration;
use Mnemosine\Bibliography;
use Mnemosine\Footnote;
use Mnemosine\Appraisal;
use Mnemosine\Module;
use Mnemosine\User;
use Mnemosine\Role;
use Mnemosine\Movement;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing as drawing;
use Mnemosine\Authorizable;

set_time_limit(0);

class ConsultasController extends Controller
{
    use Authorizable;
    // use inventory as the main module for this module
    protected $moduleName = "consultas";

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

    public function index(PieceDataTable $dataTable){
        $dataTable->setCalledByModule($this->moduleName, $this->getModuleId(), 'inventory');
        $keywords = session('keywords') ?? request('keywords');
        //$keywords = session('keywords');
        session(['keywords' => '']);
        return $dataTable->render('consulta.index', compact('keywords'));
    }

    public function detalle($id)
    {
        $piece = Piece::with([
            'gender:id,title',
            'subgender:id,title',
            'type_object:id,title',
            'photography',
            'research',
            'location'
        ])->where("id", $id)->get()->first();

        $restorations = Restoration::where('piece_id', $id)->orderBy('treatment_date', 'desc')->get();
        $appraisals = Appraisal::where('piece_id', $id)->orderBy('created_at', 'desc')->orderBy('id', 'desc')->get();

        $user = User::find($piece->created_by);
        $piece->created_by_name = $user->name;
        if(is_null($piece->updated_by)){
            $piece->updated_by_name = $piece->created_by_name;
        } else{
            $user = User::find($piece->updated_by);
            $piece->updated_by_name = $user->name;
        }

        if(!is_null($restorations)){
            $responsibleRestorerCatalog = Catalog::where('code', 'responsible_restorer')->get()->first();
            $responsibleRestorers = Catalog_element::where('catalog_id', $responsibleRestorerCatalog->id)->get();

            foreach ($restorations as $restoration) {
                $user = User::find($restoration->created_by);
                $restoration->created_by_name = $user->name;
                if(is_null($restoration->updated_by)){
                    $restoration->updated_by_name = $restoration->created_by_name;
                } else{
                    $user = User::find($restoration->updated_by);
                    $restoration->updated_by_name = $user->name;
                }
            }
        }

        $bibliographs = null;
        $footnotes = null;
        $authorNames = null;
        if(!is_null($piece->research)){
            $bibliographs = Bibliography::where('research_id', $piece->research->id)->get();
            $footnotes = Footnote::where('research_id', $piece->research->id)->get();
            $authors = Catalog_element::find(explode(",", $piece->research->author_ids));
            $authorNames = $authors->pluck('title');

            $user = User::find($piece->research->created_by);
            $piece->research->created_by_name = $user->name;
            if(is_null($piece->research->updated_by)){
                $piece->research->updated_by_name = $piece->research->created_by_name;
            } else{
                $user = User::find($piece->research->updated_by);
                $piece->research->updated_by_name = $user->name;
            }
        }

        $referenceTypeCatalog = Catalog::where('code', 'reference_type')->get()->first();
        $referenceTypes = Catalog_element::where('catalog_id', $referenceTypeCatalog->id)->get();
        $referenceTypesArray = !is_null($referenceTypes) ? $referenceTypes->pluck('title', 'id')->toArray() : null;

        foreach ($appraisals as $idx => $appraisal) {
            $user = User::find($appraisal->created_by);
            $appraisals[$idx]->update_name = $user->name;
            $appraisals[$idx]->update_email = $user->email;
        }
        $modulesId['inventario'] = Module::where('name', 'inventario')->get()->first();
        $modulesId['investigacion'] = Module::where('name', 'investigacion')->get()->first();
        $modulesId['restauracion'] = Module::where('name', 'restauracion')->get()->first();

        $mimeIcons = array(
            "text/plain" => "alt",
            "application/pdf" => "pdf",
            "application/xml" => "code",
            "text/html" => "code",
            "application/msword" => "word",
            "application/vnd.ms-excel" => "excel",
            "application/vnd.ms-powerpoint" => "powerpoint",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document" => "word",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" => "excel",
            "application/vnd.openxmlformats-officedocument.presentationml.presentation" => "powerpoint",
        );
        $fileTypes = array(
            "text/plain" => "Texto",
            "application/pdf" => "PDF",
            "application/xml" => "XML",
            "text/html" => "HTML",
            "application/msword" => "Word",
            "application/vnd.ms-excel" => "Excel",
            "application/vnd.ms-powerpoint" => "PowerPoint",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document" => "Word",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" => "Excel",
            "application/vnd.openxmlformats-officedocument.presentationml.presentation" => "PowerPoint",
        );
        $bgColors = array(
            "Word" => 'primary',
            "Texto" => 'secondary',
            "Excel" => 'success',
            "PDF" => 'danger',
            "HTML" => 'cyan',
            "XML" => 'indigo',
            "PowerPoint" => 'pink'
        );

        $storageUrl['research'] = Storage::url('') . config('fileuploads.research.documents.originals') . "/";
        $storageUrl['restoration'] = Storage::url('') . config('fileuploads.restoration.documents.originals') . "/";

        $movements = Movement::whereRaw('FIND_IN_SET(?, pieces_ids)', [$piece->id])
            ->where('authorized_by_collections', '>', 0)
            ->orderBy('departure_date', 'desc')->get();

        return view('consulta.detalle', compact('piece', 'bibliographs', 'footnotes', 'restorations', 'appraisals', 'modulesId', 'authorNames', 'responsibleRestorers', 'referenceTypesArray', 'bgColors', 'mimeIcons', 'fileTypes', 'storageUrl', 'movements'));
    }

    public function xml($id){
        $piece = Piece::findOrFail($id);

        $publisher = config('app.institution');
        $gender = isset($piece->gender->title) ? $piece->gender->title : '';
        $subgender = isset($piece->subgender->title) ? $piece->subgender->title : '';
        $authorNames = null;
        if(!is_null($piece->research)){
            $authors = Catalog_element::find(explode(",", $piece->research->author_ids));
            $authorNames = $authors->pluck('title');
        }

        $xml = new \SimpleXMLElement(view('consulta.xml', compact('piece', 'publisher', 'gender', 'subgender', 'authorNames')));

        return response($this->prettyXml($xml))
            ->withHeaders([
                'Content-Type' => 'text/xml',
                'Content-Disposition' => 'attachment; filename="'. $piece->inventory_number . ' - ' . $piece->catalog_number .'.xml"'
            ]);
    }

    public function word($id){
        $piece = Piece::findOrFail($id);

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/reportes/detalle.docx'));
        $templateProcessor->setValue('noInventario', $piece->inventory_number);
        $templateProcessor->setValue('noCatalogo', $piece->catalog_number);
        $templateProcessor->setValue('noProcedencia', $piece->origin_number);
        $templateProcessor->setValue('descripcion', str_replace("\n", '</w:t><w:br/><w:t>', htmlspecialchars($piece->description_origin)));

        $gender = isset($piece->gender->title) ? $piece->gender->title : '';
        $templateProcessor->setValue('genero', $gender);

        $subgender = isset($piece->subgender->title) ? $piece->subgender->title : '';
        $templateProcessor->setValue('subgenero', $subgender);

        $type_object = isset($piece->type_object->title) ? $piece->type_object->title : '';
        $templateProcessor->setValue('tipoObjeto', $type_object);

        $templateProcessor->setValue('mueble', $piece->tags);
        $templateProcessor->setValue('avaluo', '$ ' . number_format($piece->appraisal, 2) . ' USD');

        $location = isset($piece->location->name) ? $piece->location->name : '';
        $templateProcessor->setValue('ubicacion', $location);

        $admitted_at = isset($piece->admitted_at) ? $piece->admitted_at->diffForHumans() : "N/A";
        $templateProcessor->setValue('fingreso', $admitted_at);

        $templateProcessor->setValue('salto', $piece->height);
        $templateProcessor->setValue('sancho', $piece->width);
        $templateProcessor->setValue('sprofundo', $piece->depth);
        $templateProcessor->setValue('sdiametro', $piece->diameter);

        $templateProcessor->setValue('calto', $piece->height_with_base);
        $templateProcessor->setValue('cancho', $piece->width_with_base);
        $templateProcessor->setValue('cprofundo', $piece->depth_with_base);
        $templateProcessor->setValue('cdiametro', $piece->diameter_with_base);


        $templateProcessor->setValue('titulo', ($piece->research)? $piece->research->title: 'N/A');

        $authorNames = null;
        $bibliographs = null;
        if(!is_null($piece->research)){
            $authors = Catalog_element::find(explode(",", $piece->research->author_ids));
            $authorNames = $authors->pluck('title');

            $bibliographs = Bibliography::where('research_id', $piece->research->id)->get();
        }

        $templateProcessor->setValue('autor', ($authorNames)? $authorNames->join(", "): 'N/A');

        $templateProcessor->setValue('conjunto', isset($piece->research->set->title)? $piece->research->set->title: 'N/A');
        $templateProcessor->setValue('tecnica', isset($piece->research->technique) ? str_replace("\n", '</w:t><w:br/><w:t>', htmlspecialchars($piece->research->technique)) : "N/A");
        $templateProcessor->setValue('materiales', isset($piece->research->materials) ? str_replace("\n", '</w:t><w:br/><w:t>', htmlspecialchars($piece->research->materials)) : "N/A");
        $templateProcessor->setValue('procedencia', isset($piece->research->place_of_creation->title)? $piece->research->place_of_creation->title: 'N/A');
        $templateProcessor->setValue('fcreacion', isset($piece->research->creation_date) ? $piece->research->creation_date: 'N/A');
        $templateProcessor->setValue('epoca', isset($piece->research->period->title) ? $piece->research->period->title: 'N/A');
        $templateProcessor->setValue('pforma', isset($piece->research->acquisition_form) ? $piece->research->acquisition_form: 'N/A');
        $templateProcessor->setValue('pflugar', isset($piece->research->acquisition_source) ? $piece->research->acquisition_source: 'N/A');
        $templateProcessor->setValue('pfecha', isset($piece->research->acquisition_date) ? $piece->research->acquisition_date: 'N/A');

        $templateProcessor->setValue('firmaMarca', isset($piece->research->firm) ? ((bool)$piece->research->firm ? 'Sí' : 'No') : 'No');
        $templateProcessor->setValue('firmaMarcaDesc', isset($piece->research->firm_description) ? str_replace("\n", '</w:t><w:br/><w:t>', htmlspecialchars($piece->research->firm_description)) : 'N/A');
        $templateProcessor->setValue('descAbrev', isset($piece->research->short_description) ? str_replace("\n", '</w:t><w:br/><w:t>', htmlspecialchars($piece->research->short_description)) : 'N/A');
        $templateProcessor->setValue('descFormal', isset($piece->research->formal_description) ? str_replace("\n", '</w:t><w:br/><w:t>', htmlspecialchars($piece->research->formal_description)) : 'N/A');
        $templateProcessor->setValue('iObserv', isset($piece->research->observation) ? str_replace("\n", '</w:t><w:br/><w:t>', htmlspecialchars($piece->research->observation)) : 'N/A');
        $templateProcessor->setValue('ipao', isset($piece->research->publications) ? str_replace("\n", '</w:t><w:br/><w:t>', htmlspecialchars($piece->research->publications)) : 'N/A');

        $footnotes = null;
        $authorNames = null;
        if(!is_null($piece->research)){
            $footnotes = Footnote::where('research_id', $piece->research->id)->get();
        }

        $fns = array();
        if(!is_null($footnotes)){
            foreach ($footnotes as $idx => $footnote){
                $fns[] = $footnote;
            }
        }

        $templateProcessor->setValue('notasAlPie', count($fns) > 0 ? implode('<w:br />', $fns) : count($fns) == 1 ? $fns[0] : "");

        if(!is_null($bibliographs)){
            $templateProcessor->cloneRow('numBib', count($bibliographs));
            foreach ($bibliographs as $idx => $bibliography){
                $idx = ($idx + 1);
                $templateProcessor->setValue('numBib#' . $idx, $idx);
                $chicago = $bibliography->author . ', ' . (!empty($bibliography->article) ? '"'.$bibliography->article.'"' : '') . (!empty($bibliography->chapter) ? '"'.$bibliography->chapter.'"' : '') . (!empty($bibliography->editor) ? 'En '.$bibliography->editor : '') . ('' .$bibliography->title . '') . (!empty($bibliography->pages) ? $bibliography->pages.',' : '') . '. ' .  (!empty($bibliography->city_country) ? $bibliography->city_country : '') . (!empty($bibliography->city_country) && !empty($bibliography->editorial) ? ' : ' : '') . (!empty($bibliography->editorial) ? $bibliography->editorial.',':'') . (!empty($bibliography->vol_no) ? $bibliography->vol_no.',' : '') . ($bibliography->publication_date) . ($bibliography->identifier) . (!empty($bibliography->webpage) ? $bibliography->webpage : '');

                $templateProcessor->setValue('chicago#' . $idx, $chicago);

                $apa = $bibliography->author . ' (' . $bibliography->publication_date . '). ' . $bibliography->article . ' ' . $bibliography->chapter . ' ' . (!empty($bibliography->editor) ? 'En '.$bibliography->editor : '') . ' ' . $bibliography->title . '. ' . (!empty($bibliography->city_country) ? $bibliography->city_country : '') . ' ' . (!empty($bibliography->city_country) && !empty($bibliography->editorial) ? ':' : '')  . ' ' . $bibliography->editorial . ' ' . (!empty($bibliography->vol_no) ? $bibliography->vol_no.',' : '') . ' ' . (!empty($bibliography->pages) ? $bibliography->pages.'.' : '') . ' ' . $bibliography->identifier . ' ' . (!empty($bibliography->webpage) ? 'Recuperado de '.$bibliography->webpage : '');

                $templateProcessor->setValue('apa#' . $idx, $apa);

                $iso690 = $bibliography->author . ', ' . (!empty($bibliography->article) ? $bibliography->article : '') . ' ' . (!empty($bibliography->chapter) ? $bibliography->chapter : '') . ' ' . (!empty($bibliography->editor) ? 'En '.$bibliography->editor : '') . ' ' . $bibliography->title . '. ' . (!empty($bibliography->city_country) ? $bibliography->city_country : '') . ' ' .  (!empty($bibliography->city_country) && !empty($bibliography->editorial) ? ':' : '')  . ' ' . (!empty($bibliography->editorial) ? $bibliography->editorial.',':'') . ' ' .  $bibliography->publication_date . '. ' . (!empty($bibliography->vol_no) ? $bibliography->vol_no.',' : '') . ' ' . (!empty($bibliography->pages) ? $bibliography->pages.',' : '')  . ' ' . $bibliography->identifier . ' ' . (!empty($bibliography->webpage) ? 'Disponible en: '.$bibliography->webpage : '');


                $templateProcessor->setValue('iso#' .  $idx, $iso690);

                $referenceTypeCatalog = Catalog::where('code', 'reference_type')->get()->first();
                $referenceTypes = Catalog_element::where('catalog_id', $referenceTypeCatalog->id)->get();
                $referenceTypesArray = !is_null($referenceTypes) ? $referenceTypes->pluck('title', 'id')->toArray() : null;

                $tipoBib = ((int)$bibliography->reference_type_id > 0) ? $referenceTypesArray[$bibliography->reference_type_id] : 'Tipo de referencia no definido';
                $templateProcessor->setValue('tipoBib#' .  $idx, $tipoBib);
            }
        } else{
            $templateProcessor->setValue('numBib', '');
            $templateProcessor->setValue('chicago', '');
            $templateProcessor->setValue('apa', '');
            $templateProcessor->setValue('iso', '');
            $templateProcessor->setValue('tipoBib', '');
        }


        $documents = Document::where('piece_id', $piece->id)
            ->where('module_id', 2)
            ->get();

        $docs = '';
        foreach ($documents as $idx => $document){
            $docs .= $document->file_name . '</w:t><w:br/><w:t>';
        }

        $templateProcessor->setValue('daePieza', strlen($docs) <= 0 ? 'No se han asociado documentos' : $docs);

        $movements = Movement::whereRaw('FIND_IN_SET(?, pieces_ids)', [$piece->id])
            ->where('authorized_by_collections', '>', 0)
            ->orderBy('departure_date', 'desc')->get();


        $templateProcessor->cloneRow('mfsalida', count($movements));
        foreach ($movements as $idx => $movement){
            $idx = ($idx + 1);
            $templateProcessor->setValue('mfsalida#' . $idx, (!empty($movement->departure_date) ? $movement->departure_date->locale('es_MX')->isoFormat('LL') : ''));

            $arrivalDate = "";
            if(isset($movement->arrival_information) && !empty($movement->arrival_information)){
                $arrivalInformation = json_decode($movement->arrival_information);
                // se recorre la informacion de regreso
                foreach ($arrivalInformation as $datos) {
                    // se verifica si la pieza esta entre los datos actuales
                    if((is_array($datos->pieces) && in_array($piece->id, $datos->pieces)) || ($piece->id == $datos->pieces)){
                        $arrivalDate = \Carbon\Carbon::createFromFormat('Y-m-d', $datos->arrival_date)->locale('es_MX')->isoFormat('LL');
                        break;
                    }
                }
            } elseif(!empty($movement->arrival_date)){
                $arrivalDate = $movement->arrival_date->locale('es_MX')->isoFormat('LL');
            }

            $templateProcessor->setValue('mfentrada#' . $idx, $arrivalDate);

            $templateProcessor->setValue('minstitucion#' . $idx, (!is_null($movement->institutions) ? implode(", ", $movement->institutions->pluck('name')->toArray()) : '-'));

            $templateProcessor->setValue('mubicacion#' . $idx, $movement->exhibition['name']);

            $templateProcessor->setValue('msede#' . $idx, (!is_null($movement->venue) ? implode(", ", $movement->venue->pluck('name')->toArray()) : '-'));

        }

        $emptyImg = storage_path() . "/app/public/inventario/blank.png";

        //Fotos inventario
        $allPhotosInventory = array();

        foreach ($piece->photography as $idx => $photo) {

            $imagen = storage_path() . "/app/public/" . config('fileuploads.inventory.photographs.thumbnails') . "/" . $piece->photography[$idx]->file_name;
            if($piece->photography[$idx]->module_id == 1)
                $allPhotosInventory[] = $imagen;
        }


        $split = 4;
        $rowsArray = array_chunk($allPhotosInventory, $split);

        if(count($rowsArray) > 1)
            $templateProcessor->cloneRow('iimga', count($rowsArray));

        foreach ($rowsArray as $idx => $photo) {
            $idx = ($idx + 1);

                if(count($rowsArray) > 1){
                    $templateProcessor->setImageValue('iimga#' . $idx, isset($photo[0]) ? $photo[0] : $emptyImg);
                    $templateProcessor->setImageValue('iimgb#' . $idx, isset($photo[1]) ? $photo[1] : $emptyImg);
                    $templateProcessor->setImageValue('iimgc#' . $idx, isset($photo[2]) ? $photo[2] : $emptyImg);
                    $templateProcessor->setImageValue('iimgd#' . $idx, isset($photo[3]) ? $photo[3] : $emptyImg);                }
                else{
                    $templateProcessor->setImageValue('iimga', isset($photo[0]) ? $photo[0] : $emptyImg);
                    $templateProcessor->setImageValue('iimgb', isset($photo[1]) ? $photo[1] : $emptyImg);
                    $templateProcessor->setImageValue('iimgc', isset($photo[2]) ? $photo[2] : $emptyImg);
                    $templateProcessor->setImageValue('iimgd', isset($photo[3]) ? $photo[3] : $emptyImg);
                }
         }


       //Fotos investigación
        $allPhotosResearch = array();

        foreach ($piece->photography as $idx => $photo) {

            $imagen = storage_path() . "/app/public/" . config('fileuploads.research.photographs.thumbnails') . "/" . $piece->photography[$idx]->file_name;
            if($piece->photography[$idx]->module_id == 2)
                $allPhotosResearch[] = $imagen;
        }


        $split = 4;
        $rowsArray = array_chunk($allPhotosResearch, $split);

        if(count($rowsArray) > 1)
            $templateProcessor->cloneRow('inimga', count($rowsArray));

        foreach ($rowsArray as $idx => $photo) {
            $idx = ($idx + 1);

                if(count($rowsArray) > 1){
                    $templateProcessor->setImageValue('inimga#' . $idx, isset($photo[0]) ? $photo[0] : $emptyImg);
                    $templateProcessor->setImageValue('inimgb#' . $idx, isset($photo[1]) ? $photo[1] : $emptyImg);
                    $templateProcessor->setImageValue('inimgc#' . $idx, isset($photo[2]) ? $photo[2] : $emptyImg);
                    $templateProcessor->setImageValue('inimgd#' . $idx, isset($photo[3]) ? $photo[3] : $emptyImg);                }
                else{
                    $templateProcessor->setImageValue('inimga', isset($photo[0]) ? $photo[0] : $emptyImg);
                    $templateProcessor->setImageValue('inimgb', isset($photo[1]) ? $photo[1] : $emptyImg);
                    $templateProcessor->setImageValue('inimgc', isset($photo[2]) ? $photo[2] : $emptyImg);
                    $templateProcessor->setImageValue('inimgd', isset($photo[3]) ? $photo[3] : $emptyImg);
                }
         }


       //Fotos restauración
        $allPhotosRestoration = array();

        foreach ($piece->photography as $idx => $photo) {

            $imagen = storage_path() . "/app/public/" . config('fileuploads.restoration.photographs.thumbnails') . "/" . $piece->photography[$idx]->file_name;
            if($piece->photography[$idx]->module_id == 3)
                $allPhotosRestoration[] = $imagen;
        }


        $split = 4;
        $rowsArray = array_chunk($allPhotosRestoration, $split);

        if(count($rowsArray) > 1)
            $templateProcessor->cloneRow('rimga', count($rowsArray));

        foreach ($rowsArray as $idx => $photo) {
            $idx = ($idx + 1);

                if(count($rowsArray) > 1){
                    $templateProcessor->setImageValue('rimga#' . $idx, isset($photo[0]) ? $photo[0] : $emptyImg);
                    $templateProcessor->setImageValue('rimgb#' . $idx, isset($photo[1]) ? $photo[1] : $emptyImg);
                    $templateProcessor->setImageValue('rimgc#' . $idx, isset($photo[2]) ? $photo[2] : $emptyImg);
                    $templateProcessor->setImageValue('rimgd#' . $idx, isset($photo[3]) ? $photo[3] : $emptyImg);                }
                else{
                    $templateProcessor->setImageValue('rimga', isset($photo[0]) ? $photo[0] : $emptyImg);
                    $templateProcessor->setImageValue('rimgb', isset($photo[1]) ? $photo[1] : $emptyImg);
                    $templateProcessor->setImageValue('rimgc', isset($photo[2]) ? $photo[2] : $emptyImg);
                    $templateProcessor->setImageValue('rimgd', isset($photo[3]) ? $photo[3] : $emptyImg);
                }
         }

        //Poner imagen por defecto si no hay imagenes
        if(count($allPhotosInventory) == 0){
            $templateProcessor->setImageValue('iimga', $emptyImg);
            $templateProcessor->setImageValue('iimgb', $emptyImg);
            $templateProcessor->setImageValue('iimgc', $emptyImg);
            $templateProcessor->setImageValue('iimgd', $emptyImg);
        }

        if(count($allPhotosResearch) == 0){
            $templateProcessor->setImageValue('inimga', $emptyImg);
            $templateProcessor->setImageValue('inimgb', $emptyImg);
            $templateProcessor->setImageValue('inimgc', $emptyImg);
            $templateProcessor->setImageValue('inimgd', $emptyImg);
        }

        if(count($allPhotosRestoration) == 0){
            $templateProcessor->setImageValue('rimga', $emptyImg);
            $templateProcessor->setImageValue('rimgc', $emptyImg);
            $templateProcessor->setImageValue('rimgd', $emptyImg);
            $templateProcessor->setImageValue('rimgb', $emptyImg);
        }

        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename=piece_" . $piece->inventory_number . ".docx");
        $templateProcessor->saveAs('php://output');

    }

    public function excel($id){
        $piece = Piece::findOrFail($id);

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(storage_path('app/reportes/detalle.xlsx'));

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A3', 'No. Inventario: ' . $piece->inventory_number);
        $sheet->setCellValue('D3', 'No. catálogo: ' . $piece->catalog_number);
        $sheet->setCellValue('F3', 'No. procedencia: ' . $piece->origin_number);
        $sheet->setCellValue('A5', htmlspecialchars($piece->description_origin));

        $gender = isset($piece->gender->title) ? $piece->gender->title : '';
        $sheet->setCellValue('A6', 'Género: ' . $gender);

        $subgender = isset($piece->subgender->title) ? $piece->subgender->title : '';
        $sheet->setCellValue('D6', 'Subgénero: ' . $subgender);

        $type_object = isset($piece->type_object->title) ? $piece->type_object->title : '';
        $sheet->setCellValue('F6', 'Tipo de objeto: ' . $type_object);

        $sheet->setCellValue('A7', $piece->tags);
        $sheet->setCellValue('A8', 'Avalúo: $ ' . number_format($piece->appraisal, 2) . ' USD');

        $location = isset($piece->location->name) ? $piece->location->name : '';
        $sheet->setCellValue('D8', 'Ubicación: ' . $location);

        $admitted_at = isset($piece->admitted_at) ? $piece->admitted_at->diffForHumans() : "N/A";
        $sheet->setCellValue('F8', 'Fecha de ingreso: ' . $admitted_at);

        $sheet->setCellValue('A11', $piece->height);
        $sheet->setCellValue('C11', $piece->width);
        $sheet->setCellValue('E11', $piece->depth);
        $sheet->setCellValue('F11', $piece->diameter);

        $sheet->setCellValue('A14', $piece->height_with_base);
        $sheet->setCellValue('C14', $piece->width_with_base);
        $sheet->setCellValue('E14', $piece->depth_with_base);
        $sheet->setCellValue('F14', $piece->diameter_with_base);


        $sheet->setCellValue('A22', 'Título: ' . (isset($piece->research)? $piece->research->title: 'N/A'));


        $authorNames = null;
        $bibliographs = array();
        if(!is_null($piece->research)){
            $authors = Catalog_element::find(explode(",", $piece->research->author_ids));
            $authorNames = $authors->pluck('title');

            $bibliographs = Bibliography::where('research_id', $piece->research->id)->get();
        }

        $sheet->setCellValue('F22', 'Autor(es): ' . (isset($authorNames)? $authorNames->join(", "): 'N/A'));


        $sheet->setCellValue('A23', 'Conjunto:  ' . (isset($piece->research->set) ? $piece->research->set->title: 'N/A'));
        $sheet->setCellValue('A25', htmlspecialchars(isset($piece->research->technique) ? $piece->research->technique: ''));
        $sheet->setCellValue('A27', htmlspecialchars(isset($piece->research->materials) ? $piece->research->materials : ''));
        $sheet->setCellValue('A28', 'Procedencia: ' . (isset($piece->research->place_of_creation->title)? $piece->research->place_of_creation->title: 'N/A'));
        $sheet->setCellValue('G28', 'Fecha de creación: ' . (isset($piece->research->creation_date)? $piece->research->creation_date: 'N/A'));
        $sheet->setCellValue('A29', 'Epoca: ' . (isset($piece->research->period->title)? $piece->research->period->title: 'N/A'));
        $sheet->setCellValue('A31', 'Forma: ' . (isset($piece->research->acquisition_form)? $piece->research->acquisition_form: 'N/A'));
        $sheet->setCellValue('A32', 'Fuente/lugar: ' . (isset($piece->research->acquisition_source)? $piece->research->acquisition_source: 'N/A'));
        $sheet->setCellValue('A33', 'Fecha: ' . (isset($piece->research->acquisition_date)? $piece->research->acquisition_date: 'N/A'));

        $sheet->setCellValue('A35', (bool)isset($piece->research->firm) ? 'Sí' : 'No');
        $sheet->setCellValue('B36', htmlspecialchars(isset($piece->research->firm_description)));
        $sheet->setCellValue('A38', htmlspecialchars(isset($piece->research->short_description)));
        $sheet->setCellValue('A40', htmlspecialchars(isset($piece->research->formal_description)));
        $sheet->setCellValue('A42', htmlspecialchars(isset($piece->research->observation)));
        $sheet->setCellValue('A44', htmlspecialchars(isset($piece->research->publications)));


        $footnotes = null;
        $authorNames = null;
        if(!is_null($piece->research)){
            $footnotes = Footnote::where('research_id', $piece->research->id)->get();
        }

        $fns = array();
        if(!is_null($footnotes)){
            foreach ($footnotes as $idx => $footnote){
                $fns[] = $footnote;
            }
        }

        $sheet->setCellValue('A46', count($fns) > 0 ? implode('\n', $fns) : count($fns) == 1 ? $fns[0] : "");

        $rowBibs = 66;
        $movements = Movement::whereRaw('FIND_IN_SET(?, pieces_ids)', [$piece->id])
            ->where('authorized_by_collections', '>', 0)
            ->orderBy('departure_date', 'desc')->get();

        if(count($movements) > 0)
            $sheet->insertNewRowBefore($rowBibs, count($movements));

        foreach ($movements as $idx => $movement){
            $idx = ($idx + 1);
            $sheet->setCellValue('A' . ($rowBibs +  $idx), (!empty($movement->departure_date) ? $movement->departure_date->locale('es_MX')->isoFormat('LL') : ''));
            $sheet->getStyle('A' . ($rowBibs +  $idx))->getFont()->setBold(false);

            $arrivalDate = "";
            if(isset($movement->arrival_information) && !empty($movement->arrival_information)){
                $arrivalInformation = json_decode($movement->arrival_information);
                // se recorre la informacion de regreso
                foreach ($arrivalInformation as $datos) {
                    // se verifica si la pieza esta entre los datos actuales
                    if((is_array($datos->pieces) && in_array($piece->id, $datos->pieces)) || ($piece->id == $datos->pieces)){
                        $arrivalDate = \Carbon\Carbon::createFromFormat('Y-m-d', $datos->arrival_date)->locale('es_MX')->isoFormat('LL');
                        break;
                    }
                }
            } elseif(!empty($movement->arrival_date)){
                $arrivalDate = $movement->arrival_date->locale('es_MX')->isoFormat('LL');
            }

            $sheet->setCellValue('B' . ($rowBibs +  $idx), $arrivalDate);
            $sheet->getStyle('B' . ($rowBibs +  $idx))->getFont()->setBold(false);

            $sheet->setCellValue('C' . ($rowBibs +  $idx), (!is_null($movement->institutions) ? implode(", ", $movement->institutions->pluck('name')->toArray()) : '-'));
            $sheet->getStyle('C' . ($rowBibs +  $idx))->getFont()->setBold(false);

            $sheet->setCellValue('E' . ($rowBibs +  $idx), $movement->exhibition['name']);
            $sheet->getStyle('E' . ($rowBibs +  $idx))->getFont()->setBold(false);

            $sheet->setCellValue('G' . ($rowBibs +  $idx), (!is_null($movement->venue) ? implode(", ", $movement->venue->pluck('name')->toArray()) : '-'));
            $sheet->getStyle('G' . ($rowBibs +  $idx))->getFont()->setBold(false);

        }


        $emptyImg = storage_path() . "/app/public/inventario/blank.png";


       //Fotos restauración
        $allPhotosRestoration = array();

        foreach ($piece->photography as $idx => $photo) {

            $imagen = storage_path() . "/app/public/" . config('fileuploads.restoration.photographs.thumbnails') . "/" . $piece->photography[$idx]->file_name;
            if($piece->photography[$idx]->module_id == 3)
                $allPhotosRestoration[] = $imagen;
        }


        $split = 4;
        $rowsArray = array_chunk($allPhotosRestoration, $split);

        $rowImg = 59;

        if(count($rowsArray) > 0)
            $sheet->insertNewRowBefore($rowImg, count($rowsArray));


        foreach ($rowsArray as $idx => $photo) {
            $objDrawing = new drawing();
            $objDrawing->setName('A3img' . $idx);
            $objDrawing->setDescription('A3img' . $idx);
            $objDrawing->setPath(isset($photo[0]) ? $photo[0] : $emptyImg);
            $objDrawing->setCoordinates('A' . ($rowImg + $idx));
            $objDrawing->setOffsetX(5);
            $objDrawing->setOffsetY(5);
            $objDrawing->setWorksheet($sheet);

            $objDrawing = new drawing();
            $objDrawing->setName('C3img' . $idx);
            $objDrawing->setDescription('C3img' . $idx);
            $objDrawing->setPath(isset($photo[1]) ? $photo[1] : $emptyImg);
            $objDrawing->setCoordinates('C' . ($rowImg + $idx));
            $objDrawing->setOffsetX(5);
            $objDrawing->setOffsetY(5);
            $objDrawing->setWorksheet($sheet);

            $objDrawing = new drawing();
            $objDrawing->setName('E3img' . $idx);
            $objDrawing->setDescription('E3img' . $idx);
            $objDrawing->setPath(isset($photo[2]) ? $photo[2] : $emptyImg);
            $objDrawing->setCoordinates('E' . ($rowImg + $idx));
            $objDrawing->setOffsetX(5);
            $objDrawing->setOffsetY(5);
            $objDrawing->setWorksheet($sheet);

            $objDrawing = new drawing();
            $objDrawing->setName('G3img' . $idx);
            $objDrawing->setDescription('G3img' . $idx);
            $objDrawing->setPath(isset($photo[3]) ? $photo[3] : $emptyImg);
            $objDrawing->setCoordinates('G' . ($rowImg + $idx));
            $objDrawing->setOffsetX(5);
            $objDrawing->setOffsetY(5);
            $objDrawing->setWorksheet($sheet);
        }
        $sheet->removeRow(58);


    //Fotos investigación
        $allPhotosResearch = array();

        foreach ($piece->photography as $idx => $photo) {

            $imagen = storage_path() . "/app/public/" . config('fileuploads.research.photographs.thumbnails') . "/" . $piece->photography[$idx]->file_name;
            if($piece->photography[$idx]->module_id == 2)
                $allPhotosResearch[] = $imagen;
        }


        $split = 4;
        $rowsArray = array_chunk($allPhotosResearch, $split);

        $rowImg = 53;

        if(count($rowsArray) > 0)
            $sheet->insertNewRowBefore($rowImg, count($rowsArray));

        foreach ($rowsArray as $idx => $photo) {
            $objDrawing = new drawing();
            $objDrawing->setName('A2img' . $idx);
            $objDrawing->setDescription('A2img' . $idx);
            $objDrawing->setPath(isset($photo[0]) ? $photo[0] : $emptyImg);
            $objDrawing->setCoordinates('A' . ($rowImg + $idx));
            $objDrawing->setOffsetX(5);
            $objDrawing->setOffsetY(5);
            $objDrawing->setWorksheet($sheet);

            $objDrawing = new drawing();
            $objDrawing->setName('C2img' . $idx);
            $objDrawing->setDescription('C2img' . $idx);
            $objDrawing->setPath(isset($photo[1]) ? $photo[1] : $emptyImg);
            $objDrawing->setCoordinates('C' . ($rowImg + $idx));
            $objDrawing->setOffsetX(5);
            $objDrawing->setOffsetY(5);
            $objDrawing->setWorksheet($sheet);

            $objDrawing = new drawing();
            $objDrawing->setName('E2img' . $idx);
            $objDrawing->setDescription('E2img' . $idx);
            $objDrawing->setPath(isset($photo[2]) ? $photo[2] : $emptyImg);
            $objDrawing->setCoordinates('E' . ($rowImg + $idx));
            $objDrawing->setOffsetX(5);
            $objDrawing->setOffsetY(5);
            $objDrawing->setWorksheet($sheet);

            $objDrawing = new drawing();
            $objDrawing->setName('G2img' . $idx);
            $objDrawing->setDescription('G2img' . $idx);
            $objDrawing->setPath(isset($photo[3]) ? $photo[3] : $emptyImg);
            $objDrawing->setCoordinates('G' . ($rowImg + $idx));
            $objDrawing->setOffsetX(5);
            $objDrawing->setOffsetY(5);
            $objDrawing->setWorksheet($sheet);

        }
        $sheet->removeRow(52);


        $rowBibs = 50;
        if(count($bibliographs) > 0)
            $sheet->insertNewRowBefore($rowBibs , count($bibliographs));

        foreach ($bibliographs as $idx => $bibliography){
            $sheet->setCellValue('A' . ($rowBibs +  $idx), ($idx +  1));
            $chicago = $bibliography->author . ', ' . (!empty($bibliography->article) ? '"'.$bibliography->article.'"' : '') . (!empty($bibliography->chapter) ? '"'.$bibliography->chapter.'"' : '') . (!empty($bibliography->editor) ? 'En '.$bibliography->editor : '') . ('' .$bibliography->title . '') . (!empty($bibliography->pages) ? $bibliography->pages.',' : '') . '. ' .  (!empty($bibliography->city_country) ? $bibliography->city_country : '') . (!empty($bibliography->city_country) && !empty($bibliography->editorial) ? ' : ' : '') . (!empty($bibliography->editorial) ? $bibliography->editorial.',':'') . (!empty($bibliography->vol_no) ? $bibliography->vol_no.',' : '') . ($bibliography->publication_date) . ($bibliography->identifier) . (!empty($bibliography->webpage) ? $bibliography->webpage : '');

            $sheet->setCellValue('B' . ($rowBibs +  $idx), $chicago);

            $apa = $bibliography->author . ' (' . $bibliography->publication_date . '). ' . $bibliography->article . ' ' . $bibliography->chapter . ' ' . (!empty($bibliography->editor) ? 'En '.$bibliography->editor : '') . ' ' . $bibliography->title . '. ' . (!empty($bibliography->city_country) ? $bibliography->city_country : '') . ' ' . (!empty($bibliography->city_country) && !empty($bibliography->editorial) ? ':' : '')  . ' ' . $bibliography->editorial . ' ' . (!empty($bibliography->vol_no) ? $bibliography->vol_no.',' : '') . ' ' . (!empty($bibliography->pages) ? $bibliography->pages.'.' : '') . ' ' . $bibliography->identifier . ' ' . (!empty($bibliography->webpage) ? 'Recuperado de '.$bibliography->webpage : '');

            $sheet->setCellValue('D' . ($rowBibs +  $idx), $apa);

            $iso690 = $bibliography->author . ', ' . (!empty($bibliography->article) ? $bibliography->article : '') . ' ' . (!empty($bibliography->chapter) ? $bibliography->chapter : '') . ' ' . (!empty($bibliography->editor) ? 'En '.$bibliography->editor : '') . ' ' . $bibliography->title . '. ' . (!empty($bibliography->city_country) ? $bibliography->city_country : '') . ' ' .  (!empty($bibliography->city_country) && !empty($bibliography->editorial) ? ':' : '')  . ' ' . (!empty($bibliography->editorial) ? $bibliography->editorial.',':'') . ' ' .  $bibliography->publication_date . '. ' . (!empty($bibliography->vol_no) ? $bibliography->vol_no.',' : '') . ' ' . (!empty($bibliography->pages) ? $bibliography->pages.',' : '')  . ' ' . $bibliography->identifier . ' ' . (!empty($bibliography->webpage) ? 'Disponible en: '.$bibliography->webpage : '');


            $sheet->setCellValue('E' . ($rowBibs +  $idx), $iso690);

            $referenceTypeCatalog = Catalog::where('code', 'reference_type')->get()->first();
            $referenceTypes = Catalog_element::where('catalog_id', $referenceTypeCatalog->id)->get();
            $referenceTypesArray = !is_null($referenceTypes) ? $referenceTypes->pluck('title', 'id')->toArray() : null;

            $tipoBib = ((int)$bibliography->reference_type_id > 0) ? $referenceTypesArray[$bibliography->reference_type_id] : 'Tipo de referencia no definido';
            $sheet->setCellValue('F' . ($rowBibs +  $idx), $tipoBib);
        }
        $sheet->removeRow(49);

/*
        $documents = Document::where('piece_id', $piece->id)
            ->where('module_id', 2)
            ->get();

        $docs = '';
        foreach ($documents as $idx => $document){
            $docs .= $document->file_name . '</w:t><w:br/><w:t>';
        }

        $templateProcessor->setValue('daePieza', strlen($docs) <= 0 ? 'No se han asociado documentos' : $docs);
*/


       //Fotos inventario
        $allPhotosInventory = array();

        foreach ($piece->photography as $idx => $photo) {

            $imagen = storage_path() . "/app/public/" . config('fileuploads.inventory.photographs.thumbnails') . "/" . $piece->photography[$idx]->file_name;
            if($piece->photography[$idx]->module_id == 1)
                $allPhotosInventory[] = $imagen;
        }


        $split = 4;
        $rowsArray = array_chunk($allPhotosInventory, $split);

        $rowImg = 17;
        if(count($rowsArray) > 0)
        $sheet->insertNewRowBefore($rowImg, count($rowsArray));


        foreach ($rowsArray as $idx => $photo) {

            $objDrawing = new drawing();
            $objDrawing->setName('Aimg' . $idx);
            $objDrawing->setDescription('Aimg' . $idx);
            $objDrawing->setPath(isset($photo[0]) ? $photo[0] : $emptyImg);
            $objDrawing->setCoordinates('A' . ($rowImg + $idx));
            $objDrawing->setOffsetX(5);
            $objDrawing->setOffsetY(5);
            $objDrawing->setWorksheet($sheet);
            $sheet->duplicateStyle($sheet->getStyle('A'. $rowImg),'A' . ($rowImg + $idx));

            $objDrawing = new drawing();
            $objDrawing->setName('Cimg' . $idx);
            $objDrawing->setDescription('Cimg' . $idx);
            $objDrawing->setPath(isset($photo[1]) ? $photo[1] : $emptyImg);
            $objDrawing->setCoordinates('C' . ($rowImg + $idx));
            $objDrawing->setOffsetX(5);
            $objDrawing->setOffsetY(5);
            $objDrawing->setWorksheet($sheet);

            $objDrawing = new drawing();
            $objDrawing->setName('Eimg' . $idx);
            $objDrawing->setDescription('Eimg' . $idx);
            $objDrawing->setPath(isset($photo[2]) ? $photo[2] : $emptyImg);
            $objDrawing->setCoordinates('E' . ($rowImg + $idx));
            $objDrawing->setOffsetX(5);
            $objDrawing->setOffsetY(5);
            $objDrawing->setWorksheet($sheet);

            $objDrawing = new drawing();
            $objDrawing->setName('Gimg' . $idx);
            $objDrawing->setDescription('Gimg' . $idx);
            $objDrawing->setPath(isset($photo[3]) ? $photo[3] : $emptyImg);
            $objDrawing->setCoordinates('G' . ($rowImg + $idx));
            $objDrawing->setOffsetX(5);
            $objDrawing->setOffsetY(5);
            $objDrawing->setWorksheet($sheet);

         }
        $sheet->removeRow(16);

        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename=piece_" . $piece->inventory_number . ".xlsx");
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');

        // $writer->save(storage_path('app/reportes/detalle_prueba.xlsx'));
    }

    public function prettyXml($simpleXMLElement) {
        $xmlDocument = new \DOMDocument('1.0');
        $xmlDocument->preserveWhiteSpace = false;
        $xmlDocument->formatOutput = true;
        $xmlDocument->loadXML($simpleXMLElement->asXML());

        return $xmlDocument->saveXML();
    }

    public function search($keywords = null){
        if(is_null($keywords)){
            $keywords = request('keywords');
        }
        session(['keywords' => $keywords]);
        return redirect()->route('consultas');
    }
}

<?php

namespace Mnemosine\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mnemosine\DataTables\PieceDataTable;
use Mnemosine\Http\Controllers\Files\Photos;
use Mnemosine\Http\Controllers\Files\Documents;
use Mnemosine\Piece;
use Mnemosine\Gender;
use Mnemosine\Subgender;
use Mnemosine\Catalog;
use Mnemosine\Catalog_element;
use Mnemosine\Bibliography;
use Mnemosine\Authorizable;
use Mnemosine\Photography;
use Mnemosine\Document;
use Mnemosine\Module;
use Mnemosine\Restoration;
use Mnemosine\Resource;

class Restauracion extends Controller
{
    use Authorizable;

    protected $moduleName = "restauracion";

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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PieceDataTable $dataTable)
    {
        $dataTable->setCalledByModule($this->moduleName, $this->getModuleId(), 'restoration');
        return $dataTable->render('restauracion.index');
    }

    /**
    * Show the form for creating a new resource. In this case we need to know the piece $id
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function create($pieceId)
    {
        $piece = Piece::findOrFail($pieceId);

        $gender = (!is_null($piece->gender_id)) ? Gender::find($piece->gender_id) : null;
        $subgender = (!is_null($piece->subgender_id)) ? Subgender::find($piece->subgender_id) : null;
        $objectType = Catalog_element::where('catalog_id', $piece->type_object_id)->get()->first();

        $responsibleRestorerCatalog = Catalog::where('code', 'responsible_restorer')->get()->first();
        $responsibleRestorers = Catalog_element::where('catalog_id', $responsibleRestorerCatalog->id)->get();

        return view('restauracion.create', compact('piece', 'gender', 'subgender', 'objectType', 'responsibleRestorers'));
    }

    /**
     * Show the form for editing the specified resource.
     * Receives id of restoration
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $restoration = Restoration::findOrFail($id);
        $piece = Piece::findOrFail($restoration->piece_id);

        $photographs = (!empty($restoration->photographs_ids)) ? Photography::find(explode(",", $restoration->photographs_ids)) : null;
        $documents = (!is_null($restoration->documents_ids) && !empty($restoration->documents_ids)) ? Document::find(explode(",", $restoration->documents_ids)) : null;

        $gender = (!is_null($piece->gender_id)) ? Gender::find($piece->gender_id) : null;
        $subgender = (!is_null($piece->subgender_id)) ? Subgender::find($piece->subgender_id) : null;
        $objectType = Catalog_element::where('catalog_id', $piece->type_object_id)->get()->first();

        $responsibleRestorerCatalog = Catalog::where('code', 'responsible_restorer')->get()->first();
        $responsibleRestorers = Catalog_element::where('catalog_id', $responsibleRestorerCatalog->id)->get();

        return view('restauracion.edit', compact('piece', 'gender', 'subgender', 'objectType', 'restoration', 'photographs', 'documents', 'responsibleRestorers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateRequest($request);
        $restoration = new Restoration();
        $restoration->fill($request->only('piece_id', 'preliminary_examination', 'laboratory_analysis', 'proposal_of_treatment', 'treatment_description', 'results', 'observations', 'treatment_date', 'base_or_frame', 'height', 'width', 'depth', 'diameter', 'height_with_base', 'width_with_base', 'depth_with_base', 'diameter_with_base', 'responsible_restorer'));

        // update table with DB to avoid ActionBy trait
        DB::table('pieces')
            ->where('id', $restoration->piece_id)
            ->update([
                'restoration_info' => 1,
            ]);

        $newDocPhotosIds = $this->savePhotosAndDocuments($request, $restoration->piece_id);

        $restoration->documents_ids = $newDocPhotosIds['documents'];
        $restoration->photographs_ids = $newDocPhotosIds['photographs'];
        $restoration->save();

        flash()->success('Se creo correctamente el registro de restauración para la pieza.');

        return redirect()->route('restauracion.listRecords', ['pieceId' => $restoration->piece_id]);
    }

    /**
     * Update the specified resource in storage.
     * Receives id of restoration
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validateRequest($request, $id);
        $restoration = Restoration::find($id);
        $restoration->fill($request->only('piece_id', 'preliminary_examination', 'laboratory_analysis', 'proposal_of_treatment', 'treatment_description', 'results', 'observations', 'treatment_date', 'base_or_frame', 'height', 'width', 'depth', 'diameter', 'height_with_base', 'width_with_base', 'depth_with_base', 'diameter_with_base', 'responsible_restorer'));

        $docPhotosIds['documents'] = $restoration->documents_ids;
        $docPhotosIds['photographs'] = $restoration->photographs_ids;

        $newDocPhotosIds = $this->savePhotosAndDocuments($request, $restoration->piece_id, $docPhotosIds);

        $restoration->documents_ids = $newDocPhotosIds['documents'];
        $restoration->photographs_ids = $newDocPhotosIds['photographs'];
        $restoration->save();

        flash()->success('Se editaron correctamente los datos de restauración para la pieza.');

        return redirect()->route('restauracion.listRecords', ['pieceId' => $restoration->piece_id]);
    }

    /**
     * Remove the specified resource from storage.
     * Receives id of restoration
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $restoration = Restoration::findOrFail($id);

        if(!empty($restoration->photographs_ids)){
            $photos = new Photos();
            // delete photographs records
            $photographs = Photography::find(explode(",", $restoration->photographs_ids));
            if(count($photographs) > 0){
                foreach ($photographs as $photography) {
                    // delete the file
                    $photos->deletePhotoFromDisk($photography->file_name, 'restoration');

                    flash()->warning('Se ha eliminado la foto: '.$photography->description.' ('.$photography->file_name.')');
                    // se elimina el registro en la base de datos
                    $photography->Delete();
                }
            }
        }

        if(!empty($restoration->documents_ids)){
            $documentsController = new Documents();
            // delete documents records
            $documentsModel = Document::find(explode(",", $restoration->documents_ids));
            if(count($documentsModel) > 0){
                foreach ($documentsModel as $document) {
                    // delete the file
                    $documentsController->deleteDocumentFromDisk($document->file_name, 'restoration');

                    flash()->warning('Se ha eliminado el documento: '.$document->name.' ('.$document->file_name.')');
                    // se elimina el registro en la base de datos
                    $document->Delete();
                }
            }
        }

        if ($restoration) {
            $restoration->Delete();
            flash()->success('Se elimino correctamente el registro de restauración.');
        } else{
            flash()->warning('Error al eliminar ');
        }

        // change flag if piece doesn't have any restorations
        $restorationsCount = Restoration::where('piece_id', $restoration->piece_id)->count();
        if($restorationsCount == 0){
            // update table with DB to avoid ActionBy trait
            DB::table('pieces')
                ->where('id', $restoration->piece_id)
                ->update([ 'restoration_info' => 0 ]);

            return redirect()->route('restauracion.index');
        }

        return redirect()->route('restauracion.listRecords', ['pieceId' => $restoration->piece_id]);
    }

    /**
    * Show the list of researchs for the piece
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function listRecords($pieceId)
    {
        $piece = Piece::find($pieceId);
        $restorations = Restoration::where('piece_id', $pieceId)->orderBy('id', 'desc')->get();
        $photographs = Photography::where('piece_id', $pieceId)->where('module_id', $this->getModuleId())->get();
        return view('restauracion/list-records', compact('piece', 'restorations', 'photographs'));
    }

    /**
     * Auxiliar method for store and update methods, it saves files and to DB
     *
     * @param  Request  $request
     * @param  int  $piece_id
     * @param  array  $docPhotosIds
     * @return array  Contains the new documents and photographs ids
     */
    private function savePhotosAndDocuments(Request $request, $piece_id, $docPhotosIds = null){
        $newDocPhotosIds = array();
        if(is_null($docPhotosIds)){
            $documentsIds = array();
            $photographsIds = array();
        } else{
            // explode given strings
            $documentsIds = explode(",", trim($docPhotosIds['documents']));
            $photographsIds = explode(",", trim($docPhotosIds['photographs']));
        }
        $photos = new Photos();
        // se verifica si hay que eliminar fotos
        if(!is_null($request->input('photo_ids_bd_deleted'))){
            // vienen los ids separados por comas
            $idsDeleted = explode(",", $request->input('photo_ids_bd_deleted'));
            foreach ($idsDeleted as $idx => $idDeleted) {
                // se obtiene el model con el id
                $photography = Photography::find($idDeleted);
                // delete the file
                $photos->deletePhotoFromDisk($photography->file_name, 'restoration');

                // notify the user
                flash()->warning('Se ha eliminado la foto: '.$photography->description.' ('.$photography->file_name.')');

                // delete record from database
                $photography->Delete();
            }
        }

        // se guardan las fotos
        foreach ($request->photo_id_bd as $idx => $photo_id_bd) {
            if(!is_null($photo_id_bd) && isset($request->file('photo_file')[$idx]) && $request->hasFile('photo_file')){
                // Reemplazo de imagen
                $photography = Photography::find($photo_id_bd);

                // delete the file
                $photos->deletePhotoFromDisk($photography->file_name, 'restoration');

                flash()->warning('Se ha cambiado la foto: '.$request->photo_description[$idx].' ('.$photography->file_name.')');

                // let's save the photo
                $photography->file_name = $photos->savePhotoToDisk($request->file('photo_file')[$idx], 'restoration');
            } elseif (!is_null($photo_id_bd) && !isset($request->file('photo_file')[$idx])) {
                // Actualizacion de datos
                $photography = Photography::find($photo_id_bd);
            } elseif (is_null($photo_id_bd) && isset($request->file('photo_file')[$idx]) && $request->hasFile('photo_file')) {
                // Nuevo
                $photography = new Photography();

                // let's save the photo
                $photography->file_name = $photos->savePhotoToDisk($request->file('photo_file')[$idx], 'restoration');
            } else{
                continue;
            }
            $photography->module_id = $this->getModuleId();
            $photos->setPhotoValues($photography, $request, $idx, $piece_id);
            $photography->save();
            $photographsIds[] = $photography->id;
        }
        $newDocPhotosIds['photographs'] = trim(implode(",", array_unique($photographsIds)), ",");

        /* =================================================================================
        *           DOCUMENTOS
        * =================================================================================*/
        $documents = new Documents();
        // se verifica si hay que eliminar documentos
        if(!is_null($request->input('document_ids_bd_deleted'))){
            // vienen los ids separados por comas
            $idsDeleted = explode(",", $request->input('document_ids_bd_deleted'));
            foreach ($idsDeleted as $idx => $idDeleted) {
                // se obtiene el model con el id
                $document = Document::find($idDeleted);
                // delete the file
                $documents->deleteDocumentFromDisk($document->file_name, 'restoration');

                // notify the user
                flash()->warning('Se ha eliminado el documento: '.$document->name.' ('.$document->file_name.')');

                // delete record from database
                $document->Delete();
            }
        }

        // se guardan las documentos
        foreach ($request->document_id_bd as $idx => $document_id_bd) {
            if(!is_null($document_id_bd) && isset($request->file('document_file')[$idx]) && $request->hasFile('document_file')){
                // Reemplazo de documento
                $document = Document::find($document_id_bd);

                // delete the file
                $documents->deleteDocumentFromDisk($document->file_name, 'restoration');

                flash()->warning('Se ha cambiado el documento: '.$request->document_name[$idx].' ('.$document->file_name.')');

                // let's save the document
                $document->file_name = $documents->saveDocumentToDisk($request->file('document_file')[$idx], 'restoration');
            } elseif (!is_null($document_id_bd) && !isset($request->file('document_file')[$idx])) {
                // Actualizacion de datos
                $document = Document::find($document_id_bd);
            } elseif (is_null($document_id_bd) && isset($request->file('document_file')[$idx]) && $request->hasFile('document_file')) {
                // Nuevo
                $document = new Document();

                // let's save the document
                $document->file_name = $documents->saveDocumentToDisk($request->file('document_file')[$idx], 'restoration');
            } else{
                continue;
            }
            $document->module_id = $this->getModuleId();
            $documents->setDocumentValues($document, $request, $idx, $piece_id);
            $document->save();
            $documentsIds[] = $document->id;
        }
        $newDocPhotosIds['documents'] = trim(implode(",", array_unique($documentsIds)), ",");

        return $newDocPhotosIds;
    }

    public function validateRequest(Request $request, $id = null)
    {
        $this->validate($request, [
            'responsible_restorer' => 'required',
            'treatment_date' => 'required',
            'photo_file' => 'nullable',
            'photo_file.*' => 'nullable|image|mimes:jpeg,jpg,png,tif,tiff,gif,bmp,svg|max:' . config('fileuploads.restoration.photographs.maximum_size'),
        ]);
    }
}

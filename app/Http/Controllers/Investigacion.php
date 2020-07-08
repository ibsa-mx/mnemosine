<?php

namespace Mnemosine\Http\Controllers;

use Illuminate\Http\Request;
use Mnemosine\DataTables\PieceDataTable;
use Mnemosine\Http\Controllers\Files\Photos;
use Mnemosine\Http\Controllers\Files\Documents;
use Illuminate\Support\Facades\DB;
use Mnemosine\Piece;
use Mnemosine\Gender;
use Mnemosine\Subgender;
use Mnemosine\Catalog;
use Mnemosine\Catalog_element;
use Mnemosine\Research;
use Mnemosine\Bibliography;
use Mnemosine\Footnote;
use Mnemosine\Authorizable;
use Mnemosine\Photography;
use Mnemosine\Document;
use Mnemosine\Module;

class Investigacion extends Controller
{
    use Authorizable;

    protected $moduleName = "investigacion";

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
        $dataTable->setCalledByModule($this->moduleName, $this->getModuleId(), 'research');
        return $dataTable->render('investigacion.index');
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create($id)
    {
        return $this->edit($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $piece = Piece::findOrFail($id);
        $research = Research::where('piece_id', $id)->get()->first();
        if(isset($research)) $research->author_ids = explode(",", $research->author_ids);
        $photographs = Photography::where('piece_id', $piece->id)
            ->where('module_id', $this->getModuleId())
            ->get();
        $documents = Document::where('piece_id', $piece->id)
            ->where('module_id', $this->getModuleId())
            ->get();
        $bibliographs = isset($research) ? Bibliography::where('research_id', $research->id)->get() : null;
        $footnotes = isset($research) ? Footnote::where('research_id', $research->id)->get() : null;
        $genders = Gender::orderBy('title')->get();

        $objectTypeCatalog = Catalog::where('code', 'object_type')->get()->first();
        $objectTypes = Catalog_element::where('catalog_id', $objectTypeCatalog->id)->get();

        $subgender = ($piece->gender_id) ? Subgender::where('gender_id', $piece->gender_id)->get() :  null;

        $authorCatalog = Catalog::where('code', 'author')->get()->first();
        $authors = Catalog_element::where('catalog_id', $authorCatalog->id)->get();

        $periodCatalog = Catalog::where('code', 'period')->get()->first();
        $periods = Catalog_element::where('catalog_id', $periodCatalog->id)->get();

        $placeOfCreationCatalog = Catalog::where('code', 'place_of_creation')->get()->first();
        $placeOfCreations = Catalog_element::where('catalog_id', $placeOfCreationCatalog->id)->get();

        $setCatalog = Catalog::where('code', 'set')->get()->first();
        $sets = Catalog_element::where('catalog_id', $setCatalog->id)->get();

        $refTypeCatalog = Catalog::where('code', 'reference_type')->get()->first();
        $refTypes = Catalog_element::where('catalog_id', $refTypeCatalog->id)->get();

        return view('investigacion.edit', compact('piece', 'genders', 'subgender', 'objectTypes', 'research', 'photographs', 'documents', 'authors', 'periods', 'placeOfCreations', 'bibliographs', 'footnotes', 'refTypes', 'sets'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // update table with DB to avoid ActionBy trait
        DB::table('pieces')
            ->where('id', $id)
            ->update([
                'gender_id' => $request->input('gender_id'),
                'subgender_id' => $request->input('subgender_id'),
                'type_object_id' => $request->input('type_object_id'),
                'description_origin' => $request->input('description_origin'),
                'research_info' => 1,
            ]);

        $research = Research::where('piece_id', $id)->get()->first();
        if(is_null($research)){
            $research = new Research();
        }
        $research->fill($request->only('title', 'set_id', 'technique', 'materials', 'period_id', 'place_of_creation_id', 'acquisition_form', 'acquisition_source', 'acquisition_date', 'firm', 'firm_description', 'short_description', 'formal_description', 'observation', 'publications', 'piece_id', 'creation_date', 'card'));
        $research->firm = (bool)$request->input('firm');
        $research->author_ids = !empty($request->input('author_ids')) ? implode(",", $request->input('author_ids')) : null;
        $research->piece_id = $id;
        $research->save();

        // se verifica si hay que eliminar bibliografias
        if(!is_null($request->input('bibliography_ids_bd_deleted'))){
            // vienen los ids separados por comas
            $idsDeleted = explode(",", $request->input('bibliography_ids_bd_deleted'));
            foreach ($idsDeleted as $idx => $idDeleted) {
                $bibliography = Bibliography::find($idDeleted);
                $bibliography->Delete();
            }
        }
        foreach ($request->bibliography_id_bd as $idx => $bibliography_id_bd) {
            // si no hay titulo se pasa al siguiente
            if(is_null($request->bibliography_title[$idx])) continue;

            if (!is_null($bibliography_id_bd)) {
                // Actualizacion de datos
                $bibliography = Bibliography::find($bibliography_id_bd);
            } else {
                // Nuevo
                $bibliography = new Bibliography();
            }

            $bibliography->research_id = $research->id;
            $bibliography->reference_type_id = $request->bibliography_reference_type_id[$idx];
            $bibliography->title = $request->bibliography_title[$idx];
            $bibliography->author = $request->bibliography_author[$idx];
            $bibliography->city_country = $request->bibliography_city_country[$idx];
            $bibliography->vol_no = $request->bibliography_vol_no[$idx];
            $bibliography->pages = $request->bibliography_pages[$idx];
            $bibliography->article = $request->bibliography_article[$idx];
            $bibliography->chapter = $request->bibliography_chapter[$idx];
            $bibliography->editorial = $request->bibliography_editorial[$idx];
            $bibliography->editor = $request->bibliography_editor[$idx];
            $bibliography->webpage = $request->bibliography_webpage[$idx];
            $bibliography->identifier = $request->bibliography_identifier[$idx];
            $bibliography->publication_date = $request->bibliography_publication_date[$idx];
            $bibliography->save();
        }

        // se verifica si hay que eliminar notas al pie
        if(!is_null($request->input('footnote_ids_bd_deleted'))){
            // vienen los ids separados por comas
            $idsDeleted = explode(",", $request->input('footnote_ids_bd_deleted'));
            foreach ($idsDeleted as $idx => $idDeleted) {
                $footnote = Footnote::find($idDeleted);
                $footnote->Delete();
            }
        }
        foreach ($request->footnote_id_bd as $idx => $footnote_id_bd) {
            if (!is_null($footnote_id_bd)) {
                // Actualizacion de datos
                $footnote = Footnote::find($footnote_id_bd);
            } else {
                // Nuevo
                $footnote = new Footnote();
            }
            if(is_null($request->footnote_title[$idx])) continue;
            $footnote->research_id = $research->id;
            $footnote->title = $request->footnote_title[$idx];
            $footnote->author = $request->footnote_author[$idx];
            $footnote->city_country = $request->footnote_city_country[$idx];
            $footnote->vol_no = $request->footnote_vol_no[$idx];
            $footnote->pages = $request->footnote_pages[$idx];
            $footnote->article = $request->footnote_article[$idx];
            $footnote->chapter = $request->footnote_chapter[$idx];
            $footnote->editorial = $request->footnote_editorial[$idx];
            $footnote->publication_date = $request->footnote_publication_date[$idx];
            $footnote->description = $request->footnote_description[$idx];
            $footnote->save();
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
                $photos->deletePhotoFromDisk($photography->file_name, 'research');

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
                $photos->deletePhotoFromDisk($photography->file_name, 'research');

                flash()->warning('Se ha cambiado la foto: '.$request->photo_description[$idx].' ('.$photography->file_name.')');

                // let's save the photo
                $photography->file_name = $photos->savePhotoToDisk($request->file('photo_file')[$idx], 'research');
            } elseif (!is_null($photo_id_bd) && !isset($request->file('photo_file')[$idx])) {
                // Actualizacion de datos
                $photography = Photography::find($photo_id_bd);
            } elseif (is_null($photo_id_bd) && isset($request->file('photo_file')[$idx]) && $request->hasFile('photo_file')) {
                // Nuevo
                $photography = new Photography();

                // let's save the photo
                $photography->file_name = $photos->savePhotoToDisk($request->file('photo_file')[$idx], 'research');
            } else{
                continue;
            }
            $photography->module_id = $this->getModuleId();
            $photos->setPhotoValues($photography, $request, $idx, $id);
            $photography->save();
        }

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
                $documents->deleteDocumentFromDisk($document->file_name, 'research');

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
                $documents->deleteDocumentFromDisk($document->file_name, 'research');

                flash()->warning('Se ha cambiado el documento: '.$request->document_name[$idx].' ('.$document->file_name.')');

                // let's save the document
                $document->file_name = $documents->saveDocumentToDisk($request->file('document_file')[$idx], 'research');
            } elseif (!is_null($document_id_bd) && !isset($request->file('document_file')[$idx])) {
                // Actualizacion de datos
                $document = Document::find($document_id_bd);
            } elseif (is_null($document_id_bd) && isset($request->file('document_file')[$idx]) && $request->hasFile('document_file')) {
                // Nuevo
                $document = new Document();

                // let's save the document
                $document->file_name = $documents->saveDocumentToDisk($request->file('document_file')[$idx], 'research');
            } else{
                continue;
            }
            $document->module_id = $this->getModuleId();
            $documents->setDocumentValues($document, $request, $idx, $id);
            $document->save();
        }

        flash()->success('Se editaron correctamente los datos de investigación de la pieza.');

        return redirect()->route('investigacion.index');
    }

    public function store(Request $request)
    {
        $id = $request->input('piece_id');
        return $this->update($request, $id);
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        // update table with DB to avoid ActionBy trait
        DB::table('pieces')
            ->where('id', $id)
            ->update([ 'research_info' => 0 ]);

        $photos = new Photos();
        // delete photographs records
        $photographs = Photography::where('piece_id', $id)->where('module_id', $this->getModuleId())->get();
        if(count($photographs) > 0){
            foreach ($photographs as $photography) {
             // delete the file
             $photos->deletePhotoFromDisk($photography->file_name, 'research');

             flash()->warning('Se ha eliminado la foto: '.$photography->description.' ('.$photography->file_name.')');
             // se elimina el registro en la base de datos
             $photography->Delete();
            }
        }

        $documentsController = new Documents();
        // delete photographs records
        $documentsModel = Document::where('piece_id', $id)->where('module_id', $this->getModuleId())->get();
        if(count($documentsModel) > 0){
            foreach ($documentsModel as $document) {
                // delete the file
                $documentsController->deleteDocumentFromDisk($document->file_name, 'research');

                flash()->warning('Se ha eliminado el documento: '.$document->name.' ('.$document->file_name.')');
                // se elimina el registro en la base de datos
                $document->Delete();
            }
        }

        $research = Research::where('piece_id', $id)->get()->first();
        if ($research) {
            $research->Delete();
            flash()->success('Se elimino correctamente');
        } else{
            flash()->warning('Error al eliminar ');
        }

        return redirect()->route('investigacion.index');
    }

     public function getSubgenders(Request $request, $id)
     {
         if ($request->ajax()) {
             $subgenders = Subgender::where('gender_id', $id)->orderBy('title')->get();
             return response()->json($subgenders);
         }
     }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function show($id)
     {
     }
}

<?php

namespace Mnemosine\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Mnemosine\Http\Controllers\Files\Photos;
use Mnemosine\DataTables\PieceDataTable;
use Mnemosine\Gender;
use Mnemosine\Subgender;
use Mnemosine\Catalog;
use Mnemosine\Catalog_element;
use Mnemosine\Piece;
use Mnemosine\Photography;
use Mnemosine\Module;
use Mnemosine\Appraisal;
use Mnemosine\Authorizable;
use Mnemosine\Exhibition;
use Mnemosine\Movement;

class Inventario extends Controller
{
    use Authorizable;

    protected $moduleName = "inventario";

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
        $dataTable->setCalledByModule($this->moduleName, $this->getModuleId(), 'inventory');
        return $dataTable->render('inventario.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $genders = Gender::orderBy('title')->get();
        $objectTypeCatalog = Catalog::where('code', 'object_type')->get()->first();

        $objectTypes = Catalog_element::where('catalog_id', $objectTypeCatalog->id)->get();
        $locations = Exhibition::where('institution_id', 1)->orderBy('name')->get();

        return view('inventario.new', compact('genders', 'locations', 'objectTypes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $genders = Gender::orderBy('title')->get();

        $objectTypeCatalog = Catalog::where('code', 'object_type')->get()->first();
        $objectTypes = Catalog_element::where('catalog_id', $objectTypeCatalog->id)->get();
        $locations = Exhibition::where('institution_id', 1)->orderBy('name')->get();

        $piece = Piece::with('location')->findOrFail($id);
        $photographs = Photography::where('piece_id', $piece->id)
            ->where('module_id', $this->getModuleId())
            ->get();

        ($piece->gender_id) ? $subgender = Subgender::where('gender_id', $piece->gender_id)->get() : $subgender = null;

        return view('inventario.edit', compact('genders', 'locations', 'objectTypes', 'piece', 'subgender', 'photographs'));
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

        $piece = new Piece();
        $piece->fill($request->except('photo_file', 'photo_description', 'photo_date', 'photo_author', 'photo_ids_bd', 'photo_ids_bd_deleted', 'photo_id_bd'));
        $piece->tags = isset($request->tags) ? implode(",", $request->tags) : null;
        $piece->save();

        // se guarda el avaluo en el historial
        $appraisal = new Appraisal();
        $appraisal->appraisal = number_format((float)$request->input('appraisal'), 2, '.', '');
        $appraisal->piece_id = $piece->id;
        $appraisal->save();

        // se guardan las fotos
        if($request->hasfile('photo_file')){
            foreach($request->file('photo_file') as $idx => $photo){
                // verificar que el archivo no sea null
                if(is_null($photo)) continue;

                $photos = new Photos();

                $photography = new Photography();
                $photography->module_id = $this->getModuleId();

                $photos->setPhotoValues($photography, $request, $idx, $piece->id);

                // let's save the photo
                $photography->file_name = $photos->savePhotoToDisk($photo, 'inventory');

                $photography->save();
            }
        }

        // se tiene que crear un movimiento con la ubicacion seleccionada
        $movimientoDatos = [
            'institution_ids' => 1,
            'itinerant' => 0,
            'exhibition_id' => $request->input('location_id'),
            'venues' => '9',
            'pieces_ids' => $piece->id,
            'pieces_ids_arrived' => $piece->id,
            'departure_date' => date('Y-m-d', strtotime($request->input('admitted_at'))),
            'movement_type' => 'internal',
            // el contacto de Ricardo
            'contact_ids' => 255,
            'guard_contact_ids' => 255,
            // se define que es el administrador quien registra y autoriza
            'authorized_by_collections' => 1
        ];
        $movimientoNuevo = new Movement();
        $movimientoNuevo->fill($movimientoDatos);
        $movimientoNuevo->save();

        flash()->success('Se ha creado la pieza con no. de inventario: '. $piece->inventory_number);

        return redirect()->route('inventario.index');
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
        $this->validateRequest($request, $id);

        $piece = Piece::findOrFail($id);
        $piece->fill($request->except('photo_file', 'photo_description', 'photo_date', 'photo_author', 'photo_ids_bd', 'photo_ids_bd_deleted', 'photo_id_bd', 'current_appraisal'));
        $piece->tags = isset($request->tags) ? implode(",", $request->tags) : null;
        $piece->save();

        // se verifica si es necesario guardar el avaluo en el historial
        if($request->input('appraisal') != $request->input('current_appraisal')){
            $appraisal = new Appraisal();
            $appraisal->appraisal = number_format((float)$request->input('appraisal'), 2, '.', '');
            $appraisal->piece_id = $piece->id;
            $appraisal->save();
            flash()->warning('Se ha modificado el avalúo.');
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
                $photos->deletePhotoFromDisk($photography->file_name, 'inventory');

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
                $photos->deletePhotoFromDisk($photography->file_name, 'inventory');

                flash()->warning('Se ha cambiado la foto: '.$request->photo_description[$idx].' ('.$photography->file_name.')');

                // let's save the photo
                $photography->file_name = $photos->savePhotoToDisk($request->file('photo_file')[$idx], 'inventory');
            } elseif (!is_null($photo_id_bd) && !isset($request->file('photo_file')[$idx])) {
                // Actualizacion de datos
                $photography = Photography::find($photo_id_bd);
            } elseif (is_null($photo_id_bd) && isset($request->file('photo_file')[$idx]) && $request->hasFile('photo_file')) {
                // Nuevo
                $photography = new Photography();

                // let's save the photo
                $photography->file_name = $photos->savePhotoToDisk($request->file('photo_file')[$idx], 'inventory');
            } else{
                continue;
            }
            $photography->module_id = $this->getModuleId();
            $photos->setPhotoValues($photography, $request, $idx, $piece->id);
            $photography->save();
        }

        flash()->success('Se ha modificado la pieza con no. de inventario: '. $piece->inventory_number);

        return redirect()->route('inventario.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $photos = new Photos();
        // delete photographs records
        $photographs = Photography::where('piece_id', $id)->where('module_id', $this->getModuleId())->get();
        if(count($photographs) > 0){
            foreach ($photographs as $photography) {
                // delete the file
                $photos->deletePhotoFromDisk($photography->file_name, 'inventory');

                flash()->warning('Se ha eliminado la foto: '.$photography->description.' ('.$photography->file_name.')');
                // se elimina el registro en la base de datos
                $photography->Delete();
            }
        }
        // deletes piece's record
        $piece = Piece::findOrFail($id);
        $piece->Delete();
        flash()->success('Se elimino correctamente');

        return redirect()->route('inventario.index');
    }

    /**
     * Display the specified resource
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function validateRequest(Request $request, $id = null)
    {
        $customMessages = [
            'inventory_number.required' => 'El número de inventario es un campo requerido',
            'origin_number.required' => 'El número de procedencia es un campo requerido',
            'catalog_number.required' => 'El número de catálogo es un campo requerido',
            'description_origin.required' => 'La descripción es un campo requerido',
            'inventory_number.min'  => 'Debe escribir al menos :min caracteres',
            'origin_number.unique'  => 'Ya existe otra pieza con el mismo número de procedencia, escriba otro',
            'appraisal.required' => 'Debe ingresar el avalúo',
            'appraisal.numeric' => 'Debe ingresar un número valido'
        ];
        $requestValidate = [
            'inventory_number' => 'bail|required|min:2|unique:pieces' . (!is_null($id) ? ',inventory_number,' . $id : ''),
            'origin_number' => 'required|min:2',
            'catalog_number' => 'required|min:2',
            'description_origin' => 'required|min:10',
            'alto' => 'integer',
            'photo_file' => 'nullable',
            'photo_file.*' => 'nullable|image|mimes:jpeg,jpg,png,tif,tiff,gif,bmp,svg|max:' . config('fileuploads.inventory.photographs.maximum_size'),
        ];
        if(is_null($id)){
            $customMessages['location_id.required'] = "Debe especificar la ubicación de la pieza";
            $requestValidate['location_id'] = 'required|numeric';
            $requestValidate['appraisal'] = 'required|numeric';
        } else{
            $requestValidate['appraisal'] = 'nullable|numeric';
        }
        $this->validate($request, $requestValidate, $customMessages);
    }

    public function getSubgenders(Request $request, $id)
    {
        if ($request->ajax()) {
            $subgenders = Subgender::where('gender_id', $id)->orderBy('title')->get();
            return response()->json($subgenders);
        }
    }
}

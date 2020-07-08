<?php

namespace Mnemosine\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Mnemosine\DataTables\PieceDataTable;
use Illuminate\Support\Facades\DB;
use Mnemosine\Institution;
use Mnemosine\Exhibition;
use Mnemosine\Contact;
use Mnemosine\Venue;
use Mnemosine\Movement;
use Mnemosine\Catalog;
use Mnemosine\Catalog_element;
use Mnemosine\Piece;
use Mnemosine\Role_has_permission;
use Mnemosine\Role;
use Mnemosine\User;
use Mnemosine\Module;
use Mnemosine\Authorizable;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Movimientos extends Controller
{
    use Authorizable;

    protected $moduleName = "movimientos";

    public function index(){
        $movimientos = Movement::orderBy('departure_date', 'desc')->paginate(10);
        $expos = Exhibition::orderBy('name')->get();
        $instituciones = Institution::all();
        $contactos = Contact::all();

        return view('movimiento/index', compact('movimientos', 'expos', 'contactos', 'instituciones'));
    }

    // PASO 1: crear - Controlar la vista
    public function create(){
        $instituciones = Institution::all();
        $expos = null;
        $contactos = null;
        $sedes = null;
        $var = null;
        return view('movimiento.mov_paso1.new', compact('expos', 'instituciones', 'contactos', 'sedes', 'var'));
    }

    // PASO 1: editar - Controlar la vista
    public function edit($id){
        $movimiento = Movement::findOrFail($id);
        if ($movimiento->movement_type == "internal") {
            $instituciones = Institution::where('id', '=', '1')->get();
        }
        elseif ($movimiento->movement_type == "external") {
            $instituciones = Institution::all();
        }
        $expos = Exhibition::whereIn('institution_id', explode(',', $movimiento->institution_ids))->orderBy('name')->get();
        $contactos = Contact::whereIn('institution_id', explode(',', $movimiento->institution_ids))->get();
        $var = explode(',', $movimiento->venues);

        $sedes = Venue::whereIn('institution_id', explode(',', $movimiento->institution_ids))->get();

        return view('movimiento.mov_paso1.edit', compact('movimiento', 'expos', 'instituciones', 'contactos', 'sedes', 'var'));
    }

    // PASO 2 - Controlar la vista
    public function show($id, PieceDataTable $dataTable){
        $movimiento = Movement::select('id', 'pieces_ids')->where('id', '=', $id)->first();
        $ids = is_null($movimiento->pieces_ids) ? array() : explode(',', $movimiento->pieces_ids);
        $piezas = Piece::all();

        $dataTable->setCalledByModule($this->moduleName, $this->getModuleId(), 'inventory', $ids);

        return $dataTable->render('movimiento.mov_paso2.new', compact('id', 'piezas', 'ids'));
    }

    // PASO 3 - Controlar la vista
    public function resumen_authorizar($id){
        $movimiento = Movement::find($id);
        $instituciones = !is_null($movimiento->institution_ids) ? Institution::whereIn('id', explode(',', $movimiento->institution_ids))->get() : null;
        $contactos = !is_null($movimiento->contact_ids) ? Contact::whereIn('id', explode(',', $movimiento->contact_ids))->get() : null;
        $expo = !is_null($movimiento->exhibition_id) ? Exhibition::where('id', $movimiento->exhibition_id)->first() : null;

        $pieza = !empty($movimiento->pieces_ids) ? explode(',', $movimiento->pieces_ids) : null;
        $pieza_r = !empty($movimiento->pieces_ids_arrived) ? explode(',', $movimiento->pieces_ids_arrived) : null;

        $piezas_r = $piezas = null;
        if(!is_null($pieza)){
            for ($i=0; $i < sizeof($pieza); $i++) {
                $piezas[$i] = Piece::with(['photography', 'location'])->where('id', $pieza[$i])->first();
            }
        }

        if(!is_null($pieza_r)){
            for ($i=0; $i < sizeof($pieza_r); $i++) {
                $piezas_r[$i] = Piece::with(['photography', 'location'])->where('id', $pieza_r[$i])->first();
            }
        }
        if(isset($movimiento->venues)){
            $sede = explode(',', $movimiento->venues);
            if ($sede != null) {
                for ($i=0; $i < sizeof($sede); $i++) {
                    $sedes[$i] = Venue::select('id', 'name')->where('id', '=', $sede[$i])->first();
                }
            }
        } else{
            $sedes[] = '0';
        }

        if ($movimiento->pieces_ids_arrived != null) {
            return view('movimiento.mov_paso4.resumen', compact('movimiento', 'instituciones', 'contactos', 'expo', 'sedes', 'piezas', 'piezas_r'));
        }else{
            return view('movimiento.mov_paso3.index', compact('movimiento', 'instituciones', 'contactos', 'expo', 'sedes', 'piezas'));
        }
    }

    // PASO 1 - Guardar el registro
    public function store(Request $request){
        $now = Carbon::now()->format('Y-m-d'); //TODO: validar fechas ("departure_date", "date_range")

        $movimiento = new Movement();

        if ($request->movement_type == 'external') {
            $this->validateRequest($request);
            if ($request->input('itinerant') == '1') {
                $this->validateItinerant($request);
            }else{
                $this->validateNoItinerant($request);
            }
            $movimiento->fill($request->except('venues', 'institution_ids', 'contact_ids', 'departure_date', 'start_exposure', 'end_exposure', 'paso2', 'p1', 'itinerant'));
            if ($request->input('itinerant') == '1') {
                $movimiento->itinerant = '1';
            }else{
                $movimiento->itinerant = '0';
            }

            $movimiento->institution_ids = implode(",", $request->input('institution_ids'));
        } elseif ($request->movement_type == 'internal') {
            $this->validateRequestInternal($request);
            $this->validateNoItinerant($request);
            $movimiento->fill($request->except('venues', 'institution_ids', 'contact_ids', 'departure_date', 'start_exposure', 'end_exposure', 'paso2', 'p1', 'itinerant'));
            $movimiento->institution_ids = $request->input('internal_institution_id');
            $movimiento->itinerant = '0';
            $movimiento->venues = '9';
        }
        $movimiento->contact_ids = implode(",", $request->input('contact_ids'));
        $movimiento->guard_contact_ids = implode(",", $request->input('guard_contact_ids'));
        // if internal this would be empty
        if($request->input('venues') !== null){
            $movimiento->venues = implode(",", $request->input('venues'));
        }
        $movimiento->departure_date = strtotime($request->input('departure_date'));
        $movimiento->start_exposure = strtotime($request->input('start_exposure'));
        if(!empty($request->input('end_exposure'))){
            $movimiento->end_exposure = strtotime($request->input('end_exposure'));
        } else{
            $movimiento->end_exposure = null;
        }
        $movimiento->save();

        flash()->success('El paso 1 ha sido creado con exito.');
        if ($request->input('paso2') == '1') {
            return redirect()->route('movimientos.show', ['id' => $movimiento->id]);
        } else{
            return redirect()->route('movimientos.index');
        }
    }

    // ACTUALIZAR REGISTROS EN TODOS LOS PASOS
    public function update(Request $request, $id){
        // PASO 1 - Datos generales
        if($request->p1 == 1){
            $movimiento = Movement::findOrFail($id);
            if ($request->movement_type == 'external') {
                $this->validateRequest($request);
                if ($request->input('itinerant') == '1') {
                    $this->validateItinerant($request);
                }else{
                    $this->validateNoItinerant($request);
                }
                $movimiento->fill($request->except('venues', 'institution_ids', 'contact_ids', 'departure_date', 'start_exposure', 'end_exposure', 'paso2', 'p1', 'itinerant'));
                if ($request->input('itinerant') == '1') {
                    $movimiento->itinerant = '1';
                }else{
                    $movimiento->itinerant = '0';
                }
                $movimiento->institution_ids = implode(",", $request->input('institution_ids'));
            }elseif ($request->movement_type == 'internal') {
                $this->validateRequestInternal($request);
                $this->validateNoItinerant($request);
                $movimiento->fill($request->except('venues', 'institution_ids', 'contact_ids', 'departure_date', 'start_exposure', 'end_exposure', 'paso2', 'p1', 'itinerant'));
                $movimiento->itinerant = '0';
                $movimiento->institution_ids = $request->input('internal_institution_id');
            }
            $movimiento->contact_ids = implode(",", $request->input('contact_ids'));
            $movimiento->guard_contact_ids = implode(",", $request->input('guard_contact_ids'));
            // if internal this would be empty
            if($request->input('venues') !== null){
                $movimiento->venues = implode(",", $request->input('venues'));
            }
            $movimiento->departure_date = strtotime($request->input('departure_date'));
            $movimiento->start_exposure = strtotime($request->input('start_exposure'));
            if(!empty($request->input('end_exposure'))){
                $movimiento->end_exposure = strtotime($request->input('end_exposure'));
            } else{
                $movimiento->end_exposure = null;
            }

            $movimiento->save();
            flash()->success('El paso 1 ha sido editado con exito.');
            if ($request->input('paso2') == '1') {
                return redirect()->route('movimientos.show', ['id' => $id]);
            } else {
                return redirect()->route('movimientos.index');
            }
        } // PASO 2 - Seleccion de piezas
        elseif ($request->p2 == 2) {
            $this->validateRequestp2($request);

            $movimiento = Movement::findOrFail($id);
            $movimiento->pieces_ids = $request->pieces_ids;
            $movimiento->save();

            $piezasArr = explode(",", $request->pieces_ids);
            Piece::whereIn('id', $piezasArr)->update(['in_exhibition' => 1]);

            flash()->success('Las piezas se han cargado con exito');
            if ($request->paso3 == '1') {
                return redirect()->route('movimientos.resumen_authorizar', ['id' => $id]);
            }else{
                return redirect()->route('movimientos.index');
            }

        } // PASO 3 - Información y Autorizacion
        elseif ($request->p3 == '3') {
            $movimiento = Movement::findOrFail($id);
            $userId = auth()->user()->id;
            $user = User::find($userId);

            $updatePieceLocations = false;
            if ($user->can('autorizar_colecciones') && $movimiento->authorized_by_collections == null) {
                $movimiento->authorized_by_collections = $userId;
                $movimiento->save();
                $updatePieceLocations = true;
                flash()->success('El departamento de colecciones autorizó el movimiento');
            } elseif ($user->can('autorizar_exposiciones') && $movimiento->authorized_by_exhibitions == null) {
                $movimiento->authorized_by_exhibitions = $userId;
                $movimiento->save();
                $updatePieceLocations = true;
                flash()->success('El departamento de exposiciones autorizó el movimiento');
            } else{
                flash()->info('No tiene permisos para autorizar el movimiento');
            }

            // si es autorizado, se actualiza la ubicacion de las piezas
            if($updatePieceLocations){
                $piecesIds = explode(",", $movimiento->pieces_ids);
                // si el movimiento es interno, se pone el exhibition_id, en caso contrario la ubicacion es 0
                if($movimiento->movement_type == 'internal'){
                    $location = $movimiento->exhibition_id;
                } else{
                    $location = 0;
                }
                // se actualiza la tabla con DB facade para prevenir el trait ActionBy
                DB::table('pieces')
                    ->whereIn('id', $piecesIds)
                    ->update([ 'location_id' => $location ]);
                // se verifica si se debe actualizar el ultimo movimiento de cada pieza
                foreach ($piecesIds as $key => $pieceId) {
                    // se obtiene el ultimo movimiento en que estuvo la pieza y fue autorizado
                    // se omite el movimiento actual
                    $movement = Movement::whereRaw('FIND_IN_SET(?, pieces_ids)', [$pieceId])
                        ->where('authorized_by_collections', '>', 0)
                        ->where('id', '!=' , $movimiento->id)
                        ->orderBy('departure_date', 'desc')->first();

                    $piezaEncontrada = false;
                    $arrivalInformation = array();
                    if(isset($movement->arrival_information) && !empty($movement->arrival_information)){
                        $arrivalInformation = json_decode($movement->arrival_information);
                        // se recorre la informacion de regreso
                        foreach ($arrivalInformation as $datos) {
                            // se verifica si la pieza esta entre los datos actuales
                            if((is_array($datos->pieces) && in_array($pieceId, $datos->pieces)) || ($pieceId == $datos->pieces)){
                                $piezaEncontrada = true;
                                break;
                            }
                        }
                    }

                    if(!$piezaEncontrada){
                        // si no se han registrado datos de regreso para la pieza
                        // se agregan con la variable $location
                        $arrivalInformation[] = array(
                            'pieces' => [$pieceId],
                            'location' => $location,
                            // 'tags' => [],
                            'arrival_date' => $movimiento->departure_date->format('Y-m-d'),
                        );
                        $movement->arrival_information = json_encode($arrivalInformation);

                        $piezasRegresadas = (isset($movimiento->pieces_ids_arrived) && !empty($movimiento->pieces_ids_arrived)) ? explode(",", $movimiento->pieces_ids_arrived) : array();
                        $piezasRegresadas[] = $pieceId;
                        $movement->pieces_ids_arrived = trim(implode(",", $piezasRegresadas), " ,");

                        $movement->save();
                    }
                }
            }
            return redirect()->route('movimientos.index');
        } // PASO 4 - Regresar piezas
        elseif ($request->p4 == '4') {
            $this->validateRequestP4($request);
            $movimiento = Movement::findOrFail($id);

            // se obtienen los numeros de las ubicaciones
            $ubicacionesTodas = explode(",", $request->location_numbers);
            $ubicacionesBorradas = explode(",", $request->location_numbers_deleted);
            $ubicaciones = array_diff($ubicacionesTodas, $ubicacionesBorradas);
            $piezasRegresadas = (isset($movimiento->pieces_ids_arrived) && !empty($movimiento->pieces_ids_arrived)) ? explode(",", $movimiento->pieces_ids_arrived) : array();
            // se debe obtener de la BD
            $datosJson = (isset($movimiento->arrival_information) && !empty($movimiento->arrival_information)) ? json_decode($movimiento->arrival_information) : array();

            $arrival_date = '';
            foreach($ubicaciones as $idx => $ubicacion){
                $piezasArr = $request->input('pieces_ids_' . $ubicacion);
                $location = $request->input('location_id_' . $ubicacion);
                // $tags = !is_null($request->input('tags_' . $ubicacion)) ? $request->input('tags_' . $ubicacion) : array();
                $arrival_date = $request->input('arrival_date_' . $ubicacion);
                // crear un objeto para almacenar como JSON
                $datosJson[] = array(
                    'pieces' => $piezasArr,
                    'location' => $location,
                    // 'tags' => $tags,
                    'arrival_date' => $arrival_date,
                );

                $piezasRegresadas = array_merge($piezasRegresadas, $piezasArr);
                // update table with DB facade to avoid ActionBy trait
                DB::table('pieces')
                    ->whereIn('id', $piezasArr)
                    ->update([
                        'location_id' => $location,
                        // 'tags' => implode(",", $tags)
                     ]);
                // Se crea un movimiento por el cambio  de ubicacion
                $movimientoNuevo = new Movement();
                $movimientoNuevo->fill($movimiento->only('movement_type', 'contact_ids', 'guard_contact_ids', 'authorized_by_collections'));
                $movimientoNuevo->institution_ids = 1;
                $movimientoNuevo->itinerant = 0;
                $movimientoNuevo->venues = '9';
                $movimientoNuevo->exhibition_id = $location;
                $movimientoNuevo->pieces_ids = implode(",", $piezasArr);
                $movimientoNuevo->pieces_ids_arrived = implode(",", $piezasArr);
                $movimientoNuevo->departure_date = date('Y-m-d', strtotime($arrival_date));
                $movimientoNuevo->save();
            }
            $movimiento->arrival_information = json_encode($datosJson);
            $movimiento->pieces_ids_arrived = implode(",", $piezasRegresadas);
            $movimiento->arrival_date = date('Y-m-d', strtotime($arrival_date));
            $movimiento->save();

            flash()->success('Se ha registrado el regreso de las piezas seleccionadas');
            return redirect()->route('movimientos.index');
        }
    }

    /**
    * Retrieves the id of the module this class_ belongs
    *
    * @return integer
    */
    public function getModuleId(){
        return (integer)Module::where('name', $this->moduleName)
        ->where('active', 1)
        ->first()
        ->id;
    }

    public function searchIndex(){
        $institutions = Institution::orderBy('name')->get();
        $exhibitions = Exhibition::orderBy('name')->get();
        $venues = Venue::orderBy('name')->get();
        return view('movimiento.search.index', compact('institutions', 'exhibitions', 'venues'));
    }

    public function searchResults(Request $request){
        $types = array('institution' => 'institución', 'exhibition' => 'exposición/ubicación', 'venue' => 'sede');
        switch ($request->type) {
            case 'institution':
                $type = Institution::findOrFail($request->institution);
                $movements = Movement::whereRaw('FIND_IN_SET(?, institution_ids)', [$type->id])
                    ->orderBy('departure_date', 'desc')->paginate(10);
                break;
            case 'exhibition':
                $type = Exhibition::findOrFail($request->exhibition);
                $movements = Movement::where('exhibition_id', $type->id)
                    ->orderBy('departure_date', 'desc')->paginate(10);
                break;
            case 'venue':
                $type = Venue::findOrFail($request->venue);
                $movements = Movement::whereRaw('FIND_IN_SET(?, venues)', [$type->id])
                    ->orderBy('departure_date', 'desc')->paginate(10);
                break;
            default:
                flash()->warning('No se realizo correctamente la búsqueda, vuelve a intentarlo.');
                return redirect()->route('movimientos.search.index');
                break;
        }
        return view('movimiento.search.results', compact('type', 'movements', 'request', 'types'));
    }

    public function searchResultsExcel(Request $request){
        $types = array('institution' => 'institución', 'exhibition' => 'exposición/ubicación', 'venue' => 'sede');
        switch ($request->type) {
            case 'institution':
                $type = Institution::findOrFail($request->institution);
                $movements = Movement::whereRaw('FIND_IN_SET(?, institution_ids)', [$type->id])
                    ->orderBy('departure_date', 'desc')->get();
                break;
            case 'exhibition':
                $type = Exhibition::findOrFail($request->exhibition);
                $movements = Movement::where('exhibition_id', $type->id)
                    ->orderBy('departure_date', 'desc')->get();
                break;
            case 'venue':
                $type = Venue::findOrFail($request->venue);
                $movements = Movement::whereRaw('FIND_IN_SET(?, venues)', [$type->id])
                    ->orderBy('departure_date', 'desc')->get();
                break;
            default:
                flash()->warning('No se realizo correctamente la búsqueda, vuelve a intentarlo.');
                return redirect()->route('movimientos.search.index');
                break;
        }

        if(isset($movements)){
            $columnIndex = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ');

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle("Movimientos");

            // columns width
            for ($i=0;$i<6;$i++) {
                $sheet->getColumnDimension($columnIndex[$i])->setWidth(29);
            }

            $actualRow = $firstRow = 4;
            $freezeRow = 'A' . ($firstRow+1);
            $sheet->freezePane($freezeRow, $freezeRow);

            $sheet->setCellValue('A1', "Búsqueda de movimientos por " . $types[$request->type]);
            $sheet->setCellValue('A2', ucfirst($types[$request->type]) . ": " . $type->name);
            $sheet->getStyle('A1:A2')->getFont()->setSize(15);
            $sheet->getStyle('A1:A2')->getFont()->setName('Arial');

            // Encabezados
            $sheet->setCellValue('A' . $firstRow, "Núms. de inventario");
            $sheet->setCellValue('B' . $firstRow, "Fecha salida");
            $sheet->setCellValue('C' . $firstRow, "Fecha entrada");
            $sheet->setCellValue('D' . $firstRow, "Institución");
            $sheet->setCellValue('E' . $firstRow, "Ubicación / Exposición");
            $sheet->setCellValue('F' . $firstRow, "Sede");

            foreach ($movements as $key => $movement) {
                $actualRow++;
                $sheet->setCellValue('A' . $actualRow, isset($movement->pieces) ? implode(", ", $movement->pieces->pluck('inventory_number')->toArray()) : "");
                $sheet->setCellValue('B' . $actualRow, isset($movement->departure_date) ? $movement->departure_date->locale('es_MX')->isoFormat('LL') : '');
                $sheet->setCellValue('C' . $actualRow, isset($movement->arrival_date) ? $movement->arrival_date->locale('es_MX')->isoFormat('LL') : '');
                $sheet->setCellValue('D' . $actualRow, !is_null($movement->institutions) ? implode(", ", $movement->institutions->pluck('name')->toArray()) : '-');
                $sheet->setCellValue('E' . $actualRow, isset($movement->exhibition['name']) ? $movement->exhibition['name'] : "");
                $sheet->setCellValue('F' . $actualRow, !is_null($movement->venue) ? implode(", ", $movement->venue->pluck('name')->toArray()) : '-');
            }
            $rangeAll = 'A'.$firstRow.':'.$columnIndex[5] . $actualRow;
            $rangeTitle = 'A'.$firstRow.':'.$columnIndex[5] . $firstRow;

            $sheet->getStyle($rangeAll)->getAlignment()->setWrapText(true);
            $sheet->getStyle($rangeAll)->getFont()->setSize(12);
            $sheet->getStyle($rangeAll)->getFont()->setName('Arial');
            // vertical align top
            $sheet->getStyle($rangeAll)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
            // borders
            $styleArray = [
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ];
            $sheet->getStyle($rangeAll)->applyFromArray($styleArray);

            // Title styles
            // cell background
            $sheet->getStyle($rangeTitle)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFC10007');
            // text color
            $sheet->getStyle($rangeTitle)->getFont()->getColor()->setARGB('FFFFFFFF');
            // font size
            $sheet->getStyle($rangeTitle)->getFont()->setSize(13);
            $sheet->getStyle($rangeTitle)->applyFromArray($styleArray);
            $sheet->getStyle($rangeTitle)->getFont()->setBold(true);

            header('Content-Type: application/octet-stream');
            header("Content-Disposition: attachment; filename=Movimientos-" . date('Ymd-His') . ".xlsx");
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        } else{
            flash()->warning('No se pudo generar el archivo para descargar.');
            return redirect()->route('movimientos.search.index');
        }
    }

    public function return_pieces($id, PieceDataTable $dataTable){
        $movimiento = Movement::findOrFail($id);
        $piezas = Piece::findOrFail(explode(",", $movimiento->pieces_ids));
        // se obtienen las ubicaciones del museo
        $ubicaciones = Exhibition::where('institution_id', 1)->orderBy('name')->get();
        // se obtiene la informacion codificada con json
        $arrivalInformation = json_decode($movimiento->arrival_information);
        $piezasDiff = array();

        if(is_array($arrivalInformation)){
            foreach($arrivalInformation as $info){
                $piezasDiff = array_merge($piezasDiff, $info->pieces);
            }
        }

        $piezasByKey = $piezas->keyBy('id');
        $ubicacionesByKey = $ubicaciones->keyBy('id');

        return view('movimiento.mov_paso4.return_pieces', compact('id', 'movimiento', 'piezas', 'piezasDiff', 'piezasByKey', 'ubicaciones', 'ubicacionesByKey', 'arrivalInformation'));
    }

    public function validateRequest(Request $request) {
        $customMessages = [
            'institution_ids.required' => 'La institución es un campo requerido',
            'contact_ids.required' => 'El contacto es un campo requerido',
            'exhibition_id.required' => 'La exposición es un campo requerido',
            'departure_date.required'  => 'La fecha de salida es un campo requerido',
        ];
        $this->validate($request, [
            'institution_ids' => 'required',
            'contact_ids' => 'required',
            'exhibition_id' => 'required',
            'departure_date' => 'required',
        ], $customMessages);
    }

    public function validateRequestInternal(Request $request) {
        $customMessages = [
            'exhibition_id.required' => 'La exposición es un campo requerido',
            'departure_date.required'  => 'La fecha de salida es un campo requerido',
        ];
        $this->validate($request, [
            'exhibition_id' => 'required',
            'departure_date' => 'required',
        ], $customMessages);
    }

    public function validateItinerant(Request $request) {
        $customMessages = [
            'venues.required' => 'La sede es un campo requerido',
        ];
        $this->validate($request, [
            'venues' => 'required',
        ], $customMessages);
    }

    public function validateNoItinerant(Request $request) {
        $customMessages = [
            'venues' => 'Debe seleccionar máximo una sede'
        ];
        $this->validate($request, [
            'venues' => 'max:1',
        ], $customMessages);
    }

    public function validateRequestp2(Request $request) {
        $customMessages = [
            'pieces_ids.required' => 'No ha seleccionado alguna pieza',
        ];
        $this->validate($request, [
            'pieces_ids' => 'required',
        ], $customMessages);
    }

    public function validateRequestP4(Request $request) {
        $customMessages = [
            'location_numbers.required' => 'No ha indicado ninguna ubicación.',
        ];
        $this->validate($request, [
            'location_numbers' => 'required',
        ], $customMessages);
    }

    public function getVenues(Request $request, $ids)
    {
        if ($request->ajax()) {
            $data = Venue::whereIn('institution_id', explode(',', $ids))->get();
        }

        return response()->json($data);
    }

    public function getInstitutions(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = Institution::all();
        }

        return response()->json($data);
    }

    public function getcodeInventory(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = Piece::select('id', 'inventory_number')->where('id', '=', $id)->get();
        }
        return response()->json($data);
    }

    public function getVenuesMov1(Request $request, $ids)
    {
        if ($request->ajax()) {
            $data = Venue::whereIn('institution_id', explode(',', $ids))->get();
        }

        return response()->json($data);
    }

    public function getExhibitionMov1(Request $request, $ids){
        if($request->ajax())
        {
            $data = Exhibition::whereIn('institution_id', explode(',', $ids))->orderBy('name')->get();
        }
        return response()->json($data);
    }

    public function getContactMov1(Request $request, $ids){
        if($request->ajax()){
            $data = Contact::whereIn('institution_id', explode(',', $ids))->get();
        }
        return response()->json($data);
    }
}

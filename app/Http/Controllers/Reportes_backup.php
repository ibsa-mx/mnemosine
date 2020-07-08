<?php

namespace Mnemosine\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mnemosine\Authorizable;
use Mnemosine\Piece;
use Mnemosine\Gender;
use Mnemosine\Subgender;
use Mnemosine\Catalog;
use Mnemosine\Catalog_element;
use Mnemosine\Origin;
use Mnemosine\Report;
use Mnemosine\Research;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;
use Exception;

//
class ReportesBak extends Controller
{
    use Authorizable;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = Report::all();
        return view('reportes.index', compact('reports'));
    }

    //función para filtrar los campos para las consultas
    public function filtrar_campos(Request $request, $id){
        if ($request->ajax()) {
            switch ($id) {
                case 'procedencia':
                    $data['type'] = 'procedencia';
                    $data['result'] =  Origin::select('id', 'code')->get();
                    break;
                case 'inventario':
                    $data['type'] = 'inventario';
                    $data['result'] = Piece::select('id', 'inventory_number')->get();
                    break;
                case 'catalogo':
                    $data['type'] = 'catalogo';
                    $data['result'] = Piece::select('id', 'catalog_number')->get();
                    break;
                case 'genero':
                    $data['type'] = 'genero';
                    $data['result'] = Gender::select('id', 'title')->get();
                    break;
                case 'subgender':
                    $data['type'] = 'subgender';
                    $data['result'] = Subgender::select('id', 'title')->get();
                    break;
                case 'ubicacion':
                    $data['type'] = 'ubicacion';
                    $id_location = Catalog::select('id')->where('title', '=', 'Ubicación')->first();
                    $data['result'] = Catalog_element::select('id', 'title')->where('catalog_id', '=', $id_location->id)->get();
                    break;
                case 'tipo':
                    $data['type'] = 'tipo';
                    $id_tipo = Catalog::select('id')->where('title', '=', 'Tipo de objeto')->first();
                    $data['result'] = Catalog_element::select('id', 'title')->where('catalog_id', '=', $id_tipo->id)->get();
                    break;
                case 'h':
                    $data['type'] = 'h';
                    $data['result'] = Piece::select('id', 'height')->get()->groupBy('height');
                    break;
                case 'w':
                    $data['type'] = 'w';
                    $data['result'] = Piece::select('id',  'width')->get()->groupBy('width');
                    break;
                case 'd':
                    $data['type'] = 'd';
                    $data['result'] = Piece::select('id', 'depth')->get()->groupBy('depth');
                    break;
                case 'c':
                    $data['type'] = 'c';
                    $data['result'] = Piece::select('id', 'diameter')->get()->groupBy('diameter');
                    break;
                case 'hb':
                    $data['type'] = 'hb';
                    $data['result'] = Piece::select('id', 'height_with_base')->get()->groupBy('height_with_base');
                    break;
                case 'wb':
                    $data['type'] = 'wb';
                    $data['result'] = Piece::select('id', 'width_with_base')->get()->groupBy('width_with_base');
                    break;
                case 'db':
                    $data['type'] = 'db';
                    $data['result'] = Piece::select('id', 'depth_with_base')->get()->groupBy('depth_with_base');
                    break;
                case 'cb':
                    $data['type'] = 'cb';
                    $data['result'] = Piece::select('id', 'diameter_with_base')->get()->groupBy('diameter_with_base');
                    break;
                case 'descripcion':
                    $data['type'] = 'descripcion';
                    $data['result'] = Piece::select('id', 'description_origin')->get();
                    break;
                case 'avaluo':
                    $data['type'] = "avaluo";
                    //$data['result'] = Piece::select('id', 'appraisal')->get();

                    $data['result'] = Piece::select('id', 'appraisal')->get()->groupBy('appraisal');
                    break;
                default:
                    $data['result'] = "n/a";
                    break;
            }
            return response()->json($data);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('reportes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       $validate = $request->validate([
            'Nombre', 'name'=>'required',
            'Módulo', 'modulo'=>'required',
            'Columnas', 'columnas'=>'required']);

       $report = new Report();
       $report->name = $request->input('name');
       $report->description = $request->input('description');
       $report->module = $request->input('modulo');
       $report->columns = implode(",", $request->input('columnas'));


       ($request->input('campo-todas')) ?
           $report->all_fields = implode(',', $request->input('campo-todas')) :
           $report->all_fields = null;

       ($request->input('condicion-todas')) ?
           $report->all_conditions =implode(',', $request->input('condicion-todas')) :
           $report->all_conditions = null;

       ($request->input('campo-conuna')) ?
           $report->fields = implode(',', $request->input('campo-conuna')) :
           $report->fields = null;

       ($request->input('condicion-conuna')) ?
           $report->conditions = implode(',', $request->input('condicion-conuna')) :
           $report->conditions = null;

       ($request->input('filtros-todas')) ?
           $report->all_filters = implode(',', $request->input('filtros-todas')) :
           $report->all_filters = null;

       ($request->input('filtros-conuna')) ?
           $report->filters = implode(',', $request->input('filtros-conuna')) :
           $report->filters = null;

       ($request->input('select_filtro')) ?
           $report->all_selected_filters = implode(',', $request->input('select_filtro')) :
           $report->all_selected_filters = null;

       ($request->input('select_filtro_una'))?
           $report->selected_filter = implode(',', $request->input('select_filtro_una')) :
           $report->selected_filter = null;

       $report->save();

       $lates_id = $report->id;


       flash()->success('El reporte se creo con exito');

        return redirect()->route('reportes.show', ['id' => $lates_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $unacol = $todascol = $query = $uquery = $condicion = $ucondicion = array();

        $sql = $columna  = $filtro = "";

        $detalle = Report::find($id);

        $module = $detalle->module;

        $columnas = explode(",", $detalle->columns);

        for ($i=0; $i < count($columnas) ; $i++) {

            $columnas[$i] = $this->nombrar_columnas($columnas[$i]);
        }

        if($detalle->all_fields == null && $detalle->fields == null)
        {
            $consultas = Piece::all();
            return view('reportes.show', compact('detalle', 'columnas', 'consultas'));
        }else
        {
            $todasco = explode(",", $detalle->all_fields);
            $todasc = explode(",", $detalle->all_conditions);
            $todasfil = explode(",", $detalle->all_filters);
            $todasel = explode(",", $detalle->all_selected_filters);

            $unaco = explode(",", $detalle->fields);
            $unac = explode(",", $detalle->conditions);
            $unafil = explode(",", $detalle->filters);
            $unasel = explode(",", $detalle->selected_filter);

            if ($todasco['0'] != null) {
                foreach ($todasco as $tco) {
                    switch ($tco) {
                        case 'procedencia':
                            $todascol[] = 'origin_number';
                            break;
                        case 'inventario':
                            $todascol[] = 'inventory_number';
                            break;
                        case 'catalogo':
                            $todascol[] = 'catalog_number';
                            break;
                        case 'genero':
                            $todascol[] = 'gender_id';
                            break;
                        case 'subgender':
                            $todascol[] = 'subgender_id';
                            break;
                        case 'ubicacion':
                            $todascol[] = 'location_id';
                            break;
                        case 'tipo':
                            $todascol[] = 'type_object_id';
                            break;
                        case 'descripcion':
                            $todascol[] = 'description_origin';
                            break;
                        case 'avaluo':
                            $todascol[] = 'appraisal';
                            break;
                        case 'h':
                            $todascol[] = 'height';
                            break;
                        case 'w':
                            $todascol[] = 'width';
                            break;
                        case 'd':
                            $todascol[] = 'depth';
                            break;
                        case 'c':
                            $todascol[] = 'diameter';
                            break;
                        case 'hb':
                            $todascol[] = 'height_with_base';
                            break;
                        case 'wb':
                            $todascol[] = 'width_with_base';
                            break;
                        case 'db':
                            $todascol[] = 'depth_with_base';
                            break;
                        case 'cb':
                            $todascol[] = 'diameter_with_base';
                            break;
                        default:
                            $todascol[] = '';
                            break;
                    }
                }


                foreach ($todasc as $tc) {
                    switch ($tc) {
                        case 'igual':
                            $condicion[] = '=';
                            break;
                        case 'diferente':
                            $condicion[] = '<>';
                            break;
                        case 'inicia':
                            $condicion[] = "like 'var%'";
                            break;
                        case 'termina':
                            $condicion[] = "like '%var'";
                            break;
                        case 'contiene':
                            $condicion[] = "like '%var%'";
                            break;
                        case 'nocontiene':
                            $condicion[] = "not like '%var%'";
                            break;
                        case 'vacio':
                            $condicion[] = 'IS NULL';
                            break;
                        case 'novacio':
                            $condicion[] = 'IS NOT NULL';
                            break;
                        default:
                            echo'sin informacíon';
                            break;
                    }
                }
                    $j = 0;
                    for ($i=0; $i < count($todascol); $i++) {

                        $query[$i] = $todascol[$i].','. $condicion[$i];

                        if (($condicion[$i] == '=') || ($condicion[$i] == '<>')) {
                            //var_dump($condicion);exit();

                                $query[$i].= ','.$todasel[$j + 1];
                                $j++;

                        }elseif (($condicion[$i] == "like '%var'") || ($condicion[$i] == "like 'var%'") || ($condicion[$i] == "like '%var%'") || ($condicion[$i] == "not like '%var%'")) {

                            $query[$i] = str_replace('var', $todasfil[$i + 1], $query[$i]);
                        }
                    }

            }


            if ($unaco['0'] != '') {
                foreach ($unaco as $uco) {
                    switch ($uco) {
                        case 'procedencia':
                            $unacol[] = 'origin_number';
                            break;
                        case 'inventario':
                            $unacol[] = 'inventory_number';
                            break;
                        case 'catalogo':
                            $unacol[] = 'catalog_number';
                            break;
                        case 'genero':
                            $unacol[] = 'gender_id';
                            break;
                        case 'subgender':
                            $unacol[] = 'subgender_id';
                            break;
                        case 'ubicacion':
                            $unacol[] = 'location_id';
                            break;
                        case 'tipo':
                            $unacol[] = 'type_object_id';
                            break;
                        case 'descripcion':
                            $unacol[] = 'description_origin';
                            break;
                        case 'avaluo':
                            $unacol[] = 'appraisal';
                            break;
                        case 'h':
                            $unacol[] = 'height';
                            break;
                        case 'w':
                            $unacol[] = 'width';
                            break;
                        case 'd':
                            $unacol[] = 'depth';
                            break;
                        case 'c':
                            $unacol[] = 'diameter';
                            break;
                        case 'hb':
                            $unacol[] = 'height_with_base';
                            break;
                        case 'wb':
                            $unacol[] = 'width_with_base';
                            break;
                        case 'db':
                            $unacol[] = 'depth_with_base';
                            break;
                        case 'cb':
                            $unacol[] = 'diameter_with_base';
                            break;
                        default:
                            echo "sin información";
                            break;
                    }
                }


                foreach ($unac as $uc) {
                    switch ($uc) {
                        case 'igual':
                            $ucondicion[] = '=';
                            break;
                        case 'diferente':
                            $ucondicion[] = '<>';
                            break;
                        case 'inicia':
                            $ucondicion[] = "like 'var%'";
                            break;
                        case 'termina':
                            $ucondicion[] = "like '%var'";
                            break;
                        case 'contiene':
                            $ucondicion[] = "like '%var%'";
                            break;
                        case 'nocontiene':
                            $ucondicion[] = "NOT like '%var%'";
                            break;
                        case 'vacio':
                            $ucondicion[] = "IS NULL";
                            break;
                        case 'novacio':
                            $ucondicion[] = "IS NOT NULL";
                            break;
                        default:
                            echo "sin información";
                            break;
                    }
                }

                    $k = 0;
                for ($i=0; $i < count($unacol); $i++) {

                    $uquery[$i] = $unacol[$i].','. $ucondicion[$i];

                    if (($ucondicion[$i] == '=') || ($ucondicion[$i] == '<>')) {

                        $uquery[$i].= ','.$unasel[$k + 1];

                    }elseif (($ucondicion[$i] == "like '%var'") || ($ucondicion[$i] == "like 'var%'") || ($ucondicion[$i] == "like '%var%'") || ($ucondicion[$i] == "NOT like '%var%'")) {

                        $uquery[$i] = str_replace('var', $unafil[$i + '1'], $uquery[$i]);
                    }
                }
            }


            if (count($query) == '1') {
                $temp = explode(',', $query['0']);

                $r = str_replace(',', ' ', $query[0]);

                $consultas = Piece::whereRaw($r)->get();


               if (!empty($consultas[0])) {
                    return view('reportes.show', compact('consultas', 'detalle', 'columnas'));

                }else{
                    return view('reportes.show', compact('detalle', 'columnas'));
                }

            }elseif (count($query) > '1') {
                //CUANDO SON MAYORES A 1

                for ($i=0; $i < count($query); $i++) {
                    $temp3 = explode(',', $query[$i]);
                }

                $str = str_replace(',', ' ', $query); //reemplaza comas por espacios

                $sql = ' ';

                for ($i=0; $i < count($query) ; $i++) {  //forma la query
                    $sql .= $str[$i]. ' '. 'AND'. ' ';
                }

                $sql = substr($sql, 0, -4); //quita al final el AND sobrante

                $consultas = DB::table('pieces')
                    ->whereRaw($sql)
                    ->get();

                if (!empty($consultas[0])) {
                     return view('reportes.show', compact('consultas', 'detalle', 'columnas'));

                }else{
                     return view('reportes.show', compact('detalle', 'columnas'));

                }
            }

            if (count($uquery) == '1') {
                $utemp = explode(',', $uquery['0']);

                $ur = str_replace(',', ' ', $uquery[0]);

                $consultas = Piece::whereRaw($ur)->get();


               if (!empty($consultas[0])) {
                    return view('reportes.show', compact('consultas', 'detalle', 'columnas'));

                }else{
                    return view('reportes.show', compact('detalle', 'columnas'));
                }

            }elseif (count($uquery) > '1') {
                //SI SON MAYORES A 1

                for ($i=0; $i < count($uquery); $i++) {
                    $utemp3 = explode(',', $uquery[$i]);
                }

                $str = str_replace(',', ' ', $uquery); //reemplaza comas por espacios

                $sql = ' ';

                for ($i=0; $i < count($uquery) ; $i++) {  //forma la query
                    $sql .= $str[$i]. ' '. 'OR'. ' ';
                }

                $sql = substr($sql, 0, -4); //quita al final el AND sobrante

                $consultas = DB::table('pieces')
                    ->whereRaw($sql)
                    ->get();

                if (!empty($consultas[0])) {
                     return view('reportes.show', compact('consultas', 'detalle', 'columnas'));

                }else{
                     return view('reportes.show', compact('detalle', 'columnas'));

                }
            }
        }

    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $reporte = Report::find($id);
       $columnas = explode(',', $reporte->columns);
       $todas_filas = explode(',', $reporte->all_fields);
       //echo "<pre>";
       //var_dump($todas_filas); exit();
       $filas = explode(',', $reporte->fields);
       $todas_condiciones = explode(',', $reporte->all_conditions);
       $condiciones = explode(',', $reporte->conditions);
       $todas_filtros = explode(',', $reporte->all_filters);
       $todas_select_filtros = explode(',', $reporte->all_selected_filters);
       $filtros = explode(',', $reporte->selected_filter);
       $select_filtros = explode(',', $reporte->filters);

       $num = count($todas_filas);
       //var_dump($columnas); exit();

       return view('reportes.edit', compact('reporte', 'columnas', 'todas_filas', 'filas', 'todas_condiciones', 'condiciones', 'num', 'todas_filtros', 'todas_select_filtros', 'filtros', 'select_filtros'));
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
        return 'hola editar';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reporte = Report::find($id);
        $reporte->Delete();
        flash()->success('Se elimino correctamente');
        return redirect()->route('reportes.index');
    }

    public function nombrar_columnas($var){
        switch ($var) {
            case 'procedencia':
                $data['type'] = 'Procedencia';
                break;
            case 'inventario':
                $data['type'] = 'Inventario';
                break;
            case 'catalogo':
                $data['type'] = 'Catálogo';
                break;
            case 'genero':
                $data['type'] = 'Género';
                break;
            case 'subgenero':
                $data['type'] = 'Subgénero';
                break;
            case 'ubicacion':
                $data['type'] = 'Ubicación';
                break;
            case 'tipo':
                $data['type'] = 'Tipo';
                break;
            case 'h':
                $data['type'] = 'Alto';
                break;
            case 'w':
                $data['type'] = 'Ancho';
                break;
            case 'd':
                $data['type'] = 'Profundo';
                break;
            case 'c':
                $data['type'] = 'Diametro';
                break;
            case 'descripcion':
                $data['type'] = 'Descripción';
                break;
            case 'avaluo':
                $data['type'] = "Avalúo";
                break;
            default:
                $data['type'] = "n/a";
                break;
        }

        return $data;
    }

    public function cedula(Request $request, $id){
        //guarda los ids de todas las piezas seleccionadas para realizar la cédula
        $p = $request->input('check_p');
        $ids_piezas = (object)$p;


        foreach ($ids_piezas as $piez) {
            $piezas[] = Piece::find($piez);
        }

        foreach ($piezas as $item) {
            ($item->location_id != null) ? $ubicacion[]
                                 = Catalog_element::find($item->location_id): '';
        }

        foreach ($piezas as $item) {
            if ($item->research_info == 1) {
                $piezas_invest[] = $item;
            }
        }

        foreach ($piezas_invest as $item) {
            $investigacion[] = Research::where('piece_id', '=', $item->id)->get();
        }

        $codigo_autor = Catalog::where('code', '=', 'author')->first();
        $autores = Catalog_element::where('catalog_id', '=', $codigo_autor->id)->get();

        $codigo_lugar_c = Catalog::where('code', '=', 'place_of_creation')->first();
        $lugares_creacion = Catalog_element::where('catalog_id', '=', $codigo_lugar_c->id)->get();

        $codigo_periodo = Catalog::where('code', '=', 'period')->first();
        $periodos = Catalog_element::where('catalog_id', '=', $codigo_periodo->id)->get();

        if ($request->input('btn1') == '1') {

        $doc =  $this->generateDocx($piezas, $investigacion, $autores, $lugares_creacion);

            return view('reportes.cedula.cedula1', compact('id', 'piezas', 'ubicacion', 'investigacion', 'autores', 'lugares_creacion', 'periodos'));
        }

        elseif ($request->input('btn2') == '2') {
            $doc2 = $this->generateDocxc2($piezas, $investigacion, $autores, $lugares_creacion);

            return view('reportes.cedula.cedula2', compact('id', 'piezas', 'ubicacion', 'investigacion', 'autores', 'lugares_creacion', 'periodos'));
        }

        elseif ($request->input('btn3') == '3') {
            $doc2 = $this->generateDocxc3a($piezas, $investigacion, $autores, $lugares_creacion);

            return view('reportes.cedula.cedula3a', compact('id', 'piezas', 'investigacion', 'autores', 'lugares_creacion', 'periodos'));
        }

        elseif ($request->input('btn4') == '4') {
            $doc2 = $this->generateDocxc3b($piezas, $investigacion, $autores, $lugares_creacion);
            return view('reportes.cedula.cedula3b', compact('id', 'piezas', 'investigacion', 'autores', 'lugares_creacion', 'periodos'));
        }

        else{

            return redirect()->route('reportes.show', $id);
        }

    }


    public function generateDocx($piezas, $investigacion, $autores, $lugares_creacion){

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/public/reportes/template_cedula1.docx'));

        $templateProcessor->cloneBlock('cedula', count($investigacion), true);

        $index = 1;
        $t_autor = '';
        $m = '';
        //dependiendo si las piezas tienen investigaciones, se gnera su cédula si no no se genera, pues no tiene caso crear fichas sin información.
        foreach($investigacion as $inv){

            $comas = strpos($inv[0]->author_ids, ',');

            foreach ($piezas as $item) {
                if ($inv[0]->piece_id == $item->id) {
                    $templateProcessor->setValue('numero_proc', $item->origin_number, $index);
                    $templateProcessor->setValue('numero_inv', $item->inventory_number, $index);
                    $templateProcessor->setValue('numero_cat', $item->catalog_number, $index);
                    $ubicacion = Catalog_element::find($item->location_id);
                    $templateProcessor->setValue('ubicacion', $ubicacion->title, $index);
                    ($item->height) ? $m .= $item->height: '';
                    ($item->width) ? $m .= ' x '. $item->width : '';
                    ($item->depth) ? $m .=' x '. $item->depth: '';
                    ($item->diameter) ? $m .= ' x '. $item->diameter: '';
                    $templateProcessor->setValue('medidas', $m, $index);

                    if (empty($comas)) {
                        foreach ($autores as $a) {
                             if($a->id == $inv[0]->author_ids){
                                $t_autor = $a->title;
                             }
                        }
                    }
                    else{
                        $authores = explode(',', $inv[0]->author_ids);
                        foreach ($authores as $aut) {
                           foreach($autores as $a){
                                if($a->id == $aut){
                                    $t_autor .= $a->title.', ';
                                }
                            }
                        }
                    }
                }
            }

            $lugar = Catalog_element::find($inv[0]->place_of_creation_id);
            $templateProcessor->setValue('autor', $t_autor, $index);
            $templateProcessor->setValue('fecha_creacion', $inv[0]->creation_date, $index);
            $templateProcessor->setValue('lugar_creacion', $lugar->title, $index);
            $templateProcessor->setValue('titulo', $inv[0]->title, $index);
            $templateProcessor->setValue('tecnica', $item->technique, $index);
            $templateProcessor->setValue('procedencia', $item->adquisition_source, $index);
            $templateProcessor->setValue('espacio', '', $index);

            $t_autor = '';
            $m = '';
        }


        $templateProcessor->saveAs('cedula1.docx');

        return response()->download('cedula1.docx');
    }


    public function generateDocxc2($piezas, $investigacion, $autores, $lugares_creacion){

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/public/reportes/template_cedula2.docx'));

        $templateProcessor->cloneBlock('cedula', count($investigacion));

        $index = 1;
        $m = '';
        //dependiendo si las piezas tienen investigaciones, se gnera su cédula si no no se genera, pues no tiene caso crear fichas sin información.
        foreach($investigacion as $inv){

            foreach ($piezas as $item) {
                if ($inv[0]->piece_id == $item->id) {
                    $templateProcessor->setValue('numero_proc', $item->origin_number, $index);
                    $templateProcessor->setValue('numero_inv', $item->inventory_number, $index);
                    $templateProcessor->setValue('numero_cat', $item->catalog_number, $index);
                    $ubicacion = Catalog_element::find($item->location_id);
                    $templateProcessor->setValue('ubicacion', $ubicacion->title, $index);
                    ($item->height) ? $m .= $item->height: '';
                    ($item->width) ? $m .= ' x '. $item->width : '';
                    ($item->depth) ? $m .=' x '. $item->depth: '';
                    ($item->diameter) ? $m .= ' x '. $item->diameter: '';
                    $templateProcessor->setValue('medidas', $m, $index);
                }
            }

            $lugar = Catalog_element::find($inv[0]->place_of_creation_id);
            $templateProcessor->setValue('fecha_creacion', $inv[0]->creation_date, $index);
            $templateProcessor->setValue('lugar_creacion', $lugar->title, $index);
            $templateProcessor->setValue('titulo', $inv[0]->title, $index);
            $templateProcessor->setValue('tecnica', $item->technique, $index);
            $templateProcessor->setValue('procedencia', $item->adquisition_source, $index);
            $templateProcessor->setValue('espacio', '', $index);

            $m = '';
        }


        $templateProcessor->saveAs('cedula2.docx');

        return response()->download('cedula2.docx');
    }

    public function generateDocxc3a($piezas, $investigacion, $autores, $lugares_creacion){

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/public/reportes/template_cedula3a.docx'));

        $templateProcessor->cloneBlock('cedula', count($investigacion));

        $index = 1;
        $t_autor = '';
        $m = '';
        //dependiendo si las piezas tienen investigaciones, se gnera su cédula si no no se genera, pues no tiene caso crear fichas sin información.
        foreach($investigacion as $inv){

            $comas = strpos($inv[0]->author_ids, ',');

            if (empty($comas)) {
                foreach ($autores as $a) {
                     if($a->id == $inv[0]->author_ids){
                        $t_autor = $a->title;
                     }
                }
            }
            else{
                $authores = explode(',', $inv[0]->author_ids);
                foreach ($authores as $aut) {
                   foreach($autores as $a){
                        if($a->id == $aut){
                            $t_autor .= $a->title.', ';
                        }
                    }
                }
            }

            $templateProcessor->setValue('autor', $t_autor, $index);
            $templateProcessor->setValue('fecha_creacion', $inv[0]->creation_date, $index);
            $templateProcessor->setValue('titulo', $inv[0]->title, $index);
            $templateProcessor->setValue('tecnica', $inv[0]->technique, $index);
            $templateProcessor->setValue('espacio', '', $index);

            $m = '';
            $t_autor = '';
        }


        $templateProcessor->saveAs('cedula3a.docx');

        return response()->download('cedula3a.docx');
    }

    public function generateDocxc3b($piezas, $investigacion, $autores, $lugares_creacion){

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/public/reportes/template_cedula3b.docx'));

        $templateProcessor->cloneBlock('cedula', count($investigacion));

        $index = 1;
        $m = '';
        //dependiendo si las piezas tienen investigaciones, se gnera su cédula si no no se genera, pues no tiene caso crear fichas sin información.
        foreach($investigacion as $inv){

            $lugar = Catalog_element::find($inv[0]->place_of_creation_id);
            $templateProcessor->setValue('fecha_creacion', $inv[0]->creation_date, $index);
            $templateProcessor->setValue('lugar_creacion', $lugar->title, $index);
            $templateProcessor->setValue('titulo', $inv[0]->title, $index);
            $templateProcessor->setValue('tecnica', $inv[0]->technique, $index);
            $templateProcessor->setValue('espacio', '', $index);

            $m = '';
            $t_autor = '';
        }


        $templateProcessor->saveAs('cedula3b.docx');

        return response()->download('cedula3b.docx');
    }


    public function descargarc1(){
        header("Content-Disposition: attachment; filename=cedula1.docx; charset=iso-8859-1");
        echo file_get_contents('cedula1.docx');
    }

    public function descargarc2(){
        header("Content-Disposition: attachment; filename=cedula2.docx; charset=iso-8859-1");
        echo file_get_contents('cedula2.docx');
    }

    public function descargarc3a(){
        header("Content-Disposition: attachment; filename=cedula3a.docx; charset=iso-8859-1");
        echo file_get_contents('cedula3a.docx');
    }

    public function descargarc3b(){
        header("Content-Disposition: attachment; filename=cedula3b.docx; charset=iso-8859-1");
        echo file_get_contents('cedula3b.docx');
    }

}

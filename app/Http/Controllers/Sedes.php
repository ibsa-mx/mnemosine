<?php

namespace Mnemosine\Http\Controllers;

use Illuminate\Http\Request;
use Mnemosine\Venue;
use Mnemosine\Exhibition;
use Mnemosine\Contact;
use Mnemosine\Institution;

class Sedes extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $instituciones = Institution::all();
        $contactos = Contact::all();
        $sedes = Venue::all();

        return view('movimiento.sede.index', compact('sedes', 'contactos', 'instituciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $instituciones = Institution::all();
        $contactos = null;
        return view('movimiento.sede.new', compact('instituciones', 'contactos'));
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
        $sede = new Venue();
        $sede->fill($request->all());
        $sede->save();

        flash()->success('La sede se creo con exito');

        return redirect()->route('sedes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sede = Venue::findOrFail($id);
        $instituciones = Institution::all();
        $contactos = Contact::where('institution_id', '=', $sede->institution_id)->get();

        return view('movimiento.sede.edit', compact('instituciones', 'contactos', 'sede'));
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
        $this->validateRequest($request);
        $sede = Venue::findOrFail($id);
        $sede->fill($request->all());
        $sede->save();

        flash()->success('La sede se ha editado con exito');
        return redirect()->route('sedes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sede = Venue::findOrFail($id);
        $sede->Delete();

        flash()->success('La sede se elimino correctamente');
        return redirect()->route('sedes.index');
    }

    public function getExhibitions(Request $request, $id){

        if($request->ajax())
        {
            $data = Exhibition::where('institution_id', '=', $id)->get();
        }
        return response()->json($data);
    }

    public function getIdInstitucion(Request $request){
        if($request->ajax())
        {
            $data = Institution::select('id')->where('name', config('app.institution'))->first();
        }
        return response()->json($data);
    }

    public function validateRequest(Request $request)
    {
        $customMessages = [
            'name.required' => 'El nombre es un campo requerido',
            'contact_id.required' => 'El contacto es un campo requerido',
            'institution_id.required' => 'La institución es un campo requerido',
        ];

        $this->validate($request, [
            'name' => 'required',
            'contact_id' => 'required',
            'institution_id' => 'required',
        ], $customMessages);
    }

}

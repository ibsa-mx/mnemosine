<?php

namespace Mnemosine\Http\Controllers;

use Illuminate\Http\Request;
use Mnemosine\Exhibition;
use Mnemosine\Contact;
use Mnemosine\Institution;

class Exposiciones extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contactos = Contact::all();
        $instituciones = Institution::all();
        $exposiciones = Exhibition::all();
        return view('movimiento.exposicion.index', compact('exposiciones', 'contactos', 'instituciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $contactos = null;
        $instituciones = Institution::all();
        return view('movimiento.exposicion.new', compact('contactos', 'instituciones'));
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
        $exposicion = new Exhibition();
        $exposicion->fill($request->all());
        $exposicion->save();
        flash()->success('La exposición se ha creado con exito.');
        return redirect()->route('exposiciones.index');
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
        $exposicion = Exhibition::findOrFail($id);
        $contactos = Contact::where('institution_id', '=', $exposicion->institution_id)->get();
        $instituciones = Institution::all();

        return view('movimiento.exposicion.edit', compact('contactos', 'instituciones', 'exposicion'));
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
        $exposicion = Exhibition::findOrFail($id);
        $exposicion->fill($request->all());
        $exposicion->save();

        flash()->success('La exposición se ha editado correctamente.');
        return redirect()->route('exposiciones.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $exposicion = Exhibition::findOrFail($id);
        $exposicion->Delete();

        flash()->success('La exposición se ha eliminado correctamente');
        return redirect()->route('exposiciones.index');
    }

    public function validateRequest(Request $request)
    {
        $customMessages = [
            'name.required' => 'El nombre es un campo requerido',
            'contact_id.required' => 'El contacto de la institución es un campo requerido',
            'institution_id.required' => 'La institución es un campo requerido',
        ];

        $this->validate($request, [
            'name' => 'required',
            'contact_id' => 'required',
            'institution_id' => 'required',
        ], $customMessages);
    }

    public function getContacts(Request $request, $id){
        if($request->ajax()){
            $data = Contact::where('institution_id', '=', $id)->get();
        }
         return response()->json($data);
    }
}

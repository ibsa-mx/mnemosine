<?php

namespace Mnemosine\Http\Controllers;

use Illuminate\Http\Request;
use Mnemosine\Institution;
use Mnemosine\Authorizable;
use Mnemosine\Country;
use Mnemosine\State;

class Instituciones extends Controller
{
    use Authorizable;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $institucion = Institution::all();
        return view('movimiento.institucion.index', compact('institucion'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $paises = Country::all();
        $estados = null;
        return view('movimiento.institucion.new', compact('paises', 'estados'));
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
        $institution = new Institution();
        $institution->fill($request->all());
        $institution->save();

        flash()->success('La institución se ha creado con exito.');
        return redirect()->route('instituciones.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('movimiento.institucion.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $paises = Country::all();
        $institucion = Institution::findOrFail($id);
        $estados = State::where('country_id', '=', $institucion->country_id)->get();

        return view('movimiento.institucion.edit', compact('institucion', 'paises', 'estados'));
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
        $institucion  =  Institution::findOrFail($id);
        $institucion->fill($request->all());
        $institucion->save();
        flash()->success('La institución se ha editado.');
        return redirect()->route('instituciones.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $institucion = Institution::findOrFail($id);
        $institucion->Delete();
        flash()->success('Se elimino correctamente');
        return redirect()->route('instituciones.index');
    }

     public function validateRequest(Request $request)
    {
        $customMessages = [
            'name.unique' => 'El nombre de la institución ya existe',
            'address.required' => 'La dirección es un campo requerido',
            'phone.required' => 'El número de télefono es un campo requerido',
            //'email.required'  => 'El correo debe contener al menos dos caracteres, escriba otro',
            'country_id.required' => 'El país es un campo requerido',
        ];
        $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required|min:7',
            //'email' => 'required',
            'country_id' => 'required',
        ], $customMessages);
    }

    public function getStates(Request $request, $id){
        if ($request->ajax()) {
            $states = State::select('id', 'name')->where('country_id', '=', $id)->get();
            return response()->json($states);
        }
    }
}

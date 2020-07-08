<?php

namespace Mnemosine\Http\Controllers;

use Illuminate\Http\Request;
use Mnemosine\Catalog;
use Mnemosine\Catalog_element;
use Mnemosine\Institution;
use Mnemosine\Contact;

class Contactos extends Controller
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

        return view('movimiento.contacto.index', compact('contactos', 'instituciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $instituciones = Institution::all();
        $titulosCatalog = Catalog::where('code', 'treatment')->get()->first();
        $titulos = Catalog_element::where('catalog_id', $titulosCatalog->id)->get();

        return view('movimiento.contacto.new', compact('instituciones', 'titulos'));
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
        $contacto = new Contact();
        $contacto->fill($request->all());
        $contacto->save();
        flash()->success('El contacto se ha creado con exito.');
        return redirect()->route('contactos.index');
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
        $instituciones = Institution::all();
        $titulosCatalog = Catalog::where('code', 'treatment')->get()->first();
        $titulos = Catalog_element::where('catalog_id', $titulosCatalog->id)->get();
        $contacto = Contact::findOrFail($id);

        return view('movimiento.contacto.edit', compact('instituciones', 'titulos', 'contacto'));
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
        $contacto = Contact::findOrFail($id);
        $contacto->fill($request->all());
        $contacto->save();
        flash()->success('El contacto se ha editado.');
        return redirect()->route('contactos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contacto = Contact::findOrFail($id);
        $contacto->Delete();
        flash()->success('El contacto se elimino correctamente');
        return redirect()->route('contactos.index');
    }

    public function validateRequest(Request $request)
    {
        $customMessages = [
            'name.required' => 'El nombre es un campo requerido',
            'last_name.required' => 'El apellido paterno es un campo requerido',
            'email.required'  => 'El correo debe contener al menos dos caracteres, escriba otro',
            'institution_id.required' => 'La institución es un campo requerido',
        ];
        $this->validate($request, [
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'institution_id' => 'required',
        ], $customMessages);
    }
}

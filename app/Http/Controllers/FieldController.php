<?php

namespace Mnemosine\Http\Controllers;

use Mnemosine\Module;
use Mnemosine\Field;
use Mnemosine\FieldGroup;
use Mnemosine\Catalog;
use Mnemosine\Role;
use Mnemosine\Gender;
use Illuminate\Http\Request;
use Mnemosine\Authorizable; // permisos

class FieldController extends Controller
{
    use Authorizable; // permisos

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $modules = Module::where('active', 1)->orderBy('order')->get();
        $fieldGroups = FieldGroup::orderBy('order')->get();
        $fields = Field::orderBy('order')->get();
        $catalogs = Catalog::all();
        $roles = Role::all();
        $genders = Gender::all();

        $tiposCampos = ['text' => 'Texto', 'checkbox' => 'Casilla', 'select' => 'Lista', 'multi-select' => 'Lista multiple', 'textarea' => "Texto grande", 'date' => 'Fecha', 'email' => 'Correo electrónico', 'file' => 'Archivo', 'image' => 'Imagen', 'number' => 'Número', 'password' => 'Contraseña', 'radio' => 'Botón de radio', 'range' => 'Rango de numeros', 'tel' => 'Teléfono', 'time' => 'Hora', 'url' => 'Dirección web', 'color' => 'Color'];

        return view('configuraciones.campos.index', compact('modules', 'request', 'fieldGroups', 'fields', 'catalogs', 'roles', 'genders', 'tiposCampos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

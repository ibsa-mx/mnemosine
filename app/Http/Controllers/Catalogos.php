<?php

namespace Mnemosine\Http\Controllers;

use Illuminate\Http\Request;

use Mnemosine\Catalog;

class Catalogos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $catalogs = Catalog::all();
        return view('administracion.catalogo.index', compact('catalogs'));
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
        $validateData = $request->validate([
            'catalogtitle'=>'required']);

        $catalogo = new Catalog();
        $catalogo->title = $request->input('catalogtitle');
        $catalogo->description = $request->input('catalogdescription');
        $catalogo->save();
        flash()->success('El catálogo ha sido creado.');
        return redirect()->route('catalogos.index');
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
        $catalogo = Catalog::findOrFail($id);
        return view('administracion.catalogo.edit', compact('catalogo'));
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
        $validateData = $request->validate([
            'catalogtitle'=>'required']);

        $catalogo = Catalog::findOrFail($id);
        $catalogo->title = $request->input('catalogtitle');
        $catalogo->description = $request->input('catalogdescription');
        $catalogo->save();
        flash()->success('El catálogo ha sido editado.');
        return redirect()->route('catalogos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $catalogo = Catalog::findOrFail($id);
        $catalogo->Delete();
        flash()->success('El catálogo ha sido eliminado.');
        return redirect()->route('catalogos.index');
    }
}

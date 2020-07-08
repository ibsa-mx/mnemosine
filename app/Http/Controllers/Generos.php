<?php

namespace Mnemosine\Http\Controllers;

use Illuminate\Http\Request;
use Mnemosine\Gender;

class Generos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $genders = Gender::orderBy('title')->get();
        return view('administracion.genero.index', compact('genders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administracion.genero.create');
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
            'Titulo', 'title'=>'required']);

        $genero = new Gender();
        $genero->title = $request->input('title');
        $genero->description = $request->input('description');
        $genero->save();

        flash()->success('El género se creo correctamente');
        return redirect()->route('generos.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $genero = Gender::findOrFail($id);
        return view('administracion.genero.edit', compact('genero'));
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
            'Titulo', 'title'=>'required']);

        $genero = Gender::findOrFail($id);
        $genero->title = $request->input('title');
        $genero->description = $request->input('description');
        $genero->save();

        flash()->success('Se edito correctamente el género');
        return redirect()->route('generos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $genero = Gender::findOrFail($id);
        $genero->Delete();

        flash()->success('Se elimino correctamente el género');
        return redirect()->route('generos.index');
    }
}

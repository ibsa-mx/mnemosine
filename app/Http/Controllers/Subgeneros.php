<?php

namespace Mnemosine\Http\Controllers;

use Illuminate\Http\Request;

use Mnemosine\Subgender;

use Mnemosine\Gender;

class Subgeneros extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($gender_id)
    {
        $gender = Gender::findOrFail($gender_id);
        return view('administracion.subgenero.create', compact('gender'));
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
            'Genero', 'gender_id'=>'required',
            'Titulo', 'title'=>'required']);

        $subgenero = new Subgender();
        $subgenero->title = $request->input('title');
        $subgenero->description = $request->input('description');
        $subgenero->gender_id = $request->input('gender_id');
        $subgenero->save();

        flash()->success('El subgénero se creo correctamente');
        return redirect()->route("subgeneros.show", $subgenero->gender_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $gender = Gender::findOrFail($id);
        $subgenders = Subgender::where('gender_id', $id)->get();

        return view('administracion.subgenero.index', compact('subgenders', 'gender'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subgenero = Subgender::findOrFail($id);
        $gender = Gender::findOrFail($subgenero->gender_id);

        return view('administracion.subgenero.edit', compact('gender', 'subgenero', 'id'));
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

        $subgenero = Subgender::findOrFail($id);
        $subgenero->title = $request->input('title');
        $subgenero->description = $request->input('description');
        $subgenero->save();

        flash()->success('El subgénero se edito correctamente');
        return redirect()->route("subgeneros.show", $subgenero->gender_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subgenero = Subgender::findOrFail($id);
        $subgenero->Delete();

        flash()->success('El subgénero se elimino correctamente');
        return redirect()->route("subgeneros.show", $subgenero->gender_id);
    }
}

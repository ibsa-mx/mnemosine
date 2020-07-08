<?php

namespace Mnemosine\Http\Controllers;

use Mnemosine\Set;

use Illuminate\Http\Request;


class Conjuntos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$sets = Set::onlyTrashed()->get();
        $sets = Set::all();
        return view('administracion.conjunto.index', compact('sets'));
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
            'Titulo', 'settitle'=>'required', 
            'Descripción', 'setdescription'=>'required']);

        $set = new Set();
        $set->title = $request->input('settitle');
        $set->description = $request->input('setdescription');
        $set->community_id = 1;
        $set->save();

        return back();
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
        $set = Set::find($id);
       // var_dump($set); exit();
        return view('administracion.conjunto.edit', compact('set'));
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
                        'Titulo', 'settitle'=>'required', 
                        'Descripción', 'setdescription'=>'required']);

        $set = Set::find($id);
        $set->title = $request->input('settitle');
        $set->description = $request->input('setdescription');
        $set->save();

        return redirect('/conjuntos')->with('success', 'Se elimino correctamente ');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $set = Set::find($id);
        $set->Delete();
        return redirect('/conjuntos')->with('warning', 'Se elimino correctamente ');
    }
}

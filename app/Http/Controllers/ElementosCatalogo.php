<?php

namespace Mnemosine\Http\Controllers;

use Illuminate\Http\Request;
use Mnemosine\Catalog;
use Mnemosine\Catalog_element;

class ElementosCatalogo extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'elementtitle'=>'required']);

        $element = new Catalog_element();
        $element->title = $request->input('elementtitle');
        $element->description = $request->input('elementdescription');
        $element->catalog_id = $request->input('catalogo');
        //$element->code = $request->input('elementcode');
        $element->save();

        flash()->success('El elemento de catálogo ha sido creado.');
        return redirect()->route('catalogoElementos.show', $element->catalog_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $elements = Catalog_element::where('catalog_id', $id)->orderBy('title')->get();
        $catalog = Catalog::findOrFail($id);
        return view('administracion.catalogo.elementos.index', compact('elements', 'catalog'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $element = Catalog_element::findOrFail($id);
        return view('administracion.catalogo.elementos.edit', compact('element'));
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
            'elementtitle'=>'required']);

        $element = Catalog_element::findOrFail($id);
        $elementCatalogId = $element->catalog_id;
        $element->title = $request->input('elementtitle');
        //$element->code =$request->input('elementcode');
        $element->description = $request->input('elementdescription');
        $element->save();

        flash()->success('El elemento de catálogo ha sido modificado.');
        return redirect()->route('catalogoElementos.show', $elementCatalogId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $element = Catalog_element::findOrFail($id);
        $elementCatalogId = $element->catalog_id;
        $element->Delete();
        flash()->success('El elemento de catálogo ha sido creado.');
        return redirect()->route('catalogoElementos.show', $elementCatalogId);
    }
}

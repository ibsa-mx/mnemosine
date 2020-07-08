<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Mnemosine\Piece;

Route::get('/', function () {
    return view('auth.login');
});

// Route::get('/inf', function () {
//     phpinfo();
//     return;
// });

Auth::routes(['register' => false]);

Route::group(['middleware' => ['auth']], function() {
    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/consultas', 'ConsultasController@index')->name('consultas');
    Route::get('/consultas/detalle/{id}', 'ConsultasController@detalle')->name('consultas.detalle');
    Route::get('/consultas/xml/{id}', 'ConsultasController@xml')->name('consultas.xml');
    Route::get('/consultas/word/{id}', 'ConsultasController@word')->name('consultas.word');
    Route::get('/consultas/excel/{id}', 'ConsultasController@excel')->name('consultas.excel');
    Route::get('/consultas/search/{keywords?}', 'ConsultasController@search')->name('consultas.search');


    Route::get('/filtrosAjax/{id}', 'Reportes@filtrar_campos');

    Route::get('perfil/changePassword', 'ProfileController@changePassword')->name('perfil.changePassword');
    Route::patch('perfil/updatePassword', 'ProfileController@updatePassword')->name('perfil.updatePassword');

    // los que no se incluyen en resources se deben definir antes
    Route::get('/subgenderAjax/{id}', 'Inventario@getSubgenders');
    Route::get('/investigacionSubgenderAjax/{id}', 'Investigacion@getSubgenders');
    Route::get('subgenders/{id}', 'Inventario@getSubgenders');
    Route::get('stateAjax/{id}', 'Instituciones@getStates');
    Route::get('contactAjax/{id}', 'Exposiciones@getContacts');
    Route::get('exhibitionAjax/{id}', 'Sedes@getExhibitions');
    Route::get('idInstitucionAjax', 'Sedes@getIdInstitucion');

    Route::get('mov1VenuesAjax/{id}', 'Movimientos@getVenuesMov1');
    Route::get('mov1ExhibitionAjax/{id}', 'Movimientos@getExhibitionMov1');
    Route::get('mov1ContactAjax/{id}', 'Movimientos@getContactMov1');

    Route::get('venuesAjax/{id}', 'Movimientos@getVenues');
    Route::get('institutionsAjax/{id}', 'Movimientos@getInstitutions');
    Route::get('codeAjax/{id}', 'Movimientos@getcodeInventory');
    Route::get('searchMov', 'Movimientos@searchIndex')->name('movimientos.search.index');
    Route::get('searchMovResults', 'Movimientos@searchResults')->name('movimientos.search.results');
    Route::get('searchMovResultsExcel', 'Movimientos@searchResultsExcel')->name('movimientos.search.resultsExcel');
    Route::get('resumenMov/{id}', 'Movimientos@resumen_authorizar')->name('movimientos.resumen_authorizar');
    Route::get('return_pieces/{id}', 'Movimientos@return_pieces')->name('movimientos.return_pieces');

    Route::get('restauracion/{pieceId}/listRecords', 'Restauracion@listRecords')->name('restauracion.listRecords');
    Route::get('restauracion/{pieceId}/create', 'Restauracion@create')->name('restauracion.create');
    Route::resource('restauracion', 'Restauracion')->except([
        'create'
    ]);

    Route::get('investigacion/{pieceId}/create', 'Investigacion@create')->name('investigacion.create');
    Route::resource('investigacion', 'Investigacion')->except([
        'create'
    ]);

    Route::get('subgeneros/{genderId}/create', 'Subgeneros@create')->name('subgeneros.create');
    Route::resource('subgeneros', 'Subgeneros')->except([
        'create', 'index'
    ]);

    Route::post('reportes/cedula/{id}', 'Reportes@cedula')->name('reportes.cedula');
    Route::get('reportes/descargarCedula/{cedula}', 'Reportes@descargarCedula')->name('reportes.descargarCedula');

    Route::resources([
        'inventario' => 'Inventario',
        'catalogos' => 'Catalogos',
        'catalogoElementos' => 'ElementosCatalogo',
        'campos' => 'FieldController',
        'usuarios' => 'UserController',
        'roles' => 'RoleController',
        'reportes' => 'Reportes',
        'instituciones' => 'Instituciones',
        'sedes' => 'Sedes',
        'contactos' => 'Contactos',
        'movimientos' => 'Movimientos',
        'exposiciones' => 'Exposiciones',
        'generos' => 'Generos',
    ]);
});

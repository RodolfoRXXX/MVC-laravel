<?php

use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/inicio', function(){
    return view('inicio');
});

##### CRUD de regiones
Route::get('/regiones', function () {
    //obtenemos listado de regiones
    
    /*$regiones = DB::select('SELECT idRegion, regNombre
                                FROM regiones');
    */
    $regiones = DB::table('regiones')->get();

    return view('regiones', [ 'regiones'=>$regiones ]);
});

Route::get('region/create', function(){
    return view('regionCreate');
});

Route::post('/region/store', function(){
    $regNombre = request()->regNombre;
    //Insertamos el valor del formulario
    DB::insert(
        'INSERT INTO regiones
                (regNombre)
            VALUE
                (:regNombre)',
                [$regNombre]
    );
    //Query Builder
    /*DB::table('regiones')
        ->insert([ 'regNombre'=>$regNombre, 'k'=>$v ]);*/

    return redirect('/regiones')
            ->with(['mensaje'=>'Region '.$regNombre.' ha sido agregada correctamente']);
});

##### CRUD de regiones
Route::get('/destinos', function(){
    $destinos = DB::select("SELECT destinos.*, regiones.regNombre FROM destinos, regiones WHERE destinos.idRegion = regiones.idRegion");
    return view('destinos', ['destinos' => $destinos]);
});
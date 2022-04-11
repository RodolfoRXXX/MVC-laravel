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


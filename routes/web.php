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
                                FROM regiones'); //raw sql
    */
    $regiones = DB::table('regiones')->get();//query builder

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
            ->with(['mensaje'=>'Región '.$regNombre.' ha sido agregada correctamente']);
});
Route::get('/region/edit/{id}', function ($id)//Rellena los campos con los valores de la db
{
    //obtenemos datos de la región por su ID
    /*$region = DB::select('SELECT idRegion, regNombre
                            FROM regiones
                            WHERE idRegion = :idRegion',
                        [ $id ]);*/
    $region = DB::table('regiones')
                    ->where( 'idRegion', $id )
                    ->first(); //fetch
    //retornamos vista del formulario con sus datos cargados
    return view('regionEdit', [ 'region' => $region ]);
});
Route::post('/region/update', function(){
    $idRegion  = request()->idRegion;
    $regNombre = request()->regNombre;
    /*DB::update('UPDATE regiones
                SET
                    regNombre = :regNombre
                WHERE idRegion = :idRegion',
                [$regNombre, $idRegion]);*/
    //query builder
    DB::table('regiones')
          ->where( 'idRegion', $idRegion )
          ->update( [ 'regNombre'=>$regNombre ] );

    return redirect('/regiones')
            ->with(['mensaje'=>'Región '.$regNombre.' ha sido modificada']);
});


##### CRUD de destinos
Route::get('/destinos', function(){
    $destinos = DB::select("SELECT destinos.*, regiones.regNombre FROM destinos, regiones WHERE destinos.idRegion = regiones.idRegion");
    return view('destinos', ['destinos' => $destinos]);
});
Route::get('/destino/create', function(){
    $regiones = DB::table('regiones')
                ->select('idRegion', 'regNombre')
                ->get();
    return view('destinoCreate', ['regiones'=>$regiones]);
});
Route::post('/destino/store', function(){
    $destNombre      = request()->destNombre;
    $idRegion        = request()->idRegion;
    $destPrecio      = request()->destPrecio;
    $destAsientos    = request()->destAsientos;
    $destDisponibles = request()->destDisponibles;
        DB::table('destinos')
            ->insert(['destNombre'     =>$destNombre,
                      'idRegion'       =>$idRegion,
                      'destPrecio'     =>$destPrecio,
                      'destAsientos'   =>$destAsientos,
                      'destDisponibles'=>$destDisponibles]);
        
        return redirect('/destinos')
               ->with(['mensaje'=>'El destino '.$destNombre.' ha sido creado.']);
});
Route::get('/destino/edit/{id}', function($idDestino){
    $regiones = DB::table('regiones')
                ->get();
    $destinos = DB::table('destinos')
               ->where('idDestino', $idDestino)
               ->first();
        return view('/destinoEdit', ['regiones'=>$regiones,
                                     'destinos'=>$destinos
                                    ]);
});
Route::post('/destino/update', function(){
    $idDestino       = request()->idDestino;
    $destNombre      = request()->destNombre;
    $idRegion        = request()->idRegion;
    $destPrecio      = request()->destPrecio;
    $destAsientos    = request()->destAsientos;
    $destDisponibles = request()->destDisponibles;
        DB::table('destinos')
            ->where('idDestino', $idDestino)
            ->update(
                    ['destNombre'     =>$destNombre,
                      'idRegion'       =>$idRegion,
                      'destPrecio'     =>$destPrecio,
                      'destAsientos'   =>$destAsientos,
                      'destDisponibles'=>$destDisponibles
                    ]);
        
        return redirect('/destinos')
               ->with(['mensaje'=>'El destino '.$destNombre.' ha sido modificado.']);
});
Route::get('/destino/delete/{id}', function($id){
    $destinos = DB::table('destinos')
                ->where('idDestino', $id)
                ->first();
        return view('destinoDelete', ['destinos'=>$destinos]);
});
Route::post('/destino/destroy', function(){
    $destNombre = request()->destNombre;
    $idDestino   = request()->idDestino;
    DB::table('destinos')
        ->where('idDestino', $idDestino)
        ->delete();
        return redirect('/destinos')
               ->with(['mensaje'=>'El destino '.$destNombre.' ha sido eliminado']);
});
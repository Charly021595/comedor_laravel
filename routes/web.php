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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/welcome', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//prueba de registrar usuarios en tabla laravel
Route::get('/prueba', 'PruebaController@pasar_usuarios')->name('prueba');
Route::get('/prueba_pedidos', 'PruebaController@mover_registros')->name('mover_registros');
Route::get('/prueba_pedidos_cs', 'PruebaController@mover_registros_cs')->name('mover_registros_cs');
Route::get('/prueba_pedidos_gs', 'PruebaController@mover_registros_gs')->name('mover_registros_gs');
Route::get('/platillos', 'PruebaController@Agregar_platillos_sucursales')->name('platillos');

Route::get('/insertar_foto_miss', 'PruebaController@actualizar_foto_miss')->name('foto_miss');

//Comedor
Route::post('/tipo_platillo', 'ComedorController@tipo_platillo')->middleware('auth')->name('tipo_platillo');
Route::post('/info_platillo', 'ComedorController@info_platillo')->middleware('auth')->name('info_plaillo');
Route::post('/guardar_platillo', 'ComedorController@guardar_platillo')->middleware('auth')->name('guardar_platillo');
Route::post('/guardar_platillo_gs', 'ComedorController@guardar_platillo_gs')->middleware('auth')->name('guardar_platillo_gs');
Route::get('/comedor_gs', 'ComedorController@vista_comedor_gs')->middleware('auth')->name('comedor_gs');
Route::post('/listar_comida_gs', 'ComedorController@listar_comida_gs')->middleware('auth')->name('listar_comida_gs');
Route::post('/guardar_platillo_semanal', 'ComedorController@guardar_platillo_semanal')->middleware('auth')->name('guardar_platillo_semanal');
Route::post('/cambiar_estatus_pedido', 'ComedorController@cambiar_estatus_pedido')->middleware('auth')->name('cambiar_estatus_pedido');
Route::post('/nomina', 'ComedorController@nomina')->middleware('auth')->name('nomina');
Route::post('/enviar_nomina', 'ComedorController@enviar_nomina')->middleware('auth')->name('enviar_nomina');
Route::get('/listado_menu', 'ComedorController@vista_listado_menu')->middleware('auth')->name('listado_menu');
Route::post('/guardar_nuevo_platillo', 'ComedorController@guardar_nuevo_platillo')->middleware('auth')->name('guardar_nuevo_platillo');
Route::get('/listar_platillos', 'ComedorController@listar_platillos')->middleware('auth')->name('listar_platillos');

//Usuario
Route::post('/get_sede', 'UserController@traer_sede')->middleware('auth')->name('get_sede');
Route::post('/get_datos_usuario', 'UserController@traer_datos_usuarios')->middleware('auth')->name('get_datos_usuario');


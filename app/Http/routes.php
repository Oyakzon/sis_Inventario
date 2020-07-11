<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/', function () {
    return view('auth/login');
});
Route::get('/acerca', function () {
    return view('acerca');
});
Route::auth();
//RTUAS PRINCIPALES
Route::get('/home', 'HomeController@index');
Route::resource('almacen/categoria', 'CategoriaController');
Route::resource('almacen/articulo', 'ArticuloController');
Route::resource('ventas/cliente', 'ClienteController');
Route::resource('compras/proveedor', 'ProveedorController');
Route::resource('compras/ingreso', 'IngresoController');
Route::resource('ventas/venta', 'VentaController');
Route::resource('seguridad/usuario', 'UsuarioController');
Route::resource('perdidas/perdida', 'PerdidaController');

//RUTA PARA ACTIVAR
Route::get('activar{id}', 'ArticuloController@activar');
//REPORTES GENERALES
Route::get('reportecategorias', 'CategoriaController@reporte');
Route::get('reportearticulos', 'ArticuloController@reporte');
Route::get('reporteperdidas', 'PerdidaController@reporte');
Route::get('reporteclientes', 'ClienteController@reporte');
Route::get('reporteproveedores', 'ProveedorController@reporte');
Route::get('reporteventas', 'VentaController@reporte');
Route::get('reporteventa/{id}', 'VentaController@reportec');
Route::get('reporteingresos', 'IngresoController@reporte');
Route::get('reporteingreso/{id}', 'IngresoController@reportec');
Route::get('reporteusuarios', 'UsuarioController@reporte');
Route::get('/{slug?}', 'HomeController@index');



<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
  return $request->user();
});


Route::prefix('v1')->group(function(){
  Route::post('/pedidos','ApiV1\PedidosApiController@store');
  Route::get('/pedidos/{idpedido}','ApiV1\PedidosApiController@show')->where(['idpedido' => '[0-9]+']);
  Route::get('/pedidos/{idpedido}/contador','ApiV1\PedidosApiController@contador')->where(['idpedido' => '[0-9]+']);
  /*Route::put('/pedidos/{idpedido}/{codigoProducto}/{cantidad}','ApiV1\PedidosApiController@update');*/

  /*Route::get('/pedidos/{idpedido}/add/{codigoProducto}/{cantidad}','ApiV1\PedidosApiController@addproduct')
      ->where(['idpedido' => '[0-9]+', 'codigoProducto' => '[0-9]+', 'cantidad' => '[0-9]+']);*/


  Route::get('/pedidos/{ckey}','ApiV1\PedidosApiController@showPedidoByCKey')
      ->where(['ckey' =>  '[a-zA-Z0-9]+']);

  //POST
  Route::post('/pedidos/{ckey}/{producto_id}/{cantidad}','ApiV1\PedidosApiController@addProductoCantidadByCKey')
      ->where(['ckey' =>  '[a-zA-Z0-9]+', 'producto_id' => '[0-9]+', 'cantidad' => '[0-9]+']);

  //PUT
  Route::put('/pedidos/{ckey}/{producto_id}/{cantidad}','ApiV1\PedidosApiController@updateProductoCantidadByCKey')
      ->where(['ckey' =>  '[a-zA-Z0-9]+', 'codigoProducto' => '[0-9]+', 'cantidad' => '[0-9]+']);

  //DELETE
  Route::delete('/pedidos/{ckey}/{producto_id}','ApiV1\PedidosApiController@removeProductByCKey')
      ->where(['ckey' =>  '[a-zA-Z0-9]+', 'producto_id' => '[0-9]+']);


});


/*
#enviar variable data con json ejemplo:
data={'subject':'demo', 'body':'<p>prueba</p>', 'email':'macosta@digitrafico.com'}

/api/v1/emailsimple/
*/

<?php
Route::prefix('sistema')->group(function(){
  Route::prefix('login')->group(function(){
    Route::get('/','Sistema\LoginController@Login');
    Route::post('/authentication','Sistema\LoginController@authentication');
    Route::get('/close','Sistema\LoginController@Close');
    Route::any('/forgot','Sistema\LoginController@forgot');
    Route::get('/reset/{ckey}','Sistema\LoginController@reset')->where(['ckey' => '[a-zA-Z0-9]+']);
  });

  Route::prefix('usuarios')->group(function(){
    Route::get('/','Sistema\UsuariosController@index');
    Route::get('/{id}','Sistema\UsuariosController@show')->where(['id' => '[0-9]+']);
    Route::get('/search','Sistema\UsuariosController@search');
    Route::get('/paginate','Sistema\UsuariosController@paginate');
    Route::get('/form','Sistema\UsuariosController@customform');
    Route::post('/save','Sistema\UsuariosController@customsave');
    Route::post('/{id}/action/{action}','Sistema\UsuariosController@action')->where(['id' => '[0-9]+', 'action' => '.*']);
  });

  Route::prefix('perfiles')->group(function(){
    Route::get('/','Sistema\PerfilesController@index');
    Route::get('/{id}','Sistema\PerfilesController@show')->where(['id' => '[0-9]+']);
    Route::get('/search','Sistema\PerfilesController@search');
    Route::get('/paginate','Sistema\PerfilesController@paginate');
    Route::get('/openlist','Sistema\PerfilesController@openlist');
    Route::get('/form','Sistema\PerfilesController@form');
    Route::post('/save','Sistema\PerfilesController@save');
    Route::post('/{id}/action/{action}','Sistema\PerfilesController@action')->where(['id' => '[0-9]+', 'action' => '.*']);
  });

  Route::prefix('clientes')->group(function(){
    Route::get('/','Sistema\ClientesController@index');
    Route::get('/{id}','Sistema\ClientesController@show')->where(['id' => '[0-9]+']);
    Route::get('/search','Sistema\ClientesController@search');
    Route::get('/paginate','Sistema\ClientesController@paginate');
    Route::get('/form','Sistema\ClientesController@form');
    Route::post('/save','Sistema\ClientesController@save');
    Route::post('/{id}/action/{action}','Sistema\ClientesController@action')->where(['id' => '[0-9]+', 'action' => '.*']);
  });

  Route::prefix('paginas')->group(function(){
    Route::get('/','Sistema\PaginasController@index');
    Route::get('/{id}','Sistema\PaginasController@show')->where(['id' => '[0-9]+']);
    Route::get('/search','Sistema\PaginasController@search');
    Route::get('/paginate','Sistema\PaginasController@paginate');
    Route::get('/form','Sistema\PaginasController@customform');
    Route::post('/save','Sistema\PaginasController@customsave');
    Route::post('/{id}/action/{action}','Sistema\PaginasController@action')->where(['id' => '[0-9]+', 'action' => '.*']);
  });

  Route::prefix('banners')->group(function(){
    Route::get('/','Sistema\BannersController@index');
    Route::get('/{id}','Sistema\BannersController@show')->where(['id' => '[0-9]+']);
    Route::get('/search','Sistema\BannersController@search');
    Route::get('/paginate','Sistema\BannersController@paginate');
    Route::get('/form','Sistema\BannersController@form');
    Route::post('/save','Sistema\BannersController@savecustom');
    Route::post('/{id}/action/{action}','Sistema\BannersController@action')->where(['id' => '[0-9]+', 'action' => '.*']);
  });

  Route::prefix('invitaciones')->group(function(){
    Route::get('/','Sistema\ServiciosController@index');
    Route::get('/{id}','Sistema\ServiciosController@show')->where(['id' => '[0-9]+']);
    Route::get('/search','Sistema\ServiciosController@search');
    Route::get('/paginate','Sistema\ServiciosController@paginate');
    Route::get('/form','Sistema\ServiciosController@form');
    Route::post('/save','Sistema\ServiciosController@save');
    Route::post('/{id}/action/{action}','Sistema\ServiciosController@action')->where(['id' => '[0-9]+', 'action' => '.*']);
  });

  Route::prefix('productos')->group(function(){
    Route::get('/','Sistema\ProductosController@index');
    Route::get('/{id}','Sistema\ProductosController@show')->where(['id' => '[0-9]+']);
    Route::get('/search','Sistema\ProductosController@search');
    Route::get('/paginate','Sistema\ProductosController@paginate');
    Route::get('/form','Sistema\ProductosController@form');
    Route::post('/save','Sistema\ProductosController@savecustom');
    Route::post('/{id}/action/{action}','Sistema\ProductosController@action')->where(['id' => '[0-9]+', 'action' => '.*']);
  });

  Route::prefix('logs')->group(function(){
    Route::get('/','Sistema\LogsController@index');
    Route::get('/{id}','Sistema\LogsController@show')->where(['id' => '[0-9]+']);
    Route::get('/search','Sistema\LogsController@search');
    Route::get('/paginate','Sistema\LogsController@paginate');
  });

  Route::prefix('configuraciones')->group(function(){
    Route::get('/','Sistema\ConfiguracionesController@index');
    Route::get('/{id}','Sistema\ConfiguracionesController@show')->where(['id' => '[0-9]+']);
    Route::get('/search','Sistema\ConfiguracionesController@search');
    Route::get('/paginate','Sistema\ConfiguracionesController@paginate');
    Route::get('/form','Sistema\ConfiguracionesController@form');
    Route::post('/save','Sistema\ConfiguracionesController@save');
  });

  Route::prefix('/')->group(function(){
    Route::get('/','Sistema\LoginController@index');
    Route::get('/home', 'Sistema\HomeController@index');
    Route::get('/table/{table}/last_updated','ApiController@get_last_updated')->where(['table' => '.*']);
    Route::get('/jsontable/{table}','Sistema\HomeController@getJsonTable')->where(['table' => '.*']);
  });
});

Route::prefix(get_locale_val(Request::segment(1)))->group(function(){
  # crear url con idioma "/{{ app()->getLocale() }}/contacto/";

  Route::prefix('cliente')->group(function(){
    Route::prefix('login')->group(function(){
      Route::get('/','Cliente\LoginController@login');
      Route::post('/authentication','Cliente\LoginController@authentication');
      Route::get('/close','Cliente\LoginController@close');
      Route::any('/forgot','Cliente\LoginController@forgot');
      Route::get('/reset/{ckey}','Cliente\LoginController@reset')->where(['ckey' => '[a-zA-Z0-9]+']);
      Route::any('/register','Cliente\LoginController@register');
      Route::get('/confirm/{ckey}','Cliente\LoginController@confirmregister')->where(['ckey' => '[a-zA-Z0-9]+']);
    });

    Route::prefix('pedidos')->group(function(){
      Route::get('/','Cliente\PedidosController@index');
      Route::get('/{id}','Cliente\PedidosController@show')->where(['id' => '[0-9]+']);
      Route::post('/{id}/generar','Cliente\PedidosController@generarpedido')->where(['id' => '[0-9]+']);
    });

    Route::prefix('/')->group(function(){
      Route::get('/','Cliente\HomeController@index');
      Route::any('/cuenta','Cliente\HomeController@cuenta_form');
      Route::get('/message','Cliente\LoginController@print_message');
    });
  });

  Route::prefix('/')->group(function(){
    Route::get('/','Website\HomeController@index');
    Route::get('/maquina-de-invitaciones','Website\HomeController@maquina_invitaciones');
    Route::get('/invitaciones-web','Website\HomeController@invitaciones_web');
    Route::get('/contacto','Website\HomeController@contacto');
    Route::get('/nosotros','Website\HomeController@nosotros');


    Route::prefix('productos')->group(function(){
      Route::get('/','Website\ProductosController@list')->where(['tipo' => '[a-zA-Z0-9]+']);
      Route::get('/{id}','Website\ProductosController@show')->where(['id' => '[0-9]+']);
      Route::get('/{tipo}/categoria/{categoria_id}','Website\ProductosController@list_by_cat')->where(['tipo' => '[a-zA-Z0-9]+','categoria_id' => '[0-9]+']);
      Route::get('/{tipo}/subcategoria/{subcategoria_id}','Website\ProductosController@list_by_subcat')->where(['tipo' => '[a-zA-Z0-9]+','categoria_id' => '[0-9]+']);
      Route::get('/{tipo}','Website\ProductosController@list_by_tipo')->where(['tipo' => '[a-zA-Z0-9]+']);

    });

    Route::get('/p{id}/{slug?}', 'Website\PaginaDinamicaController@index')->where(['id' => '[0-9]+']);

    Route::get('/clearcache','Website\HomeController@clearcache');
    /*Route::get('/testmodelos','TestController@testmodelos');*/

    // Route::prefix('login')->group(function(){
    //   Route::get('/','Website\LoginController@login');
    // });

    Route::prefix('carrito')->group(function(){
    Route::get('/{ckey}','Website\PedidosController@ShowCarritoByCKey')->where(['ckey' => '[a-zA-Z0-9]+']);
    Route::get('/{ckey}/resumen','Website\PedidosController@ShowResumenByCKey')->where(['ckey' => '[a-zA-Z0-9]+']);
  });

    Route::get('/ajax/captcha','Website\AjaxController@captcha');
    Route::post('/ajax/{control}/{action}','Website\AjaxController@call')->where(['control' => '[a-zA-Z0-9]+','action' => '[a-zA-Z0-9]+']);;
    Route::post('/uploader','ApiController@uploader');
  });


});

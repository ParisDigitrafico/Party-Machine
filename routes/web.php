<?php
Route::prefix('sistema')->group(function(){
  Route::get('/','Sistema\LoginController@index');

  Route::prefix('login')->group(function(){
    Route::get('/','Sistema\LoginController@Login');
    Route::post('/authentication','Sistema\LoginController@authentication');
    Route::get('/close','Sistema\LoginController@Close');
    Route::any('/forgot','Sistema\LoginController@forgot');
    Route::get('/reset/{ckey}','Sistema\LoginController@reset')->where(['ckey' => '[a-zA-Z0-9]+']);
  });

  Route::get('/home', 'Sistema\HomeController@index');

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

  Route::prefix('plantillas')->group(function(){
    Route::get('/','Sistema\PlantillasController@index');
    Route::get('/{id}','Sistema\PlantillasController@show')->where(['id' => '[0-9]+']);
    Route::get('/search','Sistema\PlantillasController@search');
    Route::get('/paginate','Sistema\PlantillasController@paginate');
    Route::get('/form','Sistema\PlantillasController@form');
    Route::post('/save','Sistema\PlantillasController@savecustom');
    Route::post('/{id}/action/{action}','Sistema\PlantillasController@action')->where(['id' => '[0-9]+', 'action' => '.*']);
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

  Route::get('/table/{table}/last_updated','ApiController@get_last_updated')->where(['table' => '.*']);
  Route::get('/jsontable/{table}','Sistema\HomeController@getJsonTable')->where(['table' => '.*']);
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

    Route::get('/','Cliente\HomeController@index');

    Route::prefix('visas')->group(function(){
      Route::get('/','Cliente\VisasController@list');
      Route::get('/pais/{pais}','Cliente\VisasController@get_visas_por_pais');
    });
  });

  Route::prefix('/')->group(function(){
    Route::get('/','Website\HomeController@index');
    Route::get('/nosotros','Website\HomeController@nosotros');
    Route::get('/destinos','Website\HomeController@destinos');
    Route::get('/tiposvisas','Website\HomeController@tiposvisas');
    Route::get('/servicios','Website\HomeController@servicios');
    Route::get('/contacto','Website\HomeController@contacto');
    Route::get('/faqs','Website\HomeController@faqs');
    Route::get('/aviso-privacidad','Website\HomeController@avisoprivacidad');
    Route::get('/thank-you','Website\HomeController@thankyou');

    Route::prefix('/servicios')->group(function(){
      // Route::get('/primeravez','Website\HomeController@primeravez');
      // Route::get('/perdon','Website\HomeController@perdon');
      // Route::get('/renovacion','Website\HomeController@renovacion');
      // Route::get('/emergencia','Website\HomeController@emergencia');
      Route::get('/construccion','Website\HomeController@construccion');
    });

    Route::get('/p{id}/{slug?}', 'Website\PaginaDinamicaController@index')->where(['id' => '[0-9]+']);

    Route::get('/clearcache','Website\HomeController@clearcache');
    /*Route::get('/testmodelos','TestController@testmodelos');*/

    Route::prefix('login')->group(function(){
      Route::get('/','Website\LoginController@login');
    });
  });

  Route::get('/ajax/captcha','Website\AjaxController@captcha');
  Route::post('/ajax/{control}/{action}','Website\AjaxController@call')->where(['control' => '[a-zA-Z0-9]+','action' => '[a-zA-Z0-9]+']);;
  Route::post('/uploader','ApiController@uploader');
});

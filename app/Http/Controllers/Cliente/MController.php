<?php
namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;

class MController extends Controller
{
  public function __construct()
  {
    $this->app = "cliente";

    $this->middleware(function($request, $next){

      /*if(!empty(cookie("uid")) && cookie("app") === $this->app)
      {
        $control->relogin(cookie("uid"));
      }*/

      if(intval(session("usuario_id")) > 0 && session("app") === $this->app)
      {
        return $next($request);
      }

      return redirect()->to("/cliente/login/close/");
    });
  }
}
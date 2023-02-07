<?php
namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;

class MController extends Controller
{
	public function __construct()
	{
    $this->middleware(function($request, $next){
      /*$objCtrlSesion = new SysControlSesion();*/

      /*if(intval(session("usuario_id")) > 0 && session("app") === "sistema")*/
      {
        /*if($objCtrlSesion->Existe(session("usuario_id"), session("medio"), session("dispositivo_fp")))
        {*/
          return $next($request);
        /*}*/
      }

      header("Location:/sistema/login/close/");
      exit;
    });
	}
}
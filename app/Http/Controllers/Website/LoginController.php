<?php
namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Cookie;

use App\Helpers\CryptoJSAES;
use App\Helpers\MensajeNotificacion;

use App\Models\AccUsuario;
use App\Models\AccModulo;
use App\Models\AccPermiso;
use App\Models\AccControlSesion;
use App\Models\AccRestablecerContrasenia;

class LoginController extends Controller
{
  public function login()
  {
    $response = array();

    /*$cApp = 'sistema';

    if(empty(request()->cookie('fpt')))
    {
      Cookie::queue(Cookie::make('fpt', 'sistema', 999999, null, null, true));
    }

    if((intval(session("usuario_id")) > 0 && session("app") == $cApp) || request()->cookie('app') == $cApp)
    {
      header("Location:/sistema/home/");
      exit;
    }

    session()->put('SECRETKEYLOGIN', sha1(microtime(true)));*/

    return view('website.client.login.login_form', $response)->render();
  }

  public function process($usuario_id)
  {
    $response = false;

    $objCtrlSesion = new AccControlSesion();

    $usuario = AccUsuario::select()->filterStatus(1)->where("id", "=", $usuario_id)->first();

    if($usuario)
    {
      $arrModulo  = array();
      $arrPermiso = array();

      session()->put('es_super', $usuario->es_super == 1);

      if(session("es_super"))
      {
        $permisos = AccPermiso::ConsultarPermisosSuperUsuario();
      }
      else
      {
        $permisos = AccPermiso::ConsultarPermisosPorUsuario($usuario->id);
      }

      foreach($permisos as $permiso)
      {
        $arrModulo[]  = $permiso->idmodulo;
        $arrPermiso[] = $permiso->clave;
      }

      $arrModulo = AccModulo::ObtenerArrModuloPorId(array_filter(array_unique($arrModulo)));

      $cApp   = "sistema";
      $cMedio = "WEB";

      session()->put('app', $cApp);

      session()->put('usuario_id', $usuario->id);
      session()->put('usuario', $usuario->user);

      session()->put('es_cliente', $usuario->es_cliente == 1);

      session()->put('modulos', $arrModulo);
      session()->put('permisos', $arrPermiso);

      session()->put('medio', $cMedio);
      session()->put('dispositivo_fp', $txtFpjs);
      session()->put('token_fp', sha1(microtime(true)));

      $Dato = array();

      $record = $objCtrlSesion->where("app",$cApp)->where("usuario_id", $usuario->id)->where("medio", $cMedio)->first();

      if(is_null($record))
      {
        $Dato["usuario_id"]  = $usuario->id;
        $Dato["app"]        = $cApp;
        $Dato["medio"]      = $cMedio;
        $Dato["created_at"] = now();

        $objCtrlSesion->insert($Dato);
      }

      $response = true;
    }
    else
    {
      session()->flush();
      session()->save();

      setcookie('app', '', 0, '/');
      setcookie('uid', '', 0, '/');
    }

    return $response;
  }

  public function authentication(Request $request)
  {
    $response = array();

    if(!empty($request->get("txtUser")) && !empty($request->get("txtPass")))
    {
      $sk = session("SECRETKEYLOGIN");

      $txtUser = trim(CryptoJSAES::decrypt($request->get("txtUser"), $sk));
      $txtPass = trim(CryptoJSAES::decrypt($request->get("txtPass"), $sk));
      $optRmbr = trim(CryptoJSAES::decrypt($request->get("optRmbr"), $sk));

      $usuario = AccUsuario::select("*")->FilterStatus(1)->FilterUserPass($txtUser, $txtPass)->first();

      if($usuario)
      {
        $bAux = $this->process($usuario->id);

        if($bAux === true)
        {
          if($optRmbr)
          {
            $iMinutes = 60 * 24 * 30;

            Cookie::queue(Cookie::make('app', 'sistema', $iMinutes, null, null, true));
            Cookie::queue(Cookie::make('uid', $usuario->id, $iMinutes, null, null, true));
          }

          $response["success"] = true;
        }
      }
      else
      {
        $response["message"] = "Los datos de acceso no son correctos, favor de verificar.";
      }
    }

    return response()->json($response);
  }

  public function forgot(Request $request)
  {
    $response = array();

    $objUsuario = new SysUsuario();
    $objResCont = new SysRestablecerContrasenia();

    if($request->isMethod('post'))
    {
      $cCorreo = trim(strtolower($request->get("txtCorreo")));

      $usuario_id = $objUsuario->EsCuentaActiva($cCorreo);

      if($usuario_id)
      {
        $Dato = array();

        if($objResCont->ConsultarKeyPassVigente($usuario_id))
        {
          $response["success"] = true;

          if($request->get("isResend"))
          {
            $response["message"] = "Se ha reenviado un mensaje a su correo con las intrucciones para restablecer la contraseña, ";
            $response["message"].= "si no lo encuentra revise en su bandeja de SPAM.";
          }
          else
          {
            $response["message"] = "Se ha enviado un mensaje a su correo con las intrucciones para restablecer la contraseña, ";
            $response["message"].= "si no lo encuentra revise en su bandeja de SPAM.";
          }

          MensajeNotificacion::EnviarCorreoInstruccionRestablecer($cCorreo, $objResCont->keypass);
        }
        else
        {
          $Dato["keypass"]    = md5(now());
          $Dato["usuario_id"]  = $usuario_id;
          $Dato["created_at"] = now();

          $objResCont->Agregar($Dato);

          MensajeNotificacion::EnviarCorreoInstruccionRestablecer($cCorreo, $Dato["keypass"]);

          $response["success"] = true;
          /*$response["message"] = "key creada";     */
        }
      }
      else
      {
        $response["message"] = "La cuenta no existe o esta inactiva, favor de contactar con soporte.";
      }

      return response()->json($response);
    }
    else
    {
      return view('sistema.login.basico_forgot_password', $response)->render();
    }
  }

  public function reset(Request $request)
  {
    $response = array();

    $objUsuario = new SysUsuario();
    $objResCont = new SysRestablecerContrasenia();
    $objMenNot  = new MensajeNotificacion();

    $objResCont->Consultar("keypass",$request->get("k"),"","",""," created_at >= DATE_SUB(STR_TO_DATE('".now()."','%Y-%m-%d %H:%i:%s'), INTERVAL 24 HOUR) ");

    $cEnlace = '<p style="text-align:center"><a href="'.get_host().'/sistema/login/">'.get_host().'/sistema/login/</a></p>';

    $response["message"] = "";

    if(!empty($objResCont->keypass))
    {
      if($objResCont->status == 1)
      {
        $response["message"].= "<p style='text-align: justify'>El c&oacute;digo para restablecer la contrase&ntilde;a que esta utilizando ya ha sido usado previamente.</p>";
        $response["message"].= "<center>________________________________________</center><br />";
        $response["message"].= $cEnlace;
      }
      else
      {
        $objUsuario->ConsultarRegistro($objResCont->usuario_id);

        if($objUsuario->id)
        {
          $cUser = $objUsuario->user;
          $cPass = get_random_string(8);

          $response["message"] = "<p style='text-align: justify'>Estimado(a) <b>$objUsuario->nombre</b>, te proporcionamos los datos de acceso de tu cuenta,
                      hemos enviado un correo electronico con esta información por seguridad:</p>";

          $response["message"].= "Usuario: <b>$objUsuario->user</b><br />Nueva Contraseña: <b>$cPass</b>";
          $response["message"].= "<br /><br />";

          $response["message"].= "<p style='text-align: justify'>Le recomendamos cambiar la contraseña una vez acceda, en el menu <b>editar información</b>.</p>";

          $response["message"].= "<center>________________________________________</center><br />";
          $response["message"].= $cEnlace;

          $objUsuario->Actualizar(array("pass"=>sha1($cPass)),$objUsuario->id);
          $objResCont->Actualizar(array("status"=>1),$objResCont->id);

          MensajeNotificacion::EnviarCorreoContraseniaRestablecida($cUser,$cUser,$cPass);
        }
        else
        {
          $response["message"].= "<p style='text-align: justify'>El usuario no es valido o no existe.</p>";
          $response["message"].= "<center>________________________________________</center><br />";
          $response["message"].= $cEnlace;
        }
      }
    }
    else
    {
      $response["message"].= "<p style='text-align: justify'>El c&oacute;digo para restablecer la contrase&ntilde;a no es valido o ha caducado.</p>";
      $response["message"].= "<center>________________________________________</center><br />";
      $response["message"].= $cEnlace;
    }

    /*session()->put('SECRETKEYLOGIN', sha1(microtime(true)));*/

    return view('sistema.login.basico_reset_password', $response)->render();
    /*return response()->json($response);*/
  }

  public function close(Request $request)
  {

  }
}
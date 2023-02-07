<?php
namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cookie;

use App\Helpers\CryptoJSAES;
use App\Helpers\MensajeNotificacion;

use App\Models\AccUsuario;
use App\Models\AccModulo;
use App\Models\AccPermiso;
use App\Models\AccControlSesion;
use App\Models\AccConfirmarCuenta;

class LoginController extends Controller
{
  public function __construct()
  {
    $this->app = "sistema";
  }

  public function index()
  {
    header("Location:/sistema/login/");
    exit;
  }

  public function login()
  {
    $response = array();

    if(empty(request()->cookie('fpt')))
    {
      $iMinutes = 60 * 24 * 365 * 5;

      $fpt = sha1(microtime(true));

      Cookie::queue(Cookie::make('fpt', $fpt, $iMinutes, null, null, true));
    }

    if((intval(session("usuario_id")) > 0 && session("app") == $this->app) || request()->cookie('app') == $this->app)
    {
      header("Location:/sistema/home/");
      exit;
    }

    session()->put('SECRETKEYLOGIN', sha1(microtime(true)));
    session()->save();
    
    return response()->view('sistema.login.basico',$response)->setStatusCode(203);
  }

  public function relogin($usuario_id, $cApp, $cMedio, $cFpt)
  {
    $response = false;

    $objCtrlSesion = new AccControlSesion();

    $ctrlsesion = $objCtrlSesion->where("usuario_id", $usuario_id)->where("app", $cApp)
                    ->where("medio",$cMedio)->where("dispositivo_fp", $cFpt)->first();

    if($ctrlsesion)
    {
      $response = $this->process($ctrlsesion->usuario_id);
    }

    return $response;
  }

  public function process($usuario_id)
  {
    $response = false;

    $objUsuario    = new AccUsuario();
    $objCtrlSesion = new AccControlSesion();

    $usuario = $objUsuario->filterStatus(1)->whereId($usuario_id)->where("es_bloqueado",0)->first();

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
        $arrModulo[]  = $permiso->modulo_id;
        $arrPermiso[] = $permiso->clave;
      }

      $arrModulo = AccModulo::ObtenerArrModuloPorId(array_filter(array_unique($arrModulo)));

      $cMedio = "WEB";
      $cFpt   = request()->cookie('fpt') ?: sha1(microtime(true));

      session()->put('usuario_id', $usuario->id);
      session()->put('app', $this->app);

      session()->put('usuario', $usuario->user);
      session()->put('es_cliente', $usuario->es_cliente == 1);

      session()->put('modulos', $arrModulo);
      session()->put('permisos', $arrPermiso);

      session()->put('medio', $cMedio);
      session()->put('fpt', $cFpt);

      session()->save();

      $item = $objCtrlSesion->where("usuario_id", $usuario->id)->where("app",$this->app)
                  ->where("medio", $cMedio)->where("dispositivo_fp", $cFpt)->first();

      if(is_null($item))
      {
        $Dato = array();

        $Dato["usuario_id"]     = $usuario->id;
        $Dato["app"]            = $this->app;
        $Dato["medio"]          = $cMedio;
        $Dato["dispositivo_fp"] = $cFpt;
        $Dato["created_at"]     = now();

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
      $objUsuario    = new AccUsuario;
      $objCtrlSesion = new AccControlSesion;

      $sk = session("SECRETKEYLOGIN");

      $txtUser = trim(CryptoJSAES::decrypt($request->get("txtUser"), $sk));
      $txtPass = trim(CryptoJSAES::decrypt($request->get("txtPass"), $sk));
      $optRmbr = trim(CryptoJSAES::decrypt($request->get("optRmbr"), $sk));

      $iMaxInt = intval(get_config_db("MAX_INTENTOS_PASS")) ?: 1;

      $usuario = $objUsuario->FilterStatus(1)->where("user", $txtUser)->first();

      if($usuario)
      {
        if($usuario->es_bloqueado == 0)
        {
          $usuario_valido = $objUsuario->FilterUserPass($txtUser, $txtPass)->first();

          if($usuario_valido)
          {
            $bAux = $this->process($usuario_valido->id);

            if($bAux === true)
            {
              if($optRmbr)
              {
                $iMinutes = 60 * 24 * 30;

                Cookie::queue(Cookie::make('app', $this->app, $iMinutes, null, null, true));
                Cookie::queue(Cookie::make('uid', $usuario_valido->id, $iMinutes, null, null, true));
              }

              $usuario_valido->intentos_fallidos = 0;
              $usuario_valido->es_bloqueado      = 0;

              $usuario_valido->save();

              $response["success"] = true;
            }
            else
            {
              $response["message"] = "Sucedio un error inesperado al tratar de iniciar con su cuenta,
              por favor contacta con nuestro equipo de soporte técnico para solucionar este problema.";
            }
          }
          else
          {
            $usuario->intentos_fallidos = $usuario->intentos_fallidos + 1;

            $response["message"] = "El usuario o la contraseña no son validos.";

            if($usuario->intentos_fallidos >= $iMaxInt)
            {
              $objCtrlSesion->where("usuario_id", $usuario->id)->delete();
              $usuario->es_bloqueado = 1;

              $response["message"] = "Esta cuenta ha sido bloqueda por multiples intentos fallidos de inicio de sesión,
              por favor utiliza nuestro recuperador de contraseñas para acceder.";
            }

            $usuario->save();
          }
        }
        else
        {
          $response["message"] = "Esta cuenta esta bloqueada por multiples intentos fallidos de inicio de sesión,
          por favor utiliza nuestro recuperador de contraseñas para acceder.";
        }
      }
      else
      {
        $response["message"] = "El usuario que estas introduciendo no existe o esta inactivo,
        por favor contacta con nuestro equipo de soporte técnico para solucionar este problema.";
      }
    }

    return response()->json($response);
  }

  public function forgot()
  {
    $response = array();

    if(request()->isMethod('post'))
    {
      $objUsuario         = new AccUsuario;
      $objConfirmarCuenta = new AccConfirmarCuenta;

      $cUser = trim(strtolower(request()->get("correo")));

      $usuario = $objUsuario->where("user", $cUser)->first();

      if($usuario)
      {
        $confirmacion = $objConfirmarCuenta->where("usuario_id", $usuario->id)
                                              ->where("tipo","USUARIO")
                                              ->orderBy("created_at","desc")->first();

        $bAux = false;

        if(!empty($confirmacion->created_at) && get_minutes_diference(now(), $confirmacion->created_at) < (24*60))
        {
          $bAux = MensajeNotificacion::EnviarMensajeInstruccionRestablecer($usuario->user, $confirmacion->ckey);
        }
        else
        {
          $Dato = array();

          $Dato["ckey"]       = md5(now());
          $Dato["usuario_id"] = $usuario->id;
          $Dato["tipo"]       = "USUARIO";
          $Dato["created_at"] = now();

          if($objConfirmarCuenta->insert($Dato))
          {
            $bAux = MensajeNotificacion::EnviarMensajeInstruccionRestablecer($usuario->user, $Dato["ckey"]);
          }
          else
          {
            $response["message"] = "Existe un error al intentar restablecer la contraseña, favor de contactar con soporte técnico.";
          }
        }
      }
      else
      {
        $response["message"] = "No existe una cuenta relacionada al correo electrónico ingresado, favor de verificar.";
      }

      $response["success"] = boolval($bAux);
      $response["message"] = ($response["success"] === true ?
                                "" : ( $response["message"] ?: trans("general.error_inesperado") ));

      return response()->json($response);
    }
    else
    {
      return view('sistema.login.basico_forgot_password', $response)->render();
    }

    return view('sistema.login.basico_forgot_password', $response)->render();
  }

  public function reset($ckey)
  {
    $response = array();

    $objUsuario = new AccUsuario;
    $objConfirm = new AccConfirmarCuenta;

    $confirmacion = $objConfirm->where("ckey", $ckey)->where("tipo", "USUARIO")/*->where("status", 0)*/
                        ->whereRaw(" created_at >= DATE_SUB(STR_TO_DATE('".now()."','%Y-%m-%d %H:%i:%s'), INTERVAL 24 HOUR) ")->first();

    $cUrl = get_const("APP_URL").'/sistema/login/';

    $cEnlace = '<p style="text-align:center"><a href="'.$cUrl.'">'.$cUrl.'</a></p>';

    $response["message"] = "";

    if(!empty($confirmacion->id))
    {
      if($confirmacion->status)
      {
        $response["message"].= "<p style='text-align: justify'>El c&oacute;digo para restablecer la contrase&ntilde;a
        que esta intentando ingresar ya ha sido utilizado previamente.</p>";
        $response["message"].= "<center>________________________________________</center><br />";
        $response["message"].= $cEnlace;
      }
      else
      {
        $usuario = $objUsuario->whereId($confirmacion->usuario_id)->first();

        if($usuario)
        {
          $cUser = $usuario->user;
          $cPass = get_random_string(8);

          $response["message"] = "<p style='text-align: justify'>Estimado(a) <b>".$usuario->obtenerNombreCompleto()."</b>, te
          proporcionamos los datos de acceso de tu cuenta,
                      hemos enviado un correo electronico con esta información por seguridad:</p><br />";

          $response["message"].= "<p>Usuario: <b>".$usuario->user."</b><br />Nueva Contraseña: <b>".$cPass."</b></p><br />";

          $response["message"].= "<p style='text-align: justify'>Le recomendamos cambiar la contraseña una vez acceda,
          en el menu <b>editar información</b>.</p>";

          $response["message"].= "<center>________________________________________</center><br />";
          $response["message"].= $cEnlace;

          $usuario->pass = sha1($cPass);
          $usuario->es_bloqueado = 0;
          $usuario->intentos_fallidos = 0;
          $usuario->save();

          $confirmacion->status = 1;
          $confirmacion->save();

          MensajeNotificacion::EnviarMensajeContraseniaRestablecida($cUser,$cUser,$cPass);
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
      $response["message"].= "<p style='text-align: justify'>El c&oacute;digo para restablecer
      la contrase&ntilde;a no es valido o ha caducado.</p>";
      $response["message"].= "<center>________________________________________</center><br />";
      $response["message"].= $cEnlace;
    }

    return view('sistema.login.basico_reset_password', $response)->render();
  }

  public function close()
  {
    $objCtrlSesion = new AccControlSesion();

    $item = $objCtrlSesion->where("usuario_id", session("usuario_id"))->where("app", session("app"))
                    ->where("medio", session("medio"))->where("dispositivo_fp", session("fpt"))->first();

    if(!is_null($item))
    {
      $item->delete();
    }

    session()->flush();
    session()->save();

    setcookie('app', '', 0, '/');
    setcookie('uid', '', 0, '/');
    setcookie('fpt', '', 0, '/');

    header("location:/sistema/login/");
    exit;
  }
}

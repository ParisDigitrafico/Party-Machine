<?php
namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

use App\Helpers\CryptoJSAES;
use App\Helpers\MensajeNotificacion;

use App\Models\AccUsuario;
use App\Models\AccModulo;
use App\Models\AccPermiso;
use App\Models\AccControlSesion;
use App\Models\AccConfirmarCuenta;

use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
  public function __construct()
  {
    $this->app = "cliente";
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

    if(!empty(session("usuario_id")))
    {
      return redirect()->to("/cliente/");
    }

    return view('cliente.pages.login.login_form', $response)->render();
  }

  public function process($usuario_id)
  {
    $response = false;

    session()->flush();
    session()->save();

    $objUsuario    = new AccUsuario();
    $objCtrlSesion = new AccControlSesion();

    $usuario = $objUsuario->filterActivos()->whereId($usuario_id)->where('es_cliente',1)->first();

    if($usuario)
    {
      $cMedio = "WEB";
      $cFpt   = request()->cookie('fpt') ?: sha1(microtime(true));

      session()->put('app', $this->app);
      session()->put('usuario_id', $usuario->id);
      session()->put('usuario_user', $usuario->user);
      session()->put('usuario_token', $usuario->token);

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
      setcookie('fpt', '', 0, '/');
    }

    return $response;
  }

  public function authentication(Request $request)
  {
    $response = array();

    $objUsuario    = new AccUsuario();
    $objCtrlSesion = new AccControlSesion;

    $cEmail    = $request->get("email");
    $cPassword = $request->get("password");

    $usuario = $objUsuario->filterActivos()->filterUserPass($cEmail, $cPassword)->where("es_cliente", 1)->first();

    if($usuario)
    {
      session()->put('app', $this->app);
      session()->put('usuario_id', $usuario->id);
      session()->put('usuario_user', $usuario->user);
      session()->put('usuario_nombre_completo', $usuario->ObtenerNombreCompleto());

      session()->save();

      if(request()->has("rememberme"))
      {
        $iMinutes = 60 * 24 * 30;

        Cookie::queue(Cookie::make('app', $this->app, $iMinutes, null, null, true));
        Cookie::queue(Cookie::make('uid', $usuario->id, $iMinutes, null, null, true));
      }

      return redirect()->to("/cliente/");
    }

    return redirect()->to("/cliente/login/?e=1&email64=".base64_encode($cEmail));
  }

  public function forgot(Request $request)
  {
    $response = array();

    if($request->isMethod('post'))
    {
      $objUsuario = new AccUsuario();
      $objConfirm = new AccConfirmarCuenta();

      $cEmail = trim(strtolower($request->get("email")));

      $cliente = $objUsuario->filterActivos()->where("es_cliente",1)->where("user",$cEmail)->first();

      if($cliente)
      {
        $confirmacion = $objConfirm->where("email", $cEmail)
                                              ->where("tipo","CLIENTE")->where("status",0)
                                              ->orderBy("created_at","desc")->first();

        if(!empty($confirmacion->created_at) && get_minutes_diference(now(), $confirmacion->created_at) < (24*60))
        {
          MensajeNotificacion::EnviarCorreoInstruccionRestablecer($cEmail, $confirmacion->ckey, "cliente");

          $response["code"]    = 200;
          $response["message"] = 'Hemos reenviado un mensaje a su correo electr&oacute;nico con las instrucciones para restablecer
          su contrase&ntilde;a, si no lo encuentra en su bandeja de entrada, favor de verificar en su carpeta de SPAM.
          <br><a href="/cliente/">Regresar</a>';
        }
        else
        {
          $Dato = array();

          $Dato["ckey"]       = md5(now());
          $Dato["email"]      = $cEmail;
          $Dato["tipo"]       = "CLIENTE";
          $Dato["created_at"] = now();

          if($objConfirm->insert($Dato))
          {
            MensajeNotificacion::EnviarCorreoInstruccionRestablecer($cEmail, $Dato["ckey"], "cliente");

            $response["code"]    = 200;
            $response["message"] = 'Hemos Enviado un mensaje a su correo electr&oacute;nico con las instrucciones para restablecer
            su contrase&ntilde;a.<br><a href="/cliente/">Regresar</a>';

            $response["reenviar"] = base64_encode($Dato["email"]);
          }
          else
          {
            $response["code"]    = 500;
            $response["message"] = "Existe un error al intentar restablecer la contrase&ntilde;a,
            favor de contactar con soporte t&eacute;cnico.";
          }
        }

        header("location:/cliente/message/?" . http_build_query($response));
        exit;
      }
      else
      {
        $response["code"]    = 500;
        $response["message"] = "No existe una cuenta relacionada al correo electr&oacute;nico ingresado, favor de verificar.";

        header("location:/cliente/login/forgot/?" . http_build_query($response));
        exit;

        return view('cliente.pages.login.login_forgot', $response)->render();
      }
    }
    else
    {
      return view('cliente.pages.login.login_forgot', $response)->render();
    }
  }

  public function reset(Request $request, $ckey)
  {
    $response = array();

    $objUsuario = new AccUsuario();
    $objConfirm = new AccConfirmarCuenta();

    $confirmacion = $objConfirm->where("ckey", $ckey)->where("tipo", "CLIENTE")
                        ->whereRaw(" created_at >= DATE_SUB(STR_TO_DATE('".now()."','%Y-%m-%d %H:%i:%s'), INTERVAL 24 HOUR) ")->first();

    $cUrl = get_const("APP_URL") . '/'. $this->app . '/login/close/';

    $cEnlace = '<p style="text-align:center"><a href="'.$cUrl.'">Iniciar Sesi&oacute;n</a></p>';

    $response["message"] = "";

    if($confirmacion)
    {
      if($confirmacion->status)
      {
        $response["message"].= "<p style='text-align: center;'>El c&oacute;digo para restablecer la contrase&ntilde;a
        que esta intentando ingresar ya ha sido utilizado previamente.</p>";
        $response["message"].= "<center>________________________________________</center><br />";
        $response["message"].= $cEnlace;
      }
      else
      {
        $cliente = $objUsuario->filterActivos()->where("es_cliente",1)->where("user",$confirmacion->email)->first();

        if($cliente)
        {
          $cUser = $confirmacion->email;
          $cPass = get_random_string(8);

          $cliente->pass = sha1($cPass);

          if($cliente->save())
          {
            $response["message"] = "<p style='text-align: center;'>Estimado(a) <b>".$cliente->ObtenerNombreCompleto()."</b>, por seguridad
              te proporcionamos una <b>contrase&ntilde;a aleatoria</b> que le permitira acceder a su cuenta,
                        tambien <b>hemos enviado un mensaje a su correo electr&oacute;nico con dicha informaci&oacute;n:</b></p>";

            $response["message"].= "<p style='text-align: center;'>Usuario: <b>".$cUser."</b><br />Nueva Contrase&ntilde;a:
            <b>".$cPass."</b></p>";

            $response["message"].= "<p style='text-align: center;'>Le recomendamos cambiar la contrase&ntilde;a una vez acceda
            en el men&uacute; <b>Seguridad y datos personales</b>.</p>";

            $response["message"].= "<center>________________________________________</center><br />";
            $response["message"].= $cEnlace;

            $confirmacion->status = 1;

            $confirmacion->save();

            MensajeNotificacion::EnviarCorreoContraseniaRestablecida($cUser,$cUser,$cPass,"cliente");
          }
          else
          {
            $response["message"].= "<p style='text-align: center;'>Sucedio un error al verificar el cliente en nuestro servidor.</p>";
            $response["message"].= "<center>________________________________________</center><br />";
            $response["message"].= $cEnlace;
          }
        }
        else
        {
          $response["message"].= "<p style='text-align: center;'>El usuario no es valido o no existe.</p>";
          $response["message"].= "<center>________________________________________</center><br />";
          $response["message"].= $cEnlace;
        }
      }
    }
    else
    {
      $response["message"].= "<p style='text-align: center;'>El c&oacute;digo para restablecer
      la contrase&ntilde;a no es valido o ha caducado.</p>";
      $response["message"].= "<center>________________________________________</center><br />";
      $response["message"].= $cEnlace;
    }

    return redirect()->to('/cliente/message/?' . http_build_query($response));
  }

  public function close(Request $request)
  {
    session()->flush();
    session()->save();

    setcookie('app', '', 0, '/');
    setcookie('uid', '', 0, '/');
    setcookie('fpt', '', 0, '/');

    header("location:/cliente/login/");
    exit;
  }

  public function register(Request $request)
  {
    $response = array();

    if(!empty(session("usuario_id")))
    {
      header("location:/cliente/");
      exit;
    }

    if(request()->isMethod('post'))
    {
      $response = array();

      // $rules = [
      //   'email' => 'required',
      //   'name' => 'required',
      // ];

      // $message = [
      //   'email' => 'El campo Correo es obligatorio.',
      //   'name' => 'El campo Nombre es obligatorio.',
      // ];

      // Validator::make($request->all(), $rules)->setAttributeNames($message)->validate();

      $objConfirm = new AccConfirmarCuenta();
      $objUsuario = new AccUsuario();

      $cEmail = trim(strtolower(request()->get("email")));

      $usuario = $objUsuario->filterActivos()->filterUserPass($cEmail, $cPassword)->where("es_cliente", 1)->first();

      if($usuario)
      {
        $response["code"]    = 500;
        $response["message"] = "Este correo electr??nico ya ha sido registrado previamente, favor de verificar.";

        header("location:/cliente/login/register/?" . http_build_query($response));
        exit;
      }
      else
      {
        $confirmacion = $objConfirm->where("tipo", "CLIENTE")->where("status", 0)->where("email",$cEmail)
                            ->whereRaw(" created_at >= DATE_SUB(STR_TO_DATE('".now()."','%Y-%m-%d %H:%i:%s'), INTERVAL 24 HOUR) ")->first();

        if($confirmacion->id)
        {
          $Mensaje = array();

          $Mensaje["to"] = $cEmail;

          $Mensaje["subject"] = "Validaci??n de correo electronico Party Machine";

          $Mensaje["header"] = "<h2><center>Instrucciones</center></h2>";

          $Mensaje["body"] = '';
          $Mensaje["body"].= '<h4>Estimado Cliente:</h4>';

          $Mensaje["body"].= '<p style="text-align: justify">Es necesario realizar la confirmaci??n de la cuenta de Correo Electr??nico
          proporcionada al registrarse dando click en el siguiente enlace:</p>';

          $cUrl = get_const("APP_URL").'/cliente/login/confirm/'.$confirmacion->ckey.'/';

          $Mensaje["body"].= '<p style="text-align: center">
          <a href="'.$cUrl.'" target="_blank">'.$cUrl.'</a></p>';

          $Mensaje["body"].= '<p style="text-align: justify">Si no es posible acceder con un click,
          copie y pege el enlace en la barra de direcciones de su navegador web.</p>';

          $bAux = MensajeNotificacion::EnviarMensaje($Mensaje);

          $response["code"]    = 200;
          $response["message"] = "Hemos enviado un mensaje a su correo electr??nico favor de revisar su bandeja
          de entrada o carpeta de spam para continuar con el proceso.";

          header("location:/cliente/message/?" . http_build_query($response));
          exit;
        }
        else
        {
          if(!empty($cEmail) && !empty(request()->get("name")) && !empty(request()->get("lastname"))
              && !empty(request()->get("phone")) && !empty(request()->get("pswd")))
          {
            if(is_email($cEmail))
            {
              $Dato = array();

              $Dato["ckey"]       = md5(now());
              $Dato["tipo"]       = "CLIENTE";
              $Dato["email"]      = $cEmail;
              $Dato["name"]       = request()->get("name");
              $Dato["lastname"]   = request()->get("lastname");
              $Dato["phone"]      = request()->get("phone");
              $Dato["pswd"]       = sha1(request()->get("pswd"));
              $Dato["created_at"] = now();

              $confirm_id = $objConfirm->insertGetId($Dato);

              $Mensaje = array();

              $Mensaje["to"] = $cEmail;

              $Mensaje["subject"] = "Validaci??n de correo electronico Party Machine";

              $Mensaje["header"] = "<h2><center>Instrucciones</center></h2>";

              $Mensaje["body"] = '';
              $Mensaje["body"].= '<h4>Estimado Cliente:</h4>';

              $Mensaje["body"].= '<p style="text-align: justify">Es necesario realizar la confirmaci??n de la cuenta de Correo Electr??nico
              proporcionada al registrarse dando click en el siguiente enlace:</p>';

              $cUrl = get_const("APP_URL").'/cliente/login/confirm/'.$Dato["ckey"].'/';

              $Mensaje["body"].= '<p style="text-align: center">
              <a href="'.$cUrl.'" target="_blank">'.$cUrl.'</a></p>';

              $Mensaje["body"].= '<p style="text-align: justify">Si no es posible acceder con un click,
              copie y pege el enlace en la barra de direcciones de su navegador web.</p>';

              $bAux = MensajeNotificacion::EnviarMensaje($Mensaje);

              if($bAux)
              {
                $objConfirm->whereId($confirm_id)->update(["es_enviado"=>1]);
              }


              $response["code"]    = 200;
              $response["message"] = "Hemos enviado un mensaje a su correo electr??nico favor de revisar su bandeja
              de entrada o carpeta de spam para continuar con el proceso.";

              header("location:/cliente/message/?" . http_build_query($response));
              exit;
            }
            else
            {
              $response["code"]    = 500;
              $response["message"] = "El campo Correo Electr??nico no es valido, favor de verificar.";

              header("location:/cliente/login/register/?" . http_build_query($response));
              exit;
            }
          }
          else
          {
            $response["code"]    = 500;
            $response["message"] = "Hay campos que no fueron llenados correctamente.";

            header("location:/cliente/login/register/?" . http_build_query($response));
            exit;
          }
        }
      }
    }
    else
    {
      return view('cliente.pages.login.register_form', $response)->render();
    }

  }

  public function confirmregister(Request $request, $ckey)
  {
    $response = array();

    $objConfirm = new AccConfirmarCuenta();
    $objUsuario = new AccUsuario();

    $confirmacion = $objConfirm->where("ckey", $ckey)->where("tipo", "CLIENTE")
                        ->whereRaw(" created_at >= DATE_SUB(STR_TO_DATE('".now()."','%Y-%m-%d %H:%i:%s'), INTERVAL 24 HOUR) ")->first();

    if(!empty($confirmacion->id))
    {
      if($confirmacion->status)
      {
        $response["status"]  = 500;
        $response["message"] = "Este c??digo ya ha sido utilizado previamente, favor de verificar.";
      }
      else
      {
        $usuario = $objUsuario->where("user", $confirmacion->email)->first();

        if($usuario)
        {
          $response["status"]  = 200;
          $response["message"] = "Este correo electr??nico ya ha sido registrado previamente, favor de utilizar nuestro
          recuperador de contrase??as.";
        }
        else
        {
          $Dato = array();

          $Dato["user"]       = $confirmacion->email;
          $Dato["nombre"]     = $confirmacion->name;
          $Dato["apellidos"]  = $confirmacion->lastname;
          $Dato["telefono"]   = $confirmacion->phone;
          $Dato["es_cliente"] = 1;

          if($objUsuario->AgregarUsuario($Dato))
          {
            $usuario = $objUsuario->latest()->first();

            $usuario->pass = $confirmacion->pswd;
            $usuario->save();

            $confirmacion->status = 1;

            $confirmacion->save();

            $response["status"]  = 200;
            $response["message"] = "Su cuenta fue activada correctamente.";
          }
          else
          {
            $response["status"]  = 500;
            $response["message"] = "No fue posible activar su cuenta, favor de contactar con soporte.";
          }
        }
      }
    }
    else
    {
      $response["status"]  = 500;
      $response["message"] = "Este c??digo no es valido o ha caducado.";
    }

    return redirect()->to('/cliente/message/?' . http_build_query($response));
  }

  public function print_message(Request $request)
  {
    $response = array();

    return view('cliente.pages.login.message_simple', $response)->render();
  }

  public function redirect(Request $request, $provider)
  {
    return Socialite::driver($provider)->redirect();
  }

  public function callback(Request $request, $provider)
  {
    try
    {
      $objUsuario = new AccUsuario();

      $user = Socialite::driver($provider)->user();

      /*$user = (object)[];

      $user->email = "macosta@digitrafico.com";*/
      #$user->email = "raul01@gmail.com";
      /*$user->name  = "demo demo demo";*/

      /*$user->getId();
      $user->getNickname();
      $user->getName();
      $user->getEmail();
      $user->getAvatar();*/

      $AuxResponse = $objUsuario->get_cliente_ecommerce_by_email($user->email);

      if($AuxResponse["code"] == 200)
      {
        $data = $AuxResponse["data"];

        $cDir     = "app/clientes";
        $cDirPath = storage_path($cDir);

        if(!is_dir($cDirPath))
          @mkdir($cDirPath, 0755, true);

        $cFilename = $cDirPath .'/'. $data["id"] .'.txt';

        if(is_file($cFilename))
        {
          $cPassword = customDecrypt(file_get_contents($cFilename, true));
        }
        else
        {
          $cPassword = get_random_string();

          $cEncryptedPswd = customEncrypt($cPassword);

          File::put($cFilename, $cEncryptedPswd);
        }

        session()->put('app', $this->app);
        session()->put('cliente_id', $data["id"]);
        session()->put('cliente_email', $data["email"]);
        session()->put('cliente_nombre', $data["name"]);
        session()->put('cliente_pswd', sha1($cPassword));

        session()->save();

        return redirect()->to('/cliente/');
      }
      elseif($AuxResponse["code"] == 404)
      {
        $Dato = array();

        $Dato["email"]    = $user->getEmail();
        $Dato["name"]     = $user->getName();
        $Dato["phone"]    = "";
        $Dato["password"] = get_random_string();

        $AuxResponse = $objUsuario->post_cliente_ecommerce($Dato);

        if($AuxResponse["code"] == 200)
        {
          $AuxResponse = $objUsuario->get_cliente_ecommerce_by_email($Dato["email"]);

          $data = $AuxResponse["data"];

          $cDir     = "app/clientes";
          $cDirPath = storage_path($cDir);

          if(!is_dir($cDirPath))
            @mkdir($cDirPath, 0755, true);

          $cFilename = $cDirPath .'/'. $data["id"] .'.txt';

          $cPassword = $Dato["password"];

          $cEncryptedPswd = customEncrypt($cPassword);

          File::put($cFilename, $cEncryptedPswd);

          session()->put('app', $this->app);
          session()->put('cliente_id', $data["id"]);
          session()->put('cliente_email', $data["email"]);
          session()->put('cliente_nombre', $data["name"]);
          session()->put('cliente_pswd', sha1($cPassword));

          session()->save();

          return redirect()->to('/cliente/');
        }
        else
        {
          $response["code"]    = 200;
          $response["message"] = "No fue posible activar su cuenta, favor de contactar con soporte.";
        }
      }
      else
      {
        abort(404);
      }
    }
    catch (\exception $ex)
    {
      dd($ex->getMessage());
    }

    /*$user = $this->createUser($getInfo, $provider);*/
    /*auth()->login($user);
    return redirect()->to('/home');*/

    /*return */
  }

}
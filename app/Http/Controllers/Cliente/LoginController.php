<?php
namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;

use App\Helpers\CryptoJSAES;
use App\Helpers\MensajeNotificacion;
use App\Helpers\ClienteAuth;

use App\Models\AccUsuario;
use App\Models\AccModulo;
use App\Models\AccPermiso;
use App\Models\AccControlSesion;
use App\Models\AccConfirmarCuenta;

use App\Services\FbazarService;

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

    if(intval(session("cliente_id")) == 0
          && intval(request()->cookie('uid')) > 0
          && request()->cookie('app') == $this->app
          && 1==1)
    {
      $this->process(intval(request()->cookie('uid')));
    }

    if(!empty(session("cliente_id")))
    {
      redirect()->to("/cliente/");
    }

    return view('cliente.pages.login.login_form', $response)->render();
  }

  public function process($idcliente)
  {
    $response = false;

    session()->flush();
    session()->save();

    $objFbazar = new FbazarService();

    $apiResponse = $objFbazar->get_cliente_ecommerce_by_id($idcliente);

    if($apiResponse["code"] == 200)
    {
      $data = $apiResponse["data"];

      $cFilename = storage_path('app/clientes/'. $data["id"] .'.txt');

      if(is_file($cFilename))
      {
        $cPassword = customDecrypt(file_get_contents($cFilename, true));

        $apiResponse = $objFbazar->get_cliente_ecommerce_by_email_password($data["email"], $cPassword);

        if($apiResponse["code"] == 200)
        {
          $data = $apiResponse["data"];

          session()->put('app', $this->app);
          session()->put('cliente_id', $data["id"]);
          session()->put('cliente_email', $data["email"]);
          session()->put('cliente_nombre', $data["name"]);
          session()->put('cliente_pswd', sha1($cPassword));

          session()->save();

          $response = true;
        }
      }
    }

    return $response;
  }

  public function authentication(Request $request)
  {
    $response = array();

    $objFbazar = new FbazarService();

    $cEmail    = request()->get("email");
    $cPassword = request()->get("password");

    $apiResponse = $objFbazar->get_cliente_ecommerce_by_email_password($cEmail, $cPassword);

    if($apiResponse["code"] == 200)
    {
      $data = $apiResponse["data"];

      $cDir     = "app/clientes";
      $cDirPath = storage_path($cDir);

      if(!is_dir($cDirPath))
        @mkdir($cDirPath, 0755, true);

      $cFilename = $cDirPath .'/'. $data["id"] .'.txt';

      $cEncryptedPswd = customEncrypt($cPassword);

      File::put($cFilename, $cEncryptedPswd);

      session()->put('app', $this->app);
      session()->put('cliente_id', $data["id"]);
      session()->put('cliente_email', $data["email"]);
      session()->put('cliente_nombre', $data["name"]);
      session()->put('cliente_pswd', sha1($cPassword));

      session()->save();

      if(request()->has("rememberme"))
      {
        $iMinutes = 60 * 24 * 30;

        Cookie::queue(Cookie::make('app', $this->app, $iMinutes, null, null, true));
        Cookie::queue(Cookie::make('uid', $data["id"], $iMinutes, null, null, true));
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
      $objFbazar  = new FbazarService();
      $objConfirm = new AccConfirmarCuenta;

      $cEmail = trim(strtolower($request->get("email")));

      $apiResponse = $objFbazar->get_cliente_ecommerce_by_email($cEmail);

      if($apiResponse["code"] == 200)
      {
        $confirmacion = $objConfirm->where("email", $cEmail)
                                              ->where("tipo","CLIENTE")->where("status",0)
                                              ->orderBy("created_at","desc")->first();

        if(!empty($confirmacion->created_at) && get_minutes_diference(now(), $confirmacion->created_at) < (24*60))
        {
          MensajeNotificacion::EnviarMensajeInstruccionRestablecer($cEmail, $confirmacion->ckey, "cliente");

          $response["code"]    = 204;
          $response["message"] = "Hemos reenviado un mensaje a su correo electrónico con las instrucciones para restablecer
          su contraseña, si no lo encuentra en su bandeja de entrada, favor de verificar en su carpeta de SPAM.";
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
            MensajeNotificacion::EnviarMensajeInstruccionRestablecer($cEmail, $Dato["ckey"], "cliente");

            $response["code"]    = 200;
            $response["message"] = "Hemos Enviado un mensaje a su correo electrónico con las instrucciones para restablecer
            su contraseña.";

            $response["reenviar"] = base64_encode($Dato["email"]);
          }
          else
          {
            $response["message"] = "Existe un error al intentar restablecer la contraseña, favor de contactar con soporte técnico.";
          }
        }

        header("location:/cliente/message/?" . http_build_query($response));
        exit;
      }
      else
      {
        $response["message"] = "No existe una cuenta relacionada al correo electrónico ingresado, favor de verificar.";

        header("location:/cliente/login/forgot/?" . http_build_query($response));
        exit;

        return view('cliente.login.login_forgot', $response)->render();
      }
    }
    else
    {
      return view('cliente.login.login_forgot', $response)->render();
    }
  }

  public function reset(Request $request, $ckey)
  {
    $response = array();

    $objFbazar  = new FbazarService();
    $objConfirm = new AccConfirmarCuenta;

    $confirmacion = $objConfirm->where("ckey", $ckey)->where("tipo", "CLIENTE")
                        ->whereRaw(" created_at >= DATE_SUB(STR_TO_DATE('".now()."','%Y-%m-%d %H:%i:%s'), INTERVAL 24 HOUR) ")->first();


    $cUrl = get_const("APP_URL").'/cliente/login/';

    $cEnlace = '<p style="text-align:center"><a href="'.$cUrl.'">'.$cUrl.'</a></p>';

    $response["message"] = "";

    if(!empty($confirmacion->id))
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
        $apiResponse = $objFbazar->get_cliente_ecommerce_by_email($confirmacion->email);

        if($apiResponse["code"] == 200)
        {
          $Dato = $apiResponse["data"];

          $cUser = $confirmacion->email;
          $cPass = get_random_string(8);

          $Dato["password"] = $cPass;

          $apiResponse = $objFbazar->put_cliente_ecommerce($Dato);

          if($apiResponse["code"] == 200)
          {
            $response["message"] = "<p style='text-align: center;'>Estimado(a) <b>".$Dato["name"]."</b>, por seguridad
              te proporcionamos una <b>contraseña aleatoria</b> que le permitira acceder a su cuenta,
                        tambien <b>hemos enviado un mensaje a su correo electrónico con dicha información:</b></p><br />";

            $response["message"].= "<p style='text-align: center;'>Usuario: <b>".$cUser."</b><br />Nueva Contraseña: <b>".$cPass."</b></p><br />";

            $response["message"].= "<p style='text-align: center;'>Le recomendamos cambiar la contraseña una vez acceda
            en el menú <b>Seguridad y datos personales</b>.</p>";

            $response["message"].= "<center>________________________________________</center><br />";
            $response["message"].= $cEnlace;

            $objConfirm->where("id",$confirmacion->id)->update(["status"=>1]);

            MensajeNotificacion::EnviarMensajeContraseniaRestablecida($cUser,$cUser,$cPass,"cliente");
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

    return view('cliente.login.login_reset', $response)->render();
  }

  public function close(Request $request)
  {
    session()->flush();
    session()->save();

    setcookie('app', '', 0, '/');
    setcookie('uid', '', 0, '/');

    header("location:/cliente/login/");
    exit;
  }

  public function register(Request $request)
  {
    $response = array();

    if(!empty(session("cliente_id")))
    {
      header("location:/cliente/");
      exit;
    }

    if(request()->isMethod('post'))
    {
      $response = array();

      /*$rules = [
        'email' => 'required',
        'name' => 'required',
      ];

      $message = [
        'email' => 'El campo Correo es obligatorio.',
        'name' => 'El campo Nombre es obligatorio.',
      ];

      Validator::make($request->all(), $rules)->setAttributeNames($message)->validate();*/

      $objConfirm = new AccConfirmarCuenta;
      $objFbazar  = new FbazarService();

      $cEmail = trim(strtolower(request()->get("email")));

      $apiResponse = $objFbazar->get_cliente_ecommerce_by_email($cEmail);

      if($apiResponse["code"] == 200)
      {
        $response["code"]    = 500;
        $response["message"] = "Este correo electrónico ya ha sido registrado previamente, favor de verificar.";

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

          $Mensaje["subject"] = "Validación de correo electronico Farmacias Bazar";

          $Mensaje["header"] = "<h2><center>Instrucciones</center></h2>";

          $Mensaje["body"] = '';
          $Mensaje["body"].= '<h4>Estimado Cliente:</h4>';

          $Mensaje["body"].= '<p style="text-align: justify">Es necesario realizar la confirmación de la cuenta de Correo Electrónico
          proporcionada al registrarse dando click en el siguiente enlace:</p>';

          $cUrl = get_const("APP_URL").'/cliente/login/confirm/'.$confirmacion->ckey.'/';

          $Mensaje["body"].= '<p style="text-align: center">
          <a href="'.$cUrl.'" target="_blank">'.$cUrl.'</a></p>';

          $Mensaje["body"].= '<p style="text-align: justify">Si no es posible acceder con un click,
          copie y pege el enlace en la barra de direcciones de su navegador web.</p>';

          $bStatus = MensajeNotificacion::EnviarMensaje($Mensaje);

          $response["code"]    = 200;
          $response["message"] = "Hemos enviado un mensaje a su correo electrónico favor de revisar su bandeja
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
              $Dato["pswd"]       = request()->get("pswd");
              $Dato["created_at"] = now();

              $objConfirm->insert($Dato);

              $Mensaje = array();

              $Mensaje["to"] = $cEmail;

              $Mensaje["subject"] = "Validación de correo electronico Farmacias Bazar";

              $Mensaje["header"] = "<h2><center>Instrucciones</center></h2>";

              $Mensaje["body"] = '';
              $Mensaje["body"].= '<h4>Estimado Cliente:</h4>';

              $Mensaje["body"].= '<p style="text-align: justify">Es necesario realizar la confirmación de la cuenta de Correo Electrónico
              proporcionada al registrarse dando click en el siguiente enlace:</p>';

              $cUrl = get_const("APP_URL").'/cliente/login/confirm/'.$Dato["ckey"].'/';

              $Mensaje["body"].= '<p style="text-align: center">
              <a href="'.$cUrl.'" target="_blank">'.$cUrl.'</a></p>';

              $Mensaje["body"].= '<p style="text-align: justify">Si no es posible acceder con un click,
              copie y pege el enlace en la barra de direcciones de su navegador web.</p>';

              $bStatus = MensajeNotificacion::EnviarMensaje($Mensaje);

              $response["code"]    = 200;
              $response["message"] = "Hemos enviado un mensaje a su correo electrónico favor de revisar su bandeja
              de entrada o carpeta de spam para continuar con el proceso.";

              header("location:/cliente/message/?" . http_build_query($response));
              exit;
            }
            else
            {
              $response["code"]    = 500;
              $response["message"] = "El campo Correo Electrónico no es valido, favor de verificar.";

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
      return view('cliente.login.register_form', $response)->render();
    }

  }

  public function confirmregister($ckey)
  {
    $response = array();

    $objConfirm = new AccConfirmarCuenta;
    $objFbazar  = new FbazarService();

    $confirmacion = $objConfirm->where("ckey", $ckey)->where("tipo", "CLIENTE")
                        ->whereRaw(" created_at >= DATE_SUB(STR_TO_DATE('".now()."','%Y-%m-%d %H:%i:%s'), INTERVAL 24 HOUR) ")->first();

    if(!empty($confirmacion->id))
    {
      if($confirmacion->status)
      {
        $response["code"]    = 200;
        $response["message"] = "Este código ya ha sido utilizado previamente, favor de verificar.";
      }
      else
      {
        $apiResponse = $objFbazar->get_cliente_ecommerce_by_email($confirmacion->email);

        if($apiResponse["code"] == 200)
        {
          $response["code"]    = 200;
          $response["message"] = "Este correo electrónico ya ha sido registrado previamente, favor de verificar.";
        }
        else
        {
          $Dato = array();

          $Dato["email"] = $confirmacion->email;

          $cAux = $confirmacion->name . " " . $confirmacion->lastname;
          $cAux = trim($cAux);

          $Dato["name"]     = ucwords($cAux);
          $Dato["phone"]    = $confirmacion->phone;
          $Dato["password"] = $confirmacion->pswd;

          $apiResponse = $objFbazar->post_cliente_ecommerce($Dato);

          if($apiResponse["code"] == 200)
          {
            $confirmacion->pswd   = "";
            $confirmacion->status = 1;

            $confirmacion->save();

            header("location:/cliente/login/?a=t");
            exit;
          }
          else
          {
            $response["code"]    = 200;
            $response["message"] = "No fue posible activar su cuenta, favor de contactar con soporte.";
          }
        }
      }
    }
    else
    {
      $response["code"]    = 200;
      $response["message"] = "Este código no es valido o ha caducado.";
    }

    header("location:/cliente/message/?" . http_build_query($response));
    exit;
  }

  public function print_message(Request $request)
  {
    $response = array();

    return view('cliente.login.message_simple', $response)->render();
  }

  public function redirect(Request $request, $provider)
  {
    return Socialite::driver($provider)->redirect();
  }

  public function callback(Request $request, $provider)
  {
    try
    {
      $objFbazar = new FbazarService();

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

      $apiResponse = $objFbazar->get_cliente_ecommerce_by_email($user->email);

      if($apiResponse["code"] == 200)
      {
        $data = $apiResponse["data"];

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
      elseif($apiResponse["code"] == 404)
      {
        $Dato = array();

        $Dato["email"]    = $user->getEmail();
        $Dato["name"]     = $user->getName();
        $Dato["phone"]    = "";
        $Dato["password"] = get_random_string();

        $apiResponse = $objFbazar->post_cliente_ecommerce($Dato);

        if($apiResponse["code"] == 200)
        {
          $apiResponse = $objFbazar->get_cliente_ecommerce_by_email($Dato["email"]);

          $data = $apiResponse["data"];

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

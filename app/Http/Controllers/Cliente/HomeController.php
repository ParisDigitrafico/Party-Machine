<?php
namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use App\Helpers\HtmlHelper;
use App\Helpers\Normalizador;

use App\Models\AccUsuario;

use App\Services\FbazarService;

class HomeController extends MController
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index(Request $request)
  {
    $response = array();

    return view('cliente.pages.home.frontpage', $response)->render();
  }

  public function cuenta_form(Request $request)
  {
    $response = array();

    $objFbazar = new FbazarService();

    $apiResponse = $objFbazar->get_cliente_ecommerce_by_email(session("email_cliente"));

    if($apiResponse["code"] == 200)
    {
      $Dato = $apiResponse["data"];

      if($request->isMethod('post'))
      {
        $cFilename = storage_path('app/clientes/'.session("idcliente").'.txt');

        if(is_file($cFilename))
        {
          $cPassword = customDecrypt(file_get_contents($cFilename, true));

          $Dato["password"] = $cPassword;

          if(!empty($request->get("oldpswd")) && !empty($request->get("newpswd")))
          {
            if($cPassword != $request->get("oldpswd"))
            {
              $response["message"] = "La Contraseña antigua no coincide con la registrada en nuestro sistema.";
              header("location:/cliente/cuenta/?" . http_build_query($response));
              exit;
            }
            else
            {
              $cPassword = $request->get("newpswd");
              $Dato["password"] = $cPassword;
            }
          }
        }

        $cAux = $request->get("name");

        $Dato["name"]  = ucwords($cAux);
        $Dato["phone"] = $request->get("phone");
        $Dato["rfc"]   = strtoupper($request->get("rfc"));

        $apiResponse = $objFbazar->put_cliente_ecommerce($Dato);

        if($apiResponse["code"] == 200)
        {
          session()->put('nombre_cliente', $Dato["name"]);
          session()->put('pswd_cliente', sha1($cPassword));

          session()->save();

          $response["code"]    = 200;
          $response["message"] = "Sus datos han sido actualizados correctamente.";
        }
        else
        {
          $response["message"] = "Ha sucedido un error al tratar de actualizar sus datos,
          por favor vuelva a intentearlo.";
        }

        header("location:/cliente/cuenta/?" . http_build_query($response));
        exit;
      }

      $response["data"] = $Dato;

      return view('cliente.cuenta.cuenta_form', $response)->render();
    }

    abort(403);
  }

  public function direcciones_list()
  {
    $response = array();

    $objFbazar = new FbazarService();

    $apiResponse = $objFbazar->get_cliente_ecommerce_by_email(session("email_cliente"));

    if($apiResponse["code"] == 200)
    {
      $response["data"] = $apiResponse["data"];
    }

    return view('cliente.cuenta.direcciones_list', $response)->render();
  }

  public function direcciones_form()
  {
    $response = array();

    $objFbazar = new FbazarService();

    $data = array();

    $response["data"] = $data;

    return view('cliente.cuenta.direcciones_form', $response)->render();
  }

  public function direcciones_save()
  {
    $response = array();

    $objFbazar = new FbazarService();

    $apiResponse = $objFbazar->get_cliente_ecommerce_by_email(session("email_cliente"));

    $bError = false;

    if($apiResponse["code"] == 200)
    {
      $Dato = $apiResponse["data"];

      $cFilename = storage_path('app/clientes/'.session("idcliente").'.txt');

      if(is_file($cFilename))
      {
        $cPassword = customDecrypt(file_get_contents($cFilename, true));

        if(sha1($cPassword) != session("pswd_cliente"))
        {
          header("location:/cliente/login/close/");
          exit;
        }

        $Dato["password"] = $cPassword;
      }

      $Direccion = request()->get("Direccion");

      if(!empty($Direccion))
      {
        $estado    = LocEstado::find($Direccion["codigoEstado"]);
        $municipio = LocMunicipio::find($Direccion["codigoMunicipio"]);

        $Direccion["clienteId"]       = intval(session("idcliente"));
        $Direccion["calle"]           = strval($Direccion["calle"]);
        $Direccion["numeroExterior"]  = strval($Direccion["numeroExterior"]);
        $Direccion["numeroInterior"]  = strval($Direccion["numeroInterior"]);
        $Direccion["entre"]           = strval($Direccion["entre"]);
        $Direccion["referencia"]      = strval($Direccion["referencia"]);
        $Direccion["codigoEstado"]    = strval($estado->clave);
        $Direccion["codigoMunicipio"] = strval($municipio->clave);
        $Direccion["codigoColonia"]   = intval($Direccion["codigoColonia"]);
        $Direccion["codigoPostal"]    = intval($Direccion["codigoPostal"]);
        $Direccion["observaciones"]   = strval($Direccion["observaciones"]);
        $Direccion["latitud"]         = floatval($Direccion["latitud"]);
        $Direccion["longitud"]        = floatval($Direccion["longitud"]);

        /*$newClienteSad = array();

        $newClienteSad["telefono"]        = strval($Dato["phone"]);
        $newClienteSad["nombre"]          = strval($Dato["name"]);
        $newClienteSad["rfc"]             = strval($Dato["rfc"]);
        $newClienteSad["calle"]           = $Direccion["calle"];
        $newClienteSad["numeroExterior"]  = $Direccion["numeroExterior"];
        $newClienteSad["numeroInterior"]  = $Direccion["numeroInterior"];
        $newClienteSad["entre"]           = $Direccion["entre"];
        $newClienteSad["referencia"]      = $Direccion["referencia"];
        $newClienteSad["codigoEstado"]    = $Direccion["codigoEstado"];
        $newClienteSad["codigoMunicipio"] = $Direccion["codigoMunicipio"];
        $newClienteSad["codigoColonia"]   = $Direccion["codigoColonia"];
        $newClienteSad["codigoPostal"]    = $Direccion["codigoPostal"];
        $newClienteSad["latitud"]         = $Direccion["latitud"];
        $newClienteSad["longitud"]        = $Direccion["longitud"];

        $apiResponse = $objFbazar->post_clientes_sad($newClienteSad);

        if($apiResponse["code"] == 200)
        {
          $ClienteSad = $apiResponse["data"];

          $Direccion["observaciones"] = strval($ClienteSad["id"]);

          $Dato["direccion"][] = $Direccion;
        }
        else
        {
          $bError = true;
          $response["message"] = "Hubo un error al guardar los datos en nuestra plataforma,
          favor de comunicarse con soporte técnico.";
        }*/

        $Dato["direccion"][] = $Direccion;
      }

      if($bError === false)
      {
        $apiResponse = $objFbazar->put_cliente_ecommerce($Dato);

        if($apiResponse["code"] == 200)
        {
          $response["success"] = true;
          $response["message"] = trans("general.guardado_correcto");
        }
        else
        {
          $response["message"] = trans("general.error_inesperado");
        }
      }
    }
    else
    {
      $response["message"] = trans("general.error_inesperado");
    }

    return response()->json($response);
  }

  public function direcciones_delete($id)
  {
    $response = array();

    $objFbazar = new FbazarService();

    $apiResponse = $objFbazar->get_cliente_ecommerce_by_email(session("email_cliente"));

    if($apiResponse["code"] == 200)
    {
      $Dato = $apiResponse["data"];

      $cFilename = storage_path('app/clientes/'.session("idcliente").'.txt');

      if(is_file($cFilename))
      {
        $cPassword = customDecrypt(file_get_contents($cFilename, true));

        if(sha1($cPassword) != session("pswd_cliente"))
        {
          header("location:/cliente/login/close/");
          exit;
        }

        $Dato["password"] = $cPassword;
      }

      $direcciones = $Dato["direccion"];

      $arrAux = array();

      foreach($direcciones as $direccion)
      {
        if($direccion["id"] != $id)
        {
          $arrAux[] = $direccion;
        }
      }

      $Dato["direccion"] = $arrAux;

      $apiResponse = $objFbazar->put_cliente_ecommerce($Dato);

      if($apiResponse["code"] == 200)
      {
        $response["success"] = true;
        $response["message"] = trans("general.eliminado_correcto");
      }
      else
      {
        $response["message"] = trans("general.error_inesperado");
      }
    }
    else
    {
      $response["message"] = trans("general.error_inesperado");
    }

    return response()->json($response);
  }

}
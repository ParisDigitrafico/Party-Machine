<?php
namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use App\Helpers\HtmlHelper;
use App\Helpers\Normalizador;

use App\Models\AccUsuario;


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

    $objUsuario = new AccUsuario();

    $usuario = $objUsuario->whereId(session('usuario_id'))->first();

    if($usuario)
    {
      $Dato = $usuario["data"];

      if($request->isMethod('post'))
      {
        $cFilename = storage_path('app/clientes/'.session("cliente_id").'.txt');

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

      return view('cliente.pages.cuenta.cuenta_form', $response)->render();
    }

    abort(403);
  }

}
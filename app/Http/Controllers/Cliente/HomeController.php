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

    $query = $objUsuario->whereId(session('usuario_id'));

    $usuario = $query->first();

    if($usuario)
    {
      if($request->isMethod('post'))
      {
        $Dato = array();

        if(!empty($request->get("oldpswd")) && !empty($request->get("newpswd")))
        {
          $cAux = trim($request->get("oldpswd"));

          if($usuario->pass != sha1($cAux))
          {
            $response["message"] = "La Contraseña antigua no coincide con la registrada en nuestro sistema.";

            return redirect()->to('/cliente/cuenta/?' . http_build_query($response));
          }
          else
          {
            $cAux = trim($request->get("newpswd"));
            $Dato["pass"] = sha1($cAux);
          }
        }

        $Dato["nombre"]    = $request->get("name");
        $Dato["apellidos"] = $request->get("lastname");
        $Dato["telefono"]  = $request->get("phone");

        $bAux = $query->update($Dato);

        if($bAux)
        {
          $usuario = $query->first();

          session()->put('usuario_nombre', $usuario->ObtenerNombreCompleto());

          session()->save();

          $response["status"]  = 200;
          $response["message"] = "Sus datos han sido actualizados correctamente.";
        }
        else
        {
          $response["message"] = "Ha sucedido un error al tratar de actualizar sus datos,
          por favor vuelva a intentearlo.";
        }

        return redirect()->to('/cliente/cuenta/?' . http_build_query($response));
      }

      $response["data"] = $usuario;

      return view('cliente.pages.cuenta.cuenta_form', $response)->render();
    }

    abort(403);
  }

}
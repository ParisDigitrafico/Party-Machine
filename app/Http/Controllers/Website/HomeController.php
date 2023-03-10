<?php
namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use App\Helpers\HtmlHelper;
use App\Helpers\Normalizador;

use App\Models\AccUsuario;
use App\Models\WebPagina;
use App\Models\SppProducto;
use App\Models\GenCategoria;

class HomeController extends MController
{
	public function __construct()
	{
	  parent::__construct();
	}

  public function index()
  {
    $response = array();

    $pagina = WebPagina::where("clave","INICIO")->first();

    $response["page_title"] = $pagina->nombre;

    $response["pagina"] = $pagina;

    return view('website.custom.frontpage', $response)->render();
  }

  public function maquina_invitaciones(Request $request)
  {
    $response = array();

    $pagina = WebPagina::where("clave","MAQUINA DE INVITACIONES")->first();

    $response["page_title"] = $pagina->nombre;

    $response["pagina"] = $pagina;

    $response["categorias"] = GenCategoria::get();

    $response["data"] = SppProducto::where("tipo","plantilla")->filterStatus(1)->get();

    return view('website.custom.maquina_invitaciones', $response)->render();
  }

  public function invitaciones_web(Request $request)
  {
    $response = array();

    $pagina = WebPagina::where("clave","INVITACIONES WEB")->first();

    $response["page_title"] = $pagina->nombre;

    $response["pagina"] = $pagina;

    return view('website.custom.invitaciones_web', $response)->render();
  }


  public function nosotros(Request $request)
  {
    $response = array();

        $pagina = WebPagina::where("clave","NOSOTROS")->first();

    $response["page_title"] = $pagina->nombre;

    $response["pagina"] = $pagina;

    return view('website.custom.nosotros', $response)->render();
  }

  public function contacto(Request $request)
  {
    $response = array();

        $pagina = WebPagina::where("clave","CONTACTO")->first();

    $response["page_title"] = $pagina->nombre;

    $response["pagina"] = $pagina;

    return view('website.custom.contacto', $response)->render();
  }

}



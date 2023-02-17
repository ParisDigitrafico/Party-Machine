<?php
namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use App\Helpers\HtmlHelper;
use App\Helpers\Normalizador;

use App\Models\AccUsuario;

use App\Models\WebPagina;
use App\Models\SppPlantilla;

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

    return view('website.pages.frontpage', $response)->render();
  }

  public function maquina_invitaciones(Request $request)
  {
    $response = array();

        $pagina = WebPagina::where("clave","MAQUINA DE INVITACIONES")->first();

    $response["page_title"] = $pagina->nombre;

    $response["pagina"] = $pagina;

    return view('website.pages.maquina_invitaciones', $response)->render();
  }

  public function contacto(Request $request)
  {
    $response = array();

        $pagina = WebPagina::where("clave","CONTACTO")->first();

    $response["page_title"] = $pagina->nombre;

    $response["pagina"] = $pagina;

    return view('website.pages.contacto', $response)->render();
  }

  public function nosotros(Request $request)
  {
    $response = array();

        $pagina = WebPagina::where("clave","NOSOTROS")->first();

    $response["page_title"] = $pagina->nombre;

    $response["pagina"] = $pagina;

    return view('website.pages.nosotros', $response)->render();
  }

}



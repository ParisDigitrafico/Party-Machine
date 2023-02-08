<?php
namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use App\Helpers\HtmlHelper;
use App\Helpers\Normalizador;

use App\Models\AccUsuario;

use App\Models\WebPagina;

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

   public function nosotros(Request $request)
  {
    $response = array();

    $pagina = WebPagina::where("clave","NOSOTROS")->first();

    $response["page_title"] = $pagina->nombre;
    
    $response["pagina"] = $pagina;

    return view('website.pages.nosotros', $response)->render();
  }

  public function destinos(Request $request)
  {
    $response = array();

    $pagina = WebPagina::where("clave","DESTINOS")->first();
    
    $response["page_title"] = $pagina->nombre;
    
    $response["pagina"] = $pagina;

    return view('website.pages.destinos', $response)->render();
  }

  public function tiposvisas(Request $request)
  {
    $response = array();

    $pagina = WebPagina::where("clave","tiposvisas")->first();

    $response["page_title"] = $pagina->nombre;
    
    $response["pagina"] = $pagina;

    return view('website.pages.tiposvisas', $response)->render();
  }

  public function servicios(Request $request)
  {
    $response = array();

    $pagina = WebPagina::where("clave","SERVICIOS")->first();

    $response["page_title"] = $pagina->nombre;
    
    $response["pagina"] = $pagina;

    return view('website.pages.servicios', $response)->render();
  }

  public function primeravez(Request $request)
  {

    $response = array();

    $pagina = WebPagina::where("clave","PRIMERA_VEZ")->first();

    $response["page_title"] = $pagina->nombre;
    
    $response["pagina"] = $pagina;

    return view('website.pages.primeravez', $response)->render();

  }
  
  public function renovacion(Request $request)
  {
    $response = array();

    $pagina = WebPagina::where("clave","RENOVACION")->first();

    $response["page_title"] = $pagina->nombre;
    
    $response["pagina"] = $pagina;

    return view('website.pages.renovacion', $response)->render();
  }
  
  public function perdon(Request $request)
  {
    $response = array();

    $pagina = WebPagina::where("clave","PERDON")->first();

    $response["page_title"] = $pagina->nombre;
    
    $response["pagina"] = $pagina;

    return view('website.pages.perdon', $response)->render();
  }
  
public function emergencia(Request $request)
  {
    $response = array();

    $pagina = WebPagina::where("clave","EMERGENCIA")->first();

    $response["page_title"] = $pagina->nombre;
    
    $response["pagina"] = $pagina;

    return view('website.pages.emergencia', $response)->render();
  }
  
  public function construccion(Request $request)
  {
    $response = array();

    $pagina = WebPagina::where("clave","CONSTRUCCION")->first();

    $response["page_title"] = $pagina->nombre;
    
    $response["pagina"] = $pagina;

    return view('website.pages.construccion', $response)->render();
  }

  public function contacto(Request $request)
  {
    $response = array();

    $pagina = WebPagina::where("clave","CONTACTO")->first();

    $response["page_title"] = $pagina->nombre;
    
    $response["pagina"] = $pagina;

    return view('website.pages.contacto', $response)->render();
  }

  public function faqs(Request $request)
  {
    $response = array();

    $pagina = WebPagina::where("clave","FAQS")->first();

    $response["page_title"] = $pagina->nombre;
    
    $response["pagina"] = $pagina;

    return view('website.pages.faqs', $response)->render();
  }

  public function avisoprivacidad(Request $request)
  {
    $response = array();

    $pagina = WebPagina::where("clave","AVISO_PRIVACIDAD")->first();

    $response["page_title"] = $pagina->nombre;
    
    $response["pagina"] = $pagina;

    return view('website.pages.aviso-privacidad', $response)->render();
  }
  public function thankyou(Request $request)
  {
    $response = array();

    $pagina = WebPagina::where("clave","THANK-YOU")->first();

    $response["page_title"] = $pagina->nombre;
    
    $response["pagina"] = $pagina;

    return view('website.pages.thank-you', $response)->render();
  }

  public function plantillas(Request $request)
  {
    $response = array();

    $pagina = WebPagina::where("clave","PLANTILLAS")->first();

    $response["page_title"] = $pagina->nombre;
    
    $response["pagina"] = $pagina;

    return view('website.pages.plantillas', $response)->render();
  }

}



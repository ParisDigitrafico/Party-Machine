<?php
namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Helpers\SitioWeb;

use App\Models\AccUsuario;
use App\Models\WebPagina;
use App\Models\SppProducto;
use App\Models\GenCategoria;

class ProductosController extends MController
{
  public function __construct()
  {
    parent::__construct();
  }

  public function list(Request $request)
  {
    $response = array();

    $pagina = WebPagina::where("clave","PLANTILLAS")->first();

    $response["categorias"] = GenCategoria::get();

    $response["page_title"] = $pagina->nombre;

    $response["pagina"] = $pagina;

    $response["data"] = SppProducto::filterStatus(1)->get();

    return view('website.pages.productos', $response)->render();
  }

}
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

    $pagina = WebPagina::where("clave","PRODUCTOS")->first();

    $response["page_title"] = $pagina->nombre;

    $response["pagina"] = $pagina;

    $response["categorias"] = GenCategoria::get();

    $response["data"] = SppProducto::filterStatus(1)->get();

    return view('website.pages.productos', $response)->render();
  }

}
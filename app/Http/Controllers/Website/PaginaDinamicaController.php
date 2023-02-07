<?php
namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use App\Models\WebPagina;

class PaginaDinamicaController extends MController
{
  public function index($id, $slug="")
  {
    $response = array();

    $objPagina = new WebPagina();

    $data = $objPagina->with("banners")->filterVisibles()->whereId($id)->first();

    if($data)
    {
      $response["pagina"] = $data;

      return view('website.pages.dinamica', $response)->render();
    }

    abort(404);
  }

}
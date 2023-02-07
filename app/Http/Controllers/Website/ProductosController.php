<?php
namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Helpers\SitioWeb;

use App\Models\AccUsuario;

use App\Models\FbzProducto;
use App\Models\FbzProductoCategoria;

use App\Services\FbazarService;

class ProductosController extends MController
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index(Request $request)
  {
    $response = array();

    $objProducto = new FbzProducto;
    $objProdCat  = new FbzProductoCategoria;

    /*$query = $objProducto->with()->where('id', '!=', '0');*/
    $query = $objProducto->where('id', '!=', '0');

    if(!empty($cAux = $request->get('keyword')))
    {
      $query = $query->where('nombreProducto', 'like', '%'.$cAux.'%');
      $query = $query->orWhere('codigoRelacionado', 'like', '%'.$cAux.'%');
    }

    $per_page = intval(request()->get("per_page"));
    $per_page = $per_page ?: 12;

    $results = $query->paginate($per_page);

    $arrCat = $objProdCat->obtenerArrCategoriaSubcategoria();

    $cCatHtm = SitioWeb::CrearMenuCategoriaProducto($arrCat);

    $iTotal = $results->total();

    /*exit(var_dump($results->total()));  */

    /*exit(var_dump($results->lastItem())); */

    $response["page"]        = $results->currentPage();
    $response["per_page"]    = $results->perPage();
    $response["total"]       = $results->total();
    $response["total_pages"] = ceil($response["total"] / $response["per_page"]);
    $response["first_item"]  = $results->firstItem();
    $response["last_item"]   = $results->lastItem();
    $response["data"]        = $results->items();

    /*if(!empty($results->items()))
    {
      $arrAux = array();

      $arrItem = objectToArray($results->items());

      foreach($arrItem as $item)
      {
        $arrAux[] = $item;
      }

      $response["data"] = $arrAux;
    }*/

    /*exit(PrettyPrintJson(json_encode($response)));*/


    /*$response["data"] = objectToArray();*/
    /*$response["data"] = $results;*/

    $response["html_cat"] = $cCatHtm;

    return view('website.productos.producto_list', $response)->render();
  }

  public function show(Request $request, $id)
  {
    $response = array();

    $response["record"] = FbzProducto::find($id);

    return view('website.productos.producto_detail', $response)->render();
  }

  public function tmp_index(Request $request)
  {
    $response = array();

    $objProducto = new FbzProducto();
    $objProdCat  = new FbzProductoCategoria();
    $objFbzServ  = new FbazarService();

    $arrCat = $objProdCat->obtenerArrCategoriaSubcategoria();

    $cCatHtm = SitioWeb::CrearMenuCategoriaProducto($arrCat);

    $arrReq = array();

    $arrReq["Pagina"]          = intval($request->get('page') ?? 1);
    $arrReq["RecordPorPagina"] = intval($request->get('per_page') ?? 12);
    $arrReq["searchWords"]     = $request->get('searchWords');

    $arrRes = $objFbzServ->obtener_productos($arrReq);

    $response["page"]     = $arrReq["Pagina"];
    $response["per_page"] = $arrReq["RecordPorPagina"];
    $response["total"]    = (int)$arrRes["headers"]["cantidadTotalRegistros"][0];
    $response["data"]     = $arrRes["data"];

    $response["html_cat"] = $cCatHtm;

    return view('website.productos.producto_list', $response)->render();
  }

}
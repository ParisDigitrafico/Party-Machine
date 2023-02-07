<?php
namespace App\Http\Controllers\Sistema;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Helpers\HtmlHelper;

use App\Models\AccUsuario;
use App\Models\AccModulo;
use App\Models\AccPerfil;

class HomeController extends MController
{
	public function __construct()
	{
	  parent::__construct();
	}

  public function index(Request $request)
  {
    $response = array();

    $objUsuario = new AccUsuario();

    # clientes
    {
      $arrAux = array();

      $records = $objUsuario->where("es_cliente",1)->filterStatus(1)->get();
      $arrAux["activos"] = count($records);

      $records = $objUsuario->where("es_cliente",1)->filterStatus(0)->get();
      $arrAux["inactivos"] = count($records);

      $records = $objUsuario->where("es_cliente",1)->onlyTrashed()->get();
      $arrAux["eliminados"] = count($records);

      $response["clientes"] = $arrAux;
    }

    return view('sistema.home.dashboard', $response)->render();
  }

  public function getinfo(Request $request)
  {
    $response = array();

    if(!empty(session("usuario_id")))
    {
      $objMoneda = new Moneda();
      $objNoti   = new Notificacion();

      $objMoneda->ConsultarRegistro(2);

      $cSql = "
      SELECT COUNT(1) as id
      FROM notificacion
      WHERE 1=1
      AND usuario_id='".session("usuario_id")."'
      AND es_leido=0
      AND LENGTH(CHAR(deleted_at)) = 0
      ";

      $objNoti->EjecutarQuery($cSql);

      $response["success"] = true;

      $data = array();

      $data["cambio_usd"]   = $objMoneda->cambio;
      $data["not_sin_leer"] = $objNoti->id;

      $response["data"] = $data;
    }

    return response()->json($response);
  }

  public function getJsonTable(Request $request, $table)
  {
    $response = array();

    $cSql = "
    SELECT
    *,
    1
    FROM
    ".$table."
    WHERE 1=1
    ";

    $cAnd = $request->get("and");

    if(!empty($cAnd))
    {
      $arr = json_decode($cAnd, true);

      $cAux = '';

      foreach($arr as $key => $value)
      {
        $cAux.= " AND ". $key . " ". $value[0]["operator"] . " '".$value[0]["value"]."' ";
      }

      if(!empty($cAux))
      {
        $cSql.= $cAux;
      }
    }

    $cOr = $request->get("or");

    if(!empty($cOr))
    {
      $arr = json_decode($cOr, true);

      $cAux = '';

      foreach($arr as $key => $value)
      {
        $cAux.= " OR ". $key . " ". $value[0]["operator"] . " '".$value[0]["value"]."' ";
      }

      if(!empty($cAux))
      {
        $cSql.= $cAux;
      }
    }

    $response["data"] = objectToArray(DB::Select($cSql));

    return json_encode($response);
  }

}
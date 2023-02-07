<?php
namespace App\Http\Controllers\Sistema;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Helpers\HtmlHelper;

use App\Models\AccUsuario;
use App\Models\AccModulo;
use App\Models\AccLog;

class LogsController extends MController
{
	public function __construct()
	{
	  parent::__construct();

    $this->base_object = new AccLog;

    $this->permision_key    = "LOG";
    $this->permision_create = "C_" . $this->permision_key;
    $this->permision_read   = "R_" . $this->permision_key;
    $this->permision_update = "U_" . $this->permision_key;
    $this->permision_delete = "D_" . $this->permision_key;

    $this->view_key    = "sistema.logs.log";
    $this->view_list   = $this->view_key . "_list";
    $this->view_search = $this->view_key . "_search";
    $this->view_form   = $this->view_key . "_form";
    $this->view_detail = $this->view_key . "_detail";
	}

  public function index(Request $request)
  {
    if(find_string_in_array("_LOG",session("permisos")))
    {
      $response = array();

      $response["title"] = AccModulo::ObtenerTitulo("M_REGISTRO_ACTIVIDAD");

      return view('sistema.logs.log_list', $response)->render();
    }

    abort(403);
  }

  public function Paginate(Request $request)
  {
    $response = array();

    $response["success"]         = true;
    $response["draw"]            = date("YmdHis");
    $response["recordsTotal"]    = 0;
    $response["recordsFiltered"] = 0;
    $response["data"]            = array();

    $b = $request->get("b");

    $cSql = "
    SELECT
    t.*,
    1
    FROM
    acc_log t
    WHERE 1=1
    ";

    # and
    $cSql.= " AND length(char(t.deleted_at)) = 0 ";

    # order
    $cOrdenSql.= ", t.created_at DESC ";

    $iTotal = count(DB::Select("select 1 from " . after("FROM", $cSql)));

    if($iTotal > 0)
    {
      $response["recordsTotal"]    = $iTotal;
      $response["recordsFiltered"] = $response["recordsTotal"];

      $cSql.= "ORDER BY '' " . $cOrdenSql;

      if($request->get("length") > 0)
      {
        $cSql.="
        LIMIT ".intval($request->get("length"))." OFFSET ".intval($request->get("start"))."
        ";
      }

      $arrDato = objectToArray(DB::Select($cSql));

      foreach($arrDato as $Dato)
      {
        $elem = array();

        $elem["DT_RowId"] = $Dato["id"];
        $elem["id"]       = $elem["DT_RowId"];
        $elem["opt"]      = '<input class="optreg" id="opt_'.$Dato["id"].'" type="checkbox">';

        $objUsuario = new AccUsuario();

        $user = $objUsuario->withTrashed()->find($Dato["created_by"]);

        $elem["usuario"] = $user->user;
        $elem["nombre"]  = $objUsuario->ObtenerNombreCompletoById($Dato["created_by"]);
        $elem["accion"]  = $Dato["accion"];

        $elem["fecha"]   = fechaEspaniol($Dato["created_at"],true);
        $elem["status"]  = HtmlHelper::GetStatus($Dato["status"], ($Dato["status"] == 1 ? "" : "Cancelado"));

        $response["data"][] = $elem;
      }
    }

    return response()->json($response);
  }

}
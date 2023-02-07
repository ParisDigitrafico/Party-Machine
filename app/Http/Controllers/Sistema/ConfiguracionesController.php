<?php
namespace App\Http\Controllers\Sistema;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Helpers\HtmlHelper;

use App\Models\AccUsuario;
use App\Models\AccModulo;
use App\Models\AccPerfil;

use App\Models\AccConfiguracion;

class ConfiguracionesController extends MController
{
  public function __construct()
  {
    parent::__construct();

    $this->base_object = new AccConfiguracion;

    $this->name = "Configuración";

    $this->permision_key    = "CONFIG";
    $this->permision_create = "C_" . $this->permision_key;
    $this->permision_read   = "R_" . $this->permision_key;
    $this->permision_update = "U_" . $this->permision_key;
    $this->permision_delete = "D_" . $this->permision_key;

    $this->view_key    = "sistema.configuraciones.config";
    $this->view_list   = $this->view_key . "_list";
    $this->view_search = $this->view_key . "_search";
    $this->view_form   = $this->view_key . "_form";
    $this->view_detail = $this->view_key . "_detail";
  }

  public function index(Request $request)
  {
    /*if(array_intersect(["C_USUARIO","R_USUARIO","U_USUARIO","D_USUARIO"], session("permisos"))) */
    if(find_string_in_array('_CONFIG', session("permisos")))
    {
      $response = array();

      $usuarios = AccUsuario::select()->FilterClientes()->get();

      $response["title"] = AccModulo::ObtenerTitulo("M_CONFIGURACIONES");

      return view($this->view_list, $response)->render();
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
    acc.*,
    1
    FROM
    acc_configuracion acc
    WHERE 1=1
    ";

    # filter & order
    {
      if(!empty($c = $b["nombre"]))
      {
        $cSql.=" AND acc.nombre like '%".sanitize($c)."%' ";
      }

      if(isset($b["status"]) && $b["status"] !== "")
      {
        $cSql.=" AND acc.status = '".$b["status"]."' ";
      }

      # order
      $cOrdenSql.= ", length(char(acc.deleted_at)) ASC ";

    }

    $iTotal = count(DB::Select("select 1 from " . after("FROM", $cSql)));

    if($iTotal > 0)
    {
      $response["recordsTotal"]    = $iTotal;
      $response["recordsFiltered"] = $response["recordsTotal"];

      /*$cSql.= "ORDER BY '' " . $cOrdenSql;*/

      if($request->get("length") > 0)
      {
        $cSql.="
        LIMIT ".intval($request->get("length"))." OFFSET ".intval($request->get("start"))."
        ";
      }

      $arrDato = objectToArray(DB::Select($cSql));

      $arrPerfil = objectToArray(AccPerfil::Select()->get());

      foreach($arrDato as $Dato)
      {
        $elem = array();

        $elem["DT_RowId"]     = $Dato["id"];
        $elem["id"]           = $elem["DT_RowId"];
        $elem["opt"]          = '<input class="optreg" id="opt_'.$Dato["id"].'" type="checkbox">';

        $elem["clave"]        = $Dato["clave"];
        $elem["descripcion"]  = $Dato["descripcion"];
        $elem["valor"]        = $Dato["valor"];
        $elem["tipo"]         = $Dato["tipo"];

        //Datos por defecto
        $elem["fecha"]    = fechaEspaniol($Dato["created_at"],true);
        $elem["creador"]  = intval($Dato["created_by"]) == 0 ? "N/A" : AccUsuario::ObtenerNombreCompletoById($Dato["created_by"]);
        $elem["status"]   = HtmlHelper::tagStatus($Dato);
        $elem["opciones"] = '<div class="table-data-feature">';

        if(!empty($Dato["deleted_at"]))
        {
          /*if(in_array($this->permision_create, session("permisos")))
          {
            $elem["opciones"].='
            <a href="#" class="item btnAction" data-id="'.$Dato["id"].'" data-action="restore"
                title="Recuperar" data-title="¿Deseas recuperar este registro?">
            <i class="zmdi zmdi-long-arrow-up"></i>
            </a>
            ';
          }*/
        }
        else
        {
          if(in_array($this->permision_update, session("permisos")))
          {
            $elem["opciones"].='
            <a href="#" class="item btnOpenPanelForm" data-id="'.$Dato["id"].'" title="Editar" data-title="Editar Configuracion">
            <i class="zmdi zmdi-edit"></i>
            </a>
            ';
          }
        }

        $elem["opciones"].='</div>';

        $response["data"][] = $elem;
      }
    }

    return response()->json($response);
  }

}
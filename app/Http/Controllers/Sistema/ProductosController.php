<?php
namespace App\Http\Controllers\Sistema;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Helpers\HtmlHelper;

use App\Models\AccUsuario;
use App\Models\AccModulo;
use App\Models\AccPerfil;
use App\Models\AccPermiso;

use App\Models\SppProducto;
use App\Models\GenCategoria;

class ProductosController extends MController
{
  public function __construct()
  {
    parent::__construct();

    $this->base_object = new SppProducto;

    $this->name = "Productos";

    $this->permision_key    = "PRODUCTO";
    $this->permision_create = "C_" . $this->permision_key;
    $this->permision_read   = "R_" . $this->permision_key;
    $this->permision_update = "U_" . $this->permision_key;
    $this->permision_delete = "D_" . $this->permision_key;

    $this->view_key    = "sistema.productos.producto";
    $this->view_list   = $this->view_key . "_list";
    $this->view_search = $this->view_key . "_search";
    $this->view_form   = $this->view_key . "_form";
    $this->view_detail = $this->view_key . "_detail";
  }

  public function index(Request $request)
  {
    if(find_string_in_array('_PRODUCTO', session("permisos")))
    {
      $response = array();

      $response["title"] = AccModulo::ObtenerTitulo("M_PRODUCTOS");
      $response["name"]  = $this->name;

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
    tbl.*,
    1
    FROM
    spp_producto tbl
    WHERE 1=1
    ";

    # filter & order
    {
      $cSql.= in_array($this->permision_create, session("permisos")) ? "" : " AND tbl.deleted_at IS NULL ";

      if(!empty($cAux = $b["keywords"]))
      {
        $cWhere = "";
        $cWhere.= " tbl.clave like '%$cAux%'
        OR tbl.nombre like '%$cAux%'";

        $cSql.=" AND ( $cWhere )";
      }

      # order

      $order = $request->get("order");

      if($order[0]["column"] == 1 && !empty($order[0]["dir"]))
      {
        $cOrdenSql.= ", tbl.nombre " . $order[0]["dir"];
      }
    }

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

        $elem["clave"]      = $Dato["clave"];
        $elem["nombre"]      = $Dato["nombre"];
        $elem["descripcion"] = $Dato["descripcion"];
        $elem["categoria"] = GenCategoria::withTrashed()->find($Dato["categoria_id"])->nombre;

        $elem["fecha"]    = FechaEspaniol($Dato["created_at"],true)."&nbsp;hrs";
        $elem["creador"]  = intval($Dato["created_by"]) == 0 ? "N/A" : AccUsuario::ObtenerNombreCompletoById($Dato["created_by"]);
        $elem["status"]   = HtmlHelper::tagStatus($Dato);
        $elem["opciones"] = '<div class="table-data-feature">';

        if(in_array($this->permision_read, session("permisos")))
        {
          $elem["opciones"].='
          <a href="#" class="item btnOpenPanelDetail" data-id="'.$Dato["id"].'" title="Detalle" data-title="Detalle '. $this->name .'">
          <i class="zmdi zmdi-search"></i>
          </a>
          ';
        }

        if(!empty($Dato["deleted_at"]))
        {
          if(in_array($this->permision_create, session("permisos")))
          {
            $elem["opciones"].='
            <a href="#" class="item btnAction" data-id="'.$Dato["id"].'" data-action="restore"
                title="Recuperar" data-title="¿Deseas recuperar este registro?">
            <i class="zmdi zmdi-long-arrow-up"></i>
            </a>
            ';
          }
        }
        else
        {
          if(in_array($this->permision_update, session("permisos")))
          {
            $elem["opciones"].='
            <a href="#" class="item btnOpenPanelForm" data-id="'.$Dato["id"].'" title="Editar" data-title="Editar '. $this->name .'">
            <i class="zmdi zmdi-edit"></i>
            </a>
            ';
          }

          if($Dato["es_fijo"] == 0)
          {
            if(in_array($this->permision_delete, session("permisos")))
            {
              $elem["opciones"].='
              <a href="#" class="item btnAction" data-id="'.$Dato["id"].'" data-action="delete" title="Eliminar"
                  data-title="¿Deseas eliminar este registro?">
              <i class="zmdi zmdi-delete"></i>
              </a>
              ';
            }
          }
        }

        /*$elem["opciones"].='
        <!--<button class="item" data-toggle="tooltip" data-placement="top" title="" data-original-title="More">
        <i class="zmdi zmdi-more"></i>
        </button>-->
        </div>';*/

        $elem["opciones"].='</div>';

        $response["data"][] = $elem;
      }
    }

    return response()->json($response);
  }

  public function FormCustom(Request $request)
  {
    $response = array();

    $id = $request->get("id");

    $objBase = new SppProducto;

    if(!empty($id))
    {
      $record = $objBase->withTrashed()->find($id);
    }

    $response["record"] = ($record ?: $objBase);

    return view($this->view_form, $response)->render();
  }

  public function SaveCustom(Request $request)
  {
    $jsonResponse = $this->Save($request)->getContent();

    $response = json_decode($jsonResponse, true);

    if($response["success"] === true)
    {
      $data = $this->base_object->find($response["data"]["id"]);

      $data->syncArchivos($request->get("arrPhoto"));
/*      $data->syncArchivos($request->get("arrArchivo")); */
    }

    return response()->json($response);
  }

  public function Action(Request $request, $id, $action)
  {
    $response = array();

    $response["message"] = "No tienes los permisos suficientes.";

    $objBase = new SppProducto;

    switch($action)
    {
      case "delete":
      {
        if(in_array("D_PLANTILLA", session("permisos")))
        {
          $query = $objBase::find($id);

          if($query->delete())
          {
            $response["success"] = true;
            $response["message"] = "Registro eliminado correctamente.";
          }
        }

        break;
      }
      case "restore":
      case "undelete":
      {
        if(in_array("C_PLANTILLA", session("permisos")))
        {
          $query = $objBase::withTrashed()->find($id);

          if($query->restore())
          {
            $response["success"] = true;
            $response["message"] = "Registro recuperado correctamente.";
          }
        }

        break;
      }
    }

    return response()->json($response);
  }

}

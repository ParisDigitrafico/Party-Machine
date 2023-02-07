<?php
namespace App\Http\Controllers\Sistema;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Helpers\HtmlHelper;

use App\Models\AccUsuario;
use App\Models\AccModulo;
use App\Models\AccPerfil;

use App\Models\CatSede;

class PedidosController extends MController
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index(Request $request)
  {
    /*if(array_intersect(["C_USUARIO","R_USUARIO","U_USUARIO","D_USUARIO"], session("permisos"))) */
    if(find_string_in_array('_PEDIDO', session("permisos")))
    {
      $response = array();

      exit(var_dump(1));

      return view('sistema.catalogos.sedes.sede_list', $response)->render();
    }

    abort(403);
  }

  public function Search(Request $request)
  {
    $response = array();

    return view('sistema.catalogos.sedes.sede_search', $response)->render();
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
    cat.*,
    1
    FROM
    cat_sede cat
    WHERE 1=1
    ";

    # filter & order
    {

      if(!empty($c = $b["nombre"]))
      {
        $cSql.=" AND cat.nombre like '%".sanitize($c)."%' ";
      }

      if(isset($b["status"]) && $b["status"] !== "")
      {
        $cSql.=" AND cat.status = '".$b["status"]."' ";
      }

      # order
      $cOrdenSql.= ", length(char(cat.deleted_at)) ASC ";

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

        $elem["nombre"]       = $Dato["nombre"];
        $elem["descripcion"]  = $Dato["descripcion"];

        //Datos por defecto
        $elem["fecha"]    = fechaEspaniol($Dato["created_at"],true);
        $elem["creador"]  = intval($Dato["created_by"]) == 0 ? "N/A" : AccUsuario::ObtenerNombreCompletoById($Dato["created_by"]);
        $elem["status"]   = HtmlHelper::GetStatus((!empty($Dato["deleted_at"]) ? 0 : $Dato["status"]), (!empty($Dato["deleted_at"]) ? "Eliminado" : ""));
        $elem["opciones"] = '<div class="table-data-feature">';

        if(in_array("C_CATALOGO", session("permisos")) && !empty($Dato["deleted_at"]))
        {
          $elem["opciones"].='
          <a href="#" class="item btnRestore" data-id="'.$Dato["id"].'" title="Recuperar" data-title="¿Deseas recuperar este registro?">
          <i class="zmdi zmdi-long-arrow-up"></i>
          </a>
          ';
        }

        if(in_array("R_CATALOGO", session("permisos")))
        {
          $elem["opciones"].='
          <a href="#" class="item btnOpenPanelDetail" data-id="'.$Dato["id"].'" title="Detalle" data-title="Detalle Sede">
          <i class="zmdi zmdi-search"></i>
          </a>
          ';
        }

        if(in_array("U_CATALOGO", session("permisos")) && empty($Dato["deleted_at"]))
        {
          $elem["opciones"].='
          <a href="#" class="item btnOpenPanelForm" data-id="'.$Dato["id"].'" title="Editar" data-title="Editar Sede">
          <i class="zmdi zmdi-edit"></i>
          </a>
          ';
        }

        if($Dato["es_super"] != 1)
        {
          if(in_array("D_CATALOGO", session("permisos")) && empty($Dato["deleted_at"]))
          {
            $elem["opciones"].='
            <a href="#" class="item btnDelete" data-id="'.$Dato["id"].'" title="Eliminar" data-title="¿Deseas eliminar este registro?">
            <i class="zmdi zmdi-delete"></i>
            </a>
            ';
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

  public function Show(Request $request, $id)
  {
    $response = array();

    $Dato = objectToArray(CatSede::select()->where("id",$id)->first());

    if($Dato)
    {
      $response["Dato"] = $Dato;
    }

    return view('sistema.catalogos.sedes.sede_detail', $response)->render();
  }

  public function Form(Request $request)
  {
    $response = array();

    $Dato = array();

    if(!empty($request->get("id")))
    {
      $Dato = objectToArray(CatSede::select()->where("id", $request->get("id"))->first());

    }

    $response["Dato"] = $Dato;

    return view('sistema.catalogos.sedes.sede_form', $response)->render();
  }

  public function Save(Request $request)
  {
    $response = array();

    if(array_intersect(["C_CATALOGO","U_CATALOGO"], session("permisos")))
    {
      $objBase = new CatSede;

      $id   = $request->get("id");
      $Dato = $request->get("Dato");

      if($Dato)
      {
        $Dato["updated_at"] = now();

        $query = $objBase::select()->where("id", $id);

        $row = $query->first();

        if($row)
        {
          if(in_array("U_CATALOGO",session("permisos")))
          {
            $Dato["status"] = intval(isset($Dato["status"]));

            if($query->update($Dato))
            {
              $response["success"] = true;
            }
          }
          else
          {
            $response["message"] = "No tienes los permisos suficientes.";
          }
        }
        else
        {
          if(in_array("C_CATALOGO",session("permisos")))
          {
            $Dato["created_by"] = session("usuario_id");

            if($objBase::insert($Dato))
            {
              $id = $objBase::latest()->first()->id;

              $response["success"] = true;
            }
          }
          else
          {
            $response["message"] = "No tienes los permisos suficientes.";
          }
        }

        # guarda perfiles
        if($response["success"] === true)
        {

        }
      }
      else
      {
        $response["message"] = "No hay datos para guardar.";
      }
    }
    else
    {
      $response["message"] = "No tienes los permisos suficientes.";
    }

    $response["message"] = $response["success"] === true ? "Registro guardado correctamente." : ( $response["message"] ?: "Error al guardar registro." );

    return response()->json($response);
  }

  public function Action(Request $request, $id, $action)
  {
    $response = array();

    $response["message"] = "No tienes los permisos suficientes.";

    $objBase = new CatSede;

    switch($action)
    {
      case "delete":
      {
        if(in_array("D_USUARIO", session("permisos")))
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
        if(in_array("C_USUARIO", session("permisos")))
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
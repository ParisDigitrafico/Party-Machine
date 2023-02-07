<?php
namespace App\Http\Controllers\Sistema;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Helpers\HtmlHelper;

use App\Models\AccUsuario;
use App\Models\AccModulo;
use App\Models\AccPerfil;
use App\Models\AccPermiso;
use App\Models\AccControlSesion;

class PerfilesController extends MController
{
	public function __construct()
	{
	  parent::__construct();

    $this->base_object = new AccPerfil;

    $this->name = "Perfil";

    $this->permision_key    = "PERFIL";
    $this->permision_create = "C_" . $this->permision_key;
    $this->permision_read   = "R_" . $this->permision_key;
    $this->permision_update = "U_" . $this->permision_key;
    $this->permision_delete = "D_" . $this->permision_key;

    $this->view_key    = "sistema.perfiles.perfil";
    $this->view_list   = $this->view_key . "_list";
    $this->view_search = $this->view_key . "_search";
    $this->view_form   = $this->view_key . "_form";
    $this->view_detail = $this->view_key . "_detail";
	}

  public function index(Request $request)
  {
    if(find_string_in_array('_PERFIL', session("permisos")))
    {
      $response = array();

      $response["title"] = "Lista Perfiles";

      return view('sistema.perfiles.perfil_panel', $response)->render();
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

    $cSql = "
    SELECT
    t.*
    FROM
    acc_perfil t
    WHERE 1=1
    ";

    # filter & order
    {
      $cSql.= in_array("C_PERFIL", session("permisos")) ? "" : " AND length(char(t.deleted_at)) = 0 ";

      if(!empty($nombre))
      {
        $cSql.=" AND t.nombre like '%".sanitize($nombre)."%' ";
      }

      # order
      $cOrdenSql.= ", length(char(t.deleted_at)) ASC ";

      $order[0]["column"] = $order[0]["column"] ?: 1;

      if($order[0]["column"] == 0)
      {
        /*$cOrdenSql.= ", t.id " . $order[0]["dir"];*/
      }

      if($order[0]["column"] == 1)
      {
        $cOrdenSql.= ", t.nombre " . $order[0]["dir"];
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

        $elem["nombre"] = $Dato["nombre"];
        $elem["status"] = HtmlHelper::tagStatus($Dato);

        $elem["opciones"] = '<div class="table-data-feature">';

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
            if(in_array($this->permision_delete, session("permisos")) && empty($Dato["deleted_at"]))
            {
              $elem["opciones"].='
              <a href="#" class="item btnAction" data-id="'.$Dato["id"].'" data-action="delete"
                  title="Eliminar" data-title="¿Deseas eliminar este registro?">
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

  public function OpenList(Request $request)
  {
    if(find_string_in_array('_PERFIL', session("permisos")))
    {
      $response = array();

      $response["title"] = "&nbsp;";

      return view('sistema.perfiles.perfil_panel', $response)->render();
    }

    abort(403);
  }

  public function Form(Request $request)
  {
    $response = array();

    $objPerfil  = new AccPerfil;
    $objModulo  = new AccModulo;
    $objPermiso = new AccPermiso;

    $Dato = array();

    $arrModulo = array();
    $arrPermisoSel = array();

    $modulos = $objModulo::select()->FilterStatus(1)->whereRaw(" id IN (SELECT modulo_id FROM acc_permiso) ")->get();

    foreach($modulos as $modulo)
    {
      $Modulo = objectToArray($modulo);

      $permisos = $objPermiso::select()->FilterStatus(1)->where("modulo_id", $Modulo["id"])->get();

      $Modulo["permisos"] = objectToArray($permisos);

      $arrModulo[] = $Modulo;
    }

    if(!empty($request->get("id")))
    {
      $Dato = objectToArray($objPerfil::find($request->get("id")));

      $arrPermisoSel = $objPerfil->ObtenerArrPermisosSeleccionados($Dato["id"]);
    }

    $response["Dato"] = $Dato;

    $response["arrPermisoSel"] = $arrPermisoSel;

    $response["arrModulo"] = $arrModulo;

    return view('sistema.perfiles.perfil_form', $response)->render();
  }

  public function Save(Request $request)
  {
    $response = array();

    if(array_intersect(["C_PERFIL","U_PERFIL"], session("permisos")))
    {
      $objBase = new AccPerfil;

      $id   = $request->get("id");
      $Dato = $request->get("Dato");

      if(!empty($Dato))
      {
        $Dato["updated_at"] = now();

        $query = $objBase::select()->where("id", $id);

        $item = $query->first();

        if($item)
        {
          if(in_array("U_PERFIL",session("permisos")))
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
          if(in_array("C_PERFIL",session("permisos")))
          {
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

        if($response["success"] === true)
        {
          DB::Table("acc_perfil_permiso")->where('perfil_id', $id)->delete();

          $arrAux = $request->get("arrPermiso");

          if(is_array($arrAux) && !empty($arrAux))
          {
            foreach($arrAux as $idpermiso)
            {
              $DatoAux = [];

              if(is_numeric($idpermiso) && $idpermiso > 0)
              {
                $DatoAux["perfil_id"]  = $id;
                $DatoAux["permiso_id"] = $idpermiso;

                DB::Table("acc_perfil_permiso")->insert($DatoAux);
              }
            }
          }

          $items = DB::Table("acc_usuario_perfil")->where('perfil_id', $id)->get();

          if(count($items) > 0)
          {
            foreach($items as $item)
            {
              DB::Table("acc_control_sesion")->where("usuario_id", $item->usuario_id)->delete();
            }
          }
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

    $response["message"] = ($response["success"] === true ?
                              "Registro guardado correctamente." : ( $response["message"] ?: "Error al guardar registro." ));

    return response()->json($response);
  }

}
<?php
namespace App\Http\Controllers\Sistema;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use App\Helpers\HtmlHelper;

use App\Models\AccUsuario;
use App\Models\AccModulo;
use App\Models\AccPerfil;

use App\Models\Sintoma;

use App\Models\GenArchivo;

class ClientesController extends MController
{
  public function __construct()
  {
    parent::__construct();

    $this->base_object = new AccUsuario;

    $this->name_single = "Cliente";
    $this->name_plural = "Clientes";

    $this->permision_key    = "CLIENTE";
    $this->permision_create = "C_" . $this->permision_key;
    $this->permision_read   = "R_" . $this->permision_key;
    $this->permision_update = "U_" . $this->permision_key;
    $this->permision_delete = "D_" . $this->permision_key;

    $this->view_key    = "sistema.clientes.cliente";
    $this->view_list   = $this->view_key . "_list";
    $this->view_search = $this->view_key . "_search";
    $this->view_form   = $this->view_key . "_form";
    $this->view_detail = $this->view_key . "_detail";
  }

  public function index(Request $request)
  {
    if(find_string_in_array('_CLIENTE', session("permisos")))
    {
      $response = array();

      $usuarios = AccUsuario::select()->FilterClientes()->get();

      $response["title"] = AccModulo::ObtenerTitulo("M_CLIENTES");

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
    usr.*,
    GROUP_CONCAT(upf.perfil_id) AS perfiles,
    1
    FROM
    acc_usuario usr
    LEFT JOIN
    acc_usuario_perfil upf
    ON
    usr.id = upf.usuario_id
    WHERE 1=1
    AND usr.es_cliente=1
    ";

    # filter & ordering
    {
      $cSql.= in_array("C_CLIENTE", session("permisos")) ? "" : " AND length(char(usr.deleted_at)) = 0 ";

      if(!empty($cAux = $b["keywords"]))
      {
        $cWhere = "";

        /*$cSql.=" OR  ";*/
        $arrAux = explode(" ", $cAux);

        $arrAux2 = array();

        foreach($arrAux as $val)
        {
          $arrAux2[] = " usr.nombre LIKE '%".sanitize($val)."%' ";
          $arrAux2[] = " usr.apellidos LIKE '%".sanitize($val)."%' ";
        }

        $cSql.= " AND ( usr.user LIKE '%".sanitize($cAux)."%' OR usr.empresa LIKE '%".sanitize($cAux)."%' " . (!empty($arrAux2) ? " OR " . implode(" OR ", $arrAux2) : "" ) . " ) ";


        /*if(is_numeric($cAux) && $cAux > 0)
        {
          $cAux = intval($cAux);
          $cSql.=" AND ( tbl.id IN ($cAux)  )  ";
        }
        else
        {
          $arrAux = explode(" ", $cAux);

          $arrAux2 = array();

          foreach($arrAux as $val)
          {
            $arrAux2[] = " usr.nombre like '%".sanitize($val)."%' ";
            $arrAux2[] = " usr.apellidos like '%".sanitize($val)."%' ";
          }

          if(!empty($arrAux2))
          {
            $cWhere = "SELECT id from acc_usuario usr WHERE " . implode(" OR ", $arrAux2);

            $cSql.=" AND ( tbl.usuario_id IN ($cWhere)  )  ";
          }
        }
*/
      }

      if(isset($b["status"]) && $b["status"] != "")
      {
        $iAux = intval($b["status"]);

        if($iAux > 0 && in_array($iAux,[0,1]))
        {
          $cSql.=" AND usr.status = '".$iAux."' ";
        }
        else
        {
          $cSql.=" AND usr.deleted_at IS NOT NULL ";
        }
      }

      $cSql.=" GROUP BY usr.id ";

      # order
      $cOrdenSql.= ", length(char(usr.deleted_at)) ASC ";

      $order = $request->get("order");

      if($order[0]["column"] == 0 && !empty($order[0]["dir"]))
      {
        $cOrdenSql.= ", usr.user " . $order[0]["dir"];
      }

      if($order[0]["column"] == 1 && !empty($order[0]["dir"]))
      {
        $cOrdenSql.= ", usr.nombre " . $order[0]["dir"];
      }
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

        $elem["codigo"]       = $Dato["codigo"];
        $elem["user"]         = $Dato["user"] . ($Dato["es_super"] == 1 ? '&nbsp;<i class="fas fa-star" title="Súper Usuario"></i>' : '');
        $elem["nombre"]       = $Dato["nombre"];
        $elem["apellidos"]    = $Dato["apellidos"];
        $elem["apellido_pat"] = $Dato["apellido_pat"];
        $elem["apellido_mat"] = $Dato["apellido_mat"];
        $elem["empresa"]      = $Dato["empresa"];

        $elem["fecha"]    = fechaEspaniol($Dato["created_at"],true);
        $elem["creador"]  = intval($Dato["created_by"]) == 0 ? "N/A" : AccUsuario::ObtenerNombreCompletoById($Dato["created_by"]);
        $elem["status"]   = HtmlHelper::tagStatus($Dato);
        $elem["opciones"] = '<div class="table-data-feature">';

        if(in_array("R_CLIENTE", session("permisos")))
        {
          $elem["opciones"].='
          <a href="#" class="item btnOpenPanelDetail" data-id="'.$Dato["id"].'" title="Detalle" data-title="Detalle Cliente">
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

          if(in_array($this->permision_delete, session("permisos")))
          {
            $elem["opciones"].='
            <a href="#" class="item btnAction" data-id="'.$Dato["id"].'" data-action="delete"
                title="Eliminar" data-title="¿Deseas eliminar este registro?">
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

    $Dato = objectToArray(AccUsuario::select()->withTrashed()->where("id", $id)->first());

    if($Dato)
    {
      $response["Dato"] = $Dato;
    }

    return view($this->view_detail, $response)->render();
  }

  public function Form(Request $request)
  {
    $response = array();

    $id = $request->get("id");

    $arrPerfil    = array();
    $arrSintoma   = array();

    if(!empty($id))
    {
      $data = AccUsuario::where("id", $id)->first();
    }

    $response["data"] = $data;

    return view($this->view_form, $response)->render();
  }

  public function Save(Request $request)
  {
    $response = array();

    if(array_intersect(["C_CLIENTE","U_CLIENTE"], session("permisos")))
    {
      $objBase    = new AccUsuario;
      $objArchivo = new GenArchivo;

      $id   = $request->get("id");
      $Dato = $request->get("Dato");

      $Dato["updated_at"] = now();
      $Dato["user"]       = trim(strtolower($Dato["user"]));

      $query = $objBase->select()->where("id", $id);

      $record = $query->first();

      if($record)
      {
        if(in_array("U_CLIENTE", session("permisos")))
        {
          $Dato["status"] = isset($Dato["status"]);

          if($query->update($Dato, $id))
          {
            $response["success"] = true;
          }
        }
      }
      else
      {
        if(in_array("C_CLIENTE",session("permisos")))
        {
          $record = $objBase->withTrashed()->where("user", $Dato["user"])->first();

          if($record)
          {
            $response["success"] = false;
            $response["message"] = "Ya existe este usuario, favor de verificar.";

            if(!empty($record->deleted_at))
            {
              $response["message"] = "Si no encuentra el cliente probablemente este eliminado, es necesario reactivarlo para volver a utilizarlo, favor de verificar.";
            }
          }
          else
          {
            $Dato["created_at"] = now();
            $Dato["es_cliente"] = 1;

            if($objBase->AgregarUsuario($Dato))
            {
              $id = $objBase->latest()->first()->id;

              $record = $objBase->find($id);

              $record->perfiles()->sync(array(5));

              $response["success"] = true;
            }
          }
        }
      }

      if($response["success"] === true)
      {
        $response["id"] = $id;

        $objArchivo->SincronizarArchivos("usuario_id", $id, "FOTO_VISITA", $request->get("arrFoto"));

        /*$objBase->ConsultarRegistro($id);

        $response["data"] = $objBase->ObtenerDato();

        $objBase->SincronizarContacto($id, $request->get("arrContacto"));
        $objBase->SincronizarSintoma($id, $request->get("arrSintoma"));

        $objArchivo->SincronizarArchivos("usuario_id", $id, "FOTO_VISITA", $request->get("arrFoto"));*/
      }
    }
    else
    {
      $response["message"] = "No tienes los permisos suficientes.";
    }

    $response["message"] = $response["success"] === true ? "Registro guardado correctamente." : ( $response["message"] ?: "Error al guardar registro." );

    return response()->json($response);
  }

}
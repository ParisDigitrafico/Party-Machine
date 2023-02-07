<?php
namespace App\Http\Controllers\Sistema;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Helpers\HtmlHelper;

use App\Models\AccUsuario;
use App\Models\AccModulo;
use App\Models\AccPerfil;
use App\Models\AccControlSesion;

use App\Models\Sintoma;

class UsuariosController extends SuperController
{
  public function __construct()
  {
    parent::__construct();

    $this->base_object = new AccUsuario();

    $this->name_single = "Usuario";
    $this->name_plural = "Usuarios";

    $this->permision_key    = "USUARIO";
    $this->permision_create = "C_" . $this->permision_key;
    $this->permision_read   = "R_" . $this->permision_key;
    $this->permision_update = "U_" . $this->permision_key;
    $this->permision_delete = "D_" . $this->permision_key;

    $this->view_key    = "sistema.usuarios.usuario";
    $this->view_list   = $this->view_key . "_list";
    $this->view_search = $this->view_key . "_search";
    $this->view_form   = $this->view_key . "_form";
    $this->view_detail = $this->view_key . "_detail";
  }

  public function index(Request $request)
  {
    /*if(array_intersect(["C_USUARIO","R_USUARIO","U_USUARIO","D_USUARIO"], session("permisos"))) */
    if(find_string_in_array('_USUARIO', session("permisos")))
    {
      $response = array();

      $usuarios = AccUsuario::select()->FilterClientes()->get();

      $response["title"] = AccModulo::ObtenerTitulo("M_USUARIOS");

      return view('sistema.usuarios.usuario_list', $response)->render();
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
    AND usr.es_cliente=0
    ";

    # filter & ordering
    {
      $cSql.= session("es_super") === true ? "" : " AND usr.es_super=0 " ;

      $cSql.= in_array("C_USUARIO", session("permisos")) ? "" : " AND length(char(usr.deleted_at)) = 0 ";

      $b = $request->get("b");

      if(!empty($cAux = $b["keywords"]))
      {
        $cSql.=" AND usr.user like '%".sanitize($cAux)."%' ";
      }

      /*if(!empty($c = $b["nombre"]))
      {
        $cSql.=" AND usr.nombre like '%".sanitize($c)."%' ";
      }

      if(!empty($c = $b["apellidos"]))
      {
        $cSql.=" AND usr.apellidos like '%".sanitize($c)."%' ";
      }*/

      if(isset($b["status"]) && $b["status"] != "")
      {
        $iAux = intval($b["status"]);

        $cSql.= ($iAux >= 0) ? " AND usr.status = '".$iAux."' AND usr.deleted_at IS NULL " : " AND usr.deleted_at IS NOT NULL ";
      }

      $cSql.=" GROUP BY usr.id ";

      # order
      $cOrdenSql.= ", length(char(usr.deleted_at)) ASC ";

      $order = $request->get("order");

      /*if($order[0]["column"] == 0 && !empty($order[0]["dir"]))
      {
        $cOrdenSql.= ", usr.user " . $order[0]["dir"];
      }

      if($order[0]["column"] == 1 && !empty($order[0]["dir"]))
      {
        $cOrdenSql.= ", usr.nombre " . $order[0]["dir"];
      }*/
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

      $arrPerfil = objectToArray(AccPerfil::Select()->get());

      foreach($arrDato as $Dato)
      {
        $elem = array();

        $elem["DT_RowId"] = $Dato["id"];
        $elem["id"]       = $elem["DT_RowId"];
        $elem["opt"]      = '<input class="optreg" id="opt_'.$Dato["id"].'" type="checkbox">';

        $elem["codigo"]       = $Dato["codigo"];
        $elem["user"]         = ($Dato["es_super"] == 1 ? '<b><span style="color:blue; font-size: 12pt;" title="Es Super Usuario">&spades;</span></b>' : '') . $Dato["user"];
        $elem["nombre"]       = $Dato["nombre"];
        $elem["apellidos"]    = $Dato["apellidos"];
        $elem["apellido_pat"] = $Dato["apellido_pat"];
        $elem["apellido_mat"] = $Dato["apellido_mat"];

        $arr0 = explode(",",$Dato["perfiles"]);

        $arrAux = array();

        foreach($arrPerfil as $Perfil)
        {
          if(in_array($Perfil["id"], $arr0))
          {
            $arrAux[] = $Perfil["nombre"];
          }
        }

        $elem["perfiles"] = !empty($arrAux) ? implode(", ",$arrAux) : "N/A";

        $elem["fecha"]    = fechaEspaniol($Dato["created_at"],true);
        $elem["creador"]  = intval($Dato["created_by"]) == 0 ? "N/A" : AccUsuario::ObtenerNombreCompletoById($Dato["created_by"]);
        $elem["status"]   = HtmlHelper::tagStatus($Dato);
        $elem["opciones"] = '<div class="table-data-feature">';

        if(in_array($this->permision_read, session("permisos")))
        {
          $elem["opciones"].='
          <a href="#" class="item btnOpenPanelDetail" data-id="'.$Dato["id"].'" title="Detalle" data-title="Detalle '. $this->name_single .'">
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
            <a href="#" class="item btnOpenPanelForm" data-id="'.$Dato["id"].'" title="Editar" data-title="Editar '. $this->name_single .'">
            <i class="zmdi zmdi-edit"></i>
            </a>
            ';
          }

          if($Dato["es_super"] != 1)
          {
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

  public function CustomForm(Request $request)
  {
    $response = array();

    $Dato = array();

    $arrPerfil    = array();
    $arrPerfilSel = array();
    $arrSintoma   = array();

    if(!empty($request->get("id")))
    {
      $Dato = objectToArray(AccUsuario::select()->where("id", $request->get("id"))->first());

      $arrPerfilSel = AccUsuario::ObtenerArrPerfilSeleccionado($Dato["id"]);
    }

    $perfiles = AccPerfil::Select()->FilterStatus(1)->orderBy("nombre","ASC")->get();

    $response["Dato"] = $Dato;

    $response["arrPerfilSel"] = $arrPerfilSel;

    $response["arrPerfil"] = objectToArray($perfiles);

    return view('sistema.usuarios.usuario_form', $response)->render();
  }

  public function CustomSave(Request $request)
  {
    $response = array();

    $bPermiso = false;

    try
    {
      $objBase        = new AccUsuario;
      $objCtrlSession = new AccControlSesion;

      $id   = $request->get("id");
      $Dato = $request->get("Dato");

      if($Dato)
      {
        $Dato["updated_at"] = now();
        $Dato["user"] = trim(strtolower($Dato["user"]));

        if(!empty($request->get("pass")))
        {
          $Dato["pass"] = sha1($request->get("pass"));
        }

        $query = $objBase::select()->where("id", $id);

        $record = $query->first();

        if($record)
        {
          if(in_array("U_USUARIO",session("permisos")))
          {
            $bPermiso = true;

            if(session("es_super") === true && session("usuario_id") != $id)
            {
              $Dato["es_super"] = $request->has("es_super") ? 1 : 0;
            }

            $Dato["status"] = $record->es_super == 1 ? 1 : intval(isset($Dato["status"]));

            if($query->update($Dato))
            {
              if(session("usuario_id") != $record->id)
              {
                $objCtrlSession->where("usuario_id", $record->id)->delete();
              }

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
          if(in_array("C_USUARIO",session("permisos")))
          {
            $bPermiso = true;

            $record = $objBase->where("user", $Dato["user"])->first();

            if($Dato["user"] == $record->user)
            {
              $response["success"] = false;
              $response["message"] = "Ya existe este usuario, favor de verificar.";

              if(!empty($record->deleted_at))
              {
                $response["message"] = "Este usuario se encuentra eliminado, es necesario reactivarlo para volve a utilizarlo, favor de verificar.";
              }
            }
            else
            {
              if(session("es_super") === true)
              {
                $Dato["es_super"] = !empty($request->get("es_super")) ? 1 : 0;
              }

              if($objBase::AgregarUsuario($Dato))
              {
                $id = $objBase->latest()->first()->id;

                $response["success"] = true;
              }
            }
          }
          else
          {
            $response["message"] = trans("general.error_sin_permiso");
          }
        }

        # guarda perfiles
        if($response["success"] === true)
        {
          $DatoPerfil = $request->get("DatoPerfil");

          $objBase->EliminarPerfilesRelacionados($id);

          if(!empty($DatoPerfil) && is_array($DatoPerfil))
          {
            foreach($DatoPerfil as $perfil_id)
            {
              $Dato = array();

              $Dato["usuario_id"] = $id;
              $Dato["perfil_id"]  = $perfil_id;

              DB::Table("acc_usuario_perfil")->insert($Dato);
            }
          }
        }
      }
      else
      {
        $response["message"] = trans("general.error_sin_datos");
      }

      if($bPermiso === false)
      {
        $response["success"] = false;
        $response["message"] = trans("general.error_sin_permiso");
      }
    }
    catch(exception $ex)
    {
      $response["success"] = false;
      $response["message"] = trans("general.error_inesperado");
    }

    $response["message"] = $response["success"] === true ? trans("general.guardado_correcto") : ( $response["message"] ?: trans("general.error_guardado"));

    return response()->json($response);
  }

}
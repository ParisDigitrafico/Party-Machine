<?php
namespace App\Http\Controllers\Sistema;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use App\Models\AccControlSesion;

class MController extends Controller
{
  var $base_object;

  var $permision_key;
  var $permision_create;
  var $permision_read;
  var $permision_update;
  var $permision_delete;

  var $view_key;
  var $view_list;
  var $view_search;
  var $view_form;
  var $view_detail;

	public function __construct()
	{
    $this->app = "sistema";

    $this->middleware(function($request, $next){
      $objCtrlSesion = new AccControlSesion();

      if(intval(session("usuario_id")) == 0 && intval(request()->cookie('uid')) > 0 && request()->cookie('app') == $this->app)
      {
        $objLoginController = new LoginController();

        $objLoginController->process(intval(request()->cookie('uid')));
      }

      if(intval(session("usuario_id")) > 0 && session("app") === $this->app)
      {
        if($objCtrlSesion->Existe(session("usuario_id"), session("app"), session("medio")))
        {
          return $next($request);
        }
      }

      header("Location:/sistema/login/close/");
      exit;
    });
	}

  public function Search(Request $request)
  {
    $response = array();

    return view($this->view_search, $response)->render();
  }

  public function Form(Request $request)
  {
    $response = array();

    $data = $this->base_object->withTrashed()->where("id", $request->get("id"))->first() ?: $this->base_object;

    $response["data"] = $data;

    return view($this->view_form, $response)->render();
  }

  public function Save(Request $request)
  {
    $response = array();

    $bPermiso = false;

    DB::beginTransaction();

    try
    {
      $id   = $request->get("id");
      $Dato = $request->get("Dato");

      $Dato["updated_at"] = now();

      $query = $this->base_object->where("id", $id);

      $record = $query->first();

      if($record)
      {
        if(in_array($this->permision_update, session("permisos")))
        {
          $bPermiso = true;

          $Dato["status"] = intval(isset($Dato["status"]));

          if($query->update($Dato))
          {
            $response["success"] = true;
          }
        }
      }
      else
      {
        if(in_array($this->permision_create, session("permisos")))
        {
          $bPermiso = true;

          $Dato["created_by"] = session("usuario_id");

          if($this->base_object->insert($Dato))
          {
            $id = $this->base_object->latest()->first()->id;

            $response["success"] = true;
          }
        }
      }

      if($response["success"] === true)
      {
        $data = $this->base_object->find($id);

        $response["data"] = objectToArray($data);

        DB::commit();
      }

      if($bPermiso === false)
      {
        $response["message"] = trans("general.error_sin_permiso");
      }
    }
    catch(\exception $ex)
    {
      DB::rollback();

      unset($response["success"]);

      $response["message"] = $ex->getMessage();

      if(config("app.env") == "production")
      {
        $response["message"] = trans("general.error_inesperado");
      }
    }

    $response["message"] = ($response["success"] === true ?
                              trans("general.guardado_correcto") : ( $response["message"] ?: trans("general.error_guardado") ));

    return response()->json($response);
  }

  public function Show(Request $request, $id)
  {
    $response = array();

    $data = $this->base_object->withTrashed()->where("id", $id)->first();

    if($data)
    {
      $response["data"] = $data;
    }

    return view($this->view_detail, $response)->render();
  }

  public function Action(Request $request, $id, $action)
  {
    $response = array();

    $response["message"] = trans("general.error_sin_accion");

    $bPermiso = false;

    try
    {
      switch($action)
      {
        case "delete":
        {
          if(in_array($this->permision_delete, session("permisos")))
          {
            $bPermiso = true;

            $query = $this->base_object->where("id", $id);

            if($query->delete())
            {
              $response["success"] = true;
              $response["message"] = trans("general.eliminado_correcto");
            }
          }

          break;
        }
        case "restore":
        {
          if(in_array($this->permision_create, session("permisos")))
          {
            $bPermiso = true;

            $query = $this->base_object->withTrashed()->find($id);

            if($query->restore())
            {
              $response["success"] = true;
              $response["message"] = trans("general.recuperado_correcto");
            }
          }

          break;
        }
        default:
          throw new \exception("no existe accion por defecto !!");
      }

      if($bPermiso === false)
      {
        $response["message"] = trans("general.error_sin_permiso");
      }
    }
    catch(\exception $ex)
    {
      unset($response["success"]);

      $response["message"] = $ex->getMessage();

      if(config("app.env") == "production")
      {
        $response["message"] = trans("general.error_inesperado");
      }
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
        $cAux.= " AND ". $key . " ". $value["operator"] . " '".$value["value"]."' ";
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
        $cAux.= " OR ". $key . " ". $value["operator"] . " '".$value["value"]."' ";
      }

      if(!empty($cAux))
      {
        $cSql.= $cAux;
      }
    }

    $cSort = $request->get("sort");

    if(!empty($cSort))
    {
      $arr = json_decode($cSort, true);

      $cAux = " ORDER BY '' ";

      foreach($arr as $key => $value)
      {
        $cAux.= ", ". $key . " ".$value["order"]." ";
      }

      if(!empty($cAux))
      {
        $cSql.= $cAux;
      }

      /*if($table == "cat_sector")
      {
        exit($cSql);
      }*/
    }

    $response["data"] = objectToArray(DB::Select($cSql));

    return json_encode($response);
  }

}
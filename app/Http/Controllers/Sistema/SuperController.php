<?php
namespace App\Http\Controllers\Sistema;

use Illuminate\Http\Request;

class SuperController extends MController
{
  var $base_object;

  var $name_single;
  var $name_plural;

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
    parent::__construct();
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

    try
    {
      $id   = $request->get("id");
      $Dato = $request->get("Dato");

      $Dato["updated_at"] = now();

      $query = $this->base_object->where("id", $id);

      $item = $query->first();

      if($item)
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

          $Dato["created_by"] = session("idusuario");

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

}
<?php
namespace App\Http\Controllers\Sistema;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Helpers\HtmlHelper;

use App\Models\AccUsuario;
use App\Models\AccModulo;
use App\Models\AccPerfil;

use App\Models\WebPagina;
use App\Models\WebBanner;

use App\Models\GenArchivo;

class PaginasController extends MController
{
  public function __construct()
  {
    parent::__construct();

    $this->base_object = new WebPagina;

    $this->name_single = "Página";
    $this->name_plural = "Páginas";

    $this->permision_key    = "PAGINA";
    $this->permision_create = "C_" . $this->permision_key;
    $this->permision_read   = "R_" . $this->permision_key;
    $this->permision_update = "U_" . $this->permision_key;
    $this->permision_delete = "D_" . $this->permision_key;

    $this->view_key    = "sistema.paginas.pagina";
    $this->view_list   = $this->view_key . "_list";
    $this->view_search = $this->view_key . "_search";
    $this->view_form   = $this->view_key . "_form";
    $this->view_detail = $this->view_key . "_detail";
  }

  public function index(Request $request)
  {
    if(find_string_in_array('_PAGINA', session("permisos")))
    {
      $response = array();

      $response["title"] = AccModulo::ObtenerTitulo("M_PAGINAS");
      $response["name"]  = $this->name_single;

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

    $cSql = "
    SELECT
    tbl.*,
    1
    FROM
    web_pagina tbl
    WHERE 1=1
    ";

    # filter & order
    {
      $cSql.= in_array($this->permision_create, session("permisos")) ? "" : " AND tbl.deleted_at IS NULL ";

      $b = $request->get("b");

      if(!empty($cAux = $b["keywords"]))
      {

      }

      # order
      $cOrdenSql.= ", tbl.es_menu DESC ";
      $cOrdenSql.= ", tbl.es_fijo DESC ";
      $cOrdenSql.= ", tbl.orden ASC ";

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

        $elem["clave"]       = $Dato["clave"];
        $elem["nombre"]      = $Dato["nombre"];
        $elem["url"]         = $Dato["url"];
        $elem["descripcion"] = $Dato["descripcion"];
        $elem["es_menu"]     = $Dato["es_menu"] ? "Si":"No";
        $elem["es_visible"]  = $Dato["es_visible"] ? "Si":"No";
        $elem["orden"]       = $Dato["es_menu"] ? $Dato["orden"] : "-";

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

  public function CustomForm(Request $request)
  {
    $response = array();

    $objBanner = new WebBanner();

    $data = $this->base_object->withTrashed()->where("id", $request->get("id"))->first() ?: $this->base_object;

    $banners = $objBanner->where("status",1)->get();

    $response["data"]    = $data;
    $response["banners"] = $banners;

    return view($this->view_form, $response)->render();
  }

  public function CustomSave(Request $request)
  {
    $Dato = $request->get("Dato");

    $Dato["es_menu"]    = intval(isset($Dato["es_menu"]));
    $Dato["es_visible"] = intval(isset($Dato["es_visible"]));
    $Dato["mostrar_suscribete"] = intval(isset($Dato["mostrar_suscribete"]));
    $Dato["abrir_otra_ventana"] = intval(isset($Dato["abrir_otra_ventana"]));

    $request->merge(["Dato"=>$Dato]);

    $jsonResponse = $this->Save($request)->getContent();

    $response = json_decode($jsonResponse, true);

    if($response["success"] === true)
    {
      $data = $this->base_object->whereId($response["data"]["id"])->first();

      if(empty($data->clave))
      {
        $data->clave = "P" . $data->id;
        $data->save();
      }

      $data->banners()->sync($request->get("banners"));

      $data->syncArchivos($request->get("arrPhotoEncabezado"), "FOTO_ENCABEZADO");

      \File::delete(storage_path('app/menu_website.json'));
    }

    return response()->json($response);
  }

}
<?php
namespace App\Http\Controllers\Sistema;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Helpers\HtmlHelper;

use App\Models\AccUsuario;
use App\Models\AccModulo;
use App\Models\AccPerfil;

use Maatwebsite\Excel\Facades\Excel;

class ReportesController extends MController
{
  public function __construct()
  {
    parent::__construct();

    /*$this->base_object = new AccUsuario; */

    $this->permision_key    = "REPORTE";
    $this->permision_create = "C_" . $this->permision_key;
    $this->permision_read   = "R_" . $this->permision_key;
    $this->permision_update = "U_" . $this->permision_key;
    $this->permision_delete = "D_" . $this->permision_key;

    $this->view_key    = "sistema.reportes.reporte";
    $this->view_list   = $this->view_key . "_list";
    $this->view_search = $this->view_key . "_search";
    $this->view_form   = $this->view_key . "_form";
    $this->view_detail = $this->view_key . "_detail";
  }

  public function index(Request $request)
  {
    $response = array();

    $response["title"] = AccModulo::ObtenerTitulo("M_REPORTES");

    return view($this->view_list, $response)->render();
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
    fbz_reporte tbl
    WHERE 1=1
    ";

    # filter & order
    {
      if(!empty($c = $b["nombre"]))
      {
        $cSql.=" AND tbl.nombre like '%".sanitize($c)."%' ";
      }

      if(isset($b["status"]) && $b["status"] !== "")
      {
        $cSql.=" AND tbl.status = '".$b["status"]."' ";
      }

      # order
      $cOrdenSql.= ", length(char(tbl.deleted_at)) ASC ";
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

        $elem["DT_RowId"]     = $Dato["id"];
        $elem["id"]           = $elem["DT_RowId"];
        $elem["opt"]          = '<input class="optreg" id="opt_'.$Dato["id"].'" type="checkbox">';

        $elem["clave"]        = $Dato["clave"];
        $elem["nombre"]       = $Dato["nombre"];

        //Datos por defecto
        $elem["fecha"]    = fechaEspaniol($Dato["created_at"],true);
        $elem["creador"]  = intval($Dato["created_by"]) == 0 ? "N/A" : AccUsuario::ObtenerNombreCompletoById($Dato["created_by"]);
        $elem["status"]   = HtmlHelper::tagStatus($Dato);
        $elem["opciones"] = '<div class="table-data-feature">';


        if(in_array("R_REPORTE", session("permisos")))
        {
          $elem["opciones"].='
          <a href="#" class="item btnOpenPanelDetail" data-id="'.$Dato["id"].'" data-clave="'.$Dato["clave"].'"
                                                                                  title="Generar Reporte" data-title="'. $elem["nombre"] .'">
          <i class="zmdi zmdi-search"></i>
          </a>
          ';
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

  public function Finder(Request $request, $cClave)
  {
    if(in_array('R_REPORTE', session("permisos")))
    {
      $response = array();

      $response["title"] = $cClave;

      $response["mostrar_desde"] = false;
      $response["mostrar_hasta"] = false;

      $response["url"] = "/sistema/reportes/clave/".$cClave."/download/";

      switch($cClave)
      {
        case "REPORTE_CARNETS":
        {
          $response["mostrar_desde"] = true;
          $response["mostrar_hasta"] = true;

          break;
        }
        case "REPORTE_VENTA_PRODUCTOS":
        {
          break;
        }
        case "REPORTE_DESGLOSE":
        {
          $response["mostrar_desde"] = true;
          $response["mostrar_hasta"] = true;
          break;
        }
        case "REPORTE_RESULTADOS":
        {
          $objComision->Consultar();

          $response["arrComision"] = $objComision->ObtenerTodo();

          break;
        }
      }

      return view('sistema.reportes.reporte_finder', $response)->render();
    }

    abort(403);
  }

  public function Download(Request $request, $cClave)
  {
    if(in_array('R_REPORTE', session("permisos")))
    {
      $response = array();

      switch($cClave)
      {
        case "REPORTE_VENTA_BRAZALETES":
        {
          $cAux = "reporte_venta_brazaletes_" . $request->get("fecha_desde") . "_" . $request->get("fecha_hasta") . "_" . date("YmdHis");

          $data = array();

          $EncabezadoInsertado = false;

          $objVenta->Consultar("","","","",""," AND tipo='BRAZALETE' AND (created_at between '".$request->get("fecha_desde")." 00:00:00'
                                                                                          AND '".$request->get("fecha_hasta")." 23:59:59') ");

          while($objVenta->Recorrer())
          {
            $objVtaDetalle->Consultar("idventa",$objVenta->id);

            while($objVtaDetalle->Recorrer())
            {
              $micatalogo = array();
              $encabezado = array();

              array_push($encabezado,"codigo_venta");
              array_push($micatalogo,$objVenta->codigo);

              array_push($encabezado,"fecha_venta");
              array_push($micatalogo,$objVenta->created_at);

              array_push($encabezado,"tipo_brazalete");
              array_push($micatalogo,$objTipoBraz->MostrarCampoRegistro("nombre",$objVtaDetalle->idrelacionado));

              array_push($encabezado,"clave_descuento");
              array_push($micatalogo,$objVtaDetalle->clave_descuento);

              array_push($encabezado,"cantidad");
              array_push($micatalogo,$objVtaDetalle->cantidad);

              array_push($encabezado,"precio_unitario");
              array_push($micatalogo,$objVtaDetalle->precio_unitario);

              array_push($encabezado,"total");
              array_push($micatalogo,$objVtaDetalle->total);

              if($EncabezadoInsertado == false)
              {
                $data[] = $encabezado;
              	$EncabezadoInsertado = true;
              }

              $data[] = $micatalogo;
            }
          }

          Excel::create($cAux, function($excel) use($data) {
            $excel->sheet('Sheet 1', function($sheet) use($data) {
              $sheet->setOrientation('landscape');

              $sheet->fromArray($data, NULL, 'A1', false, false);
            });
          })->download('xlsx');

          break;
        }
        case "REPORTE_CARNETS":
        {
          $cTitulo = "reporte_carnets_del_" . slugify($request->get("fecha_desde")) . "_al_" . slugify($request->get("fecha_hasta"));

          $cSql = "
          SELECT
          tbl.*,
          1
          FROM
          cnc_inscripcion tbl
          WHERE 1=1
          AND ( tbl.created_at between '".$request->get("fecha_desde")." 00:00:00' AND '".$request->get("fecha_hasta")." 23:59:59' )
          ";

          $arrDato = objectToArray(DB::Select($cSql));

          foreach($arrDato as $Dato)
          {
            $micatalogo = array();
            $encabezado = array();

            $inscripcion = CncInscripcion::select()->withTrashed()->where("id", $Dato["id"])->first();

            array_push($encabezado, "No.Solicitud");
            array_push($micatalogo, $inscripcion->id);

            array_push($encabezado, "FechaCreación");
            array_push($micatalogo, $inscripcion->created_at);

            $cNombreCompleto = AccUsuario::ObtenerNombreCompletoById($inscripcion->usuario_id);

            array_push($encabezado,"Cliente");
            array_push($micatalogo, $cNombreCompleto);

            array_push($encabezado,"CorreoCliente");
            array_push($micatalogo, $inscripcion->usuario->user);

            array_push($encabezado,"TelefonoCliente");
            array_push($micatalogo, $inscripcion->usuario->telefono);

            array_push($encabezado,"RazonSocial");
            array_push($micatalogo, $inscripcion->razon_social);

            array_push($encabezado,"Rfc");
            array_push($micatalogo, $inscripcion->rfc);

            array_push($encabezado,"CorreoContactoFact");
            array_push($micatalogo, $inscripcion->correo_contacto);


            array_push($encabezado,"Socio");
            array_push($micatalogo, $inscripcion->socio);

            array_push($encabezado,"Sector/Delegación");

            $cAux = "";

            if($inscripcion->socio == "SECTOR")
            {
              $cAux = $inscripcion->sector->nombre;
            }
            elseif($inscripcion->socio == "DELEGACION")
            {
              $cAux = $inscripcion->delegacion->nombre . " - " . $inscripcion->delegacion->region->nombre;
            }

            array_push($micatalogo, $cAux);

            array_push($encabezado,"Carnet");
            array_push($micatalogo, $inscripcion->carnet->nombre);

            array_push($encabezado,"2doConvencionista");
            array_push($micatalogo, ($inscripcion->con_acompaniante == 1 ? "Si":"No") );

            array_push($encabezado,"Nombre2doConvencionista");
            array_push($micatalogo, $inscripcion->nombre_acompaniante);

            array_push($encabezado,"ModoLlegada");
            array_push($micatalogo, $inscripcion->modo_llegada);

            array_push($encabezado,"FechaLlegada");
            array_push($micatalogo, $inscripcion->fecha_llegada);

            array_push($encabezado,"HoraLlegada");
            array_push($micatalogo, $inscripcion->hora_llegada);

            array_push($encabezado,"VueloSalida");
            array_push($micatalogo, $inscripcion->vuelosalida->nombre);

            array_push($encabezado,"Hotel");
            array_push($micatalogo, $inscripcion->hotel->nombre);

            array_push($encabezado,"FormaPago");
            array_push($micatalogo, $inscripcion->forma_pago);

            array_push($encabezado,"Subtotal");
            array_push($micatalogo, $inscripcion->subtotal);

            array_push($encabezado,"Iva");
            array_push($micatalogo, $inscripcion->iva);

            array_push($encabezado,"Total");
            array_push($micatalogo, $inscripcion->total);

            array_push($encabezado,"EstadoPago");
            array_push($micatalogo, $inscripcion->etapa);

            array_push($encabezado,"OrdenConekta");

            $cAux = "";

            if($inscripcion->forma_pago == "PAGO_TARJETA" && $inscripcion->etapa == "PAGADO")
            {
              $cAux = $inscripcion->ordenconekta;
            }

            array_push($micatalogo, $cAux);

            if($EncabezadoInsertado == false)
            {
              $data[] = $encabezado;
            	$EncabezadoInsertado = true;
            }

            $data[] = $micatalogo;
          }

          Excel::create($cTitulo, function($excel) use ($data) {
            $excel->sheet('Sheet 1', function ($sheet) use ($data) {
              $sheet->setOrientation('landscape');
              $sheet->fromArray($data, NULL, 'A1', false, false);
            });
          })->download('xlsx');

          break;
        }
        case "REPORTE_DESGLOSE":
        {
          $cTitulo = "Desglose Ventas del " . $request->get("fecha_desde") . " al " . $request->get("fecha_hasta");

          $cAux = "reporte_desglose_" . $request->get("fecha_desde") . "_al_" . $request->get("fecha_hasta") . "_" . date("YmdHis");

          $data = array();

          $EncabezadoInsertado = false;

          $objVenta->Consultar("","","","",""," AND idtipo=1 AND (created_at between '".$request->get("fecha_desde")." 00:00:00'
                                                                                          AND '".$request->get("fecha_hasta")." 23:59:59') ");

          $dTotal = 0;

          while($objVenta->Recorrer())
          {
            $objBrazalete->Consultar("idventa",$objVenta->id);

            while($objBrazalete->Recorrer())
            {
              $micatalogo = array();
              $encabezado = array();

              array_push($encabezado,"descripcion");
              array_push($micatalogo, "BRAZALETE: ". $objTipoBraz->MostrarCampoRegistro("nombre",$objBrazalete->idtipo));

              array_push($encabezado,"codigo");
              array_push($micatalogo,$objBrazalete->codigo);

              $dPrecioBrazalete = doubleval($objVtaDetalle->MostrarCampoRegistro("precio_unitario",$objBrazalete->iddetalle));

              array_push($encabezado,"precio");
              array_push($micatalogo,$dPrecioBrazalete);

              $dTotal = $dTotal + $dPrecioBrazalete;

              if($EncabezadoInsertado == false)
              {
                $data[] = $encabezado;
              	$EncabezadoInsertado = true;
              }

              $data[] = $micatalogo;
            }
          }

          $data[] = array("","",$dTotal);

          Excel::create($cAux, function($excel) use($cTitulo, $data) {
            $excel->sheet('Sheet 1', function($sheet) use($cTitulo, $data) {
              $sheet->setOrientation('landscape');

              $sheet->fromArray(array(
                array($cTitulo),
              ), NULL, 'A1', false, false);

              $sheet->mergeCells('A1:H1');

              $sheet->cells('A1:H1', function($cells) {
                $cells->setAlignment('center');
              });

              $sheet->data = [];

              $sheet->fromArray($data, NULL, 'A2', false, false);
            });
          })->download('xlsx');

          break;
        }
        case "REPORTE_RESULTADOS":
        {
          $objComision->ConsultarRegistro($request->get("idcomision"));

          $cAux = "reporte_resultados_" . str_replace(" ","_",$objComision->nombre) . "_" . date("YmdHis");

          $data = array();

          $EncabezadoInsertado = false;

          $objAgenComi->ConsultarPorComision($objComision->id);

          $arrAux = $objAgenComi->ObtenerTodo();

          foreach($arrAux as $row)
          {
            $micatalogo = array();
            $encabezado = array();

            array_push($encabezado,"agencia");
            array_push($micatalogo,$row["nombre"]);

            array_push($encabezado,"comision");
            array_push($micatalogo,$row["porcentaje"]."%");

            array_push($encabezado,"email");
            array_push($micatalogo,$row["correo"]);

            $objEstado->ConsultarRegistro($row["idestado"]);

            array_push($encabezado,"estado");
            array_push($micatalogo, $objEstado->nombre);

            if($EncabezadoInsertado == false)
            {
              $data[] = $encabezado;
            	$EncabezadoInsertado = true;
            }

            $data[] = $micatalogo;
          }

          Excel::create($cAux, function($excel) use ($data) {
            $excel->sheet('Sheet 1', function ($sheet) use ($data) {
              $sheet->setOrientation('landscape');
              $sheet->fromArray($data, NULL, 'A1', false, false);
            });
          })->download('xlsx');

          break;
        }
      }

      return view('sistema.reportes.reporte_finder', $response)->render();
    }

    abort(403);
  }

}
<?php
namespace App\Http\Controllers\Cliente;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use App\Helpers\HtmlHelper;
use App\Helpers\Normalizador;
use App\Helpers\MensajeNotificacion;

use App\Models\AccUsuario;

use App\Models\SppPedido;
use App\Models\GenCategoria;



class PedidosController extends MController
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index(Request $request)
  {
    $response = array();

    $objPedido  = new SppPedido;

    return view('cliente.pages.pedidos.pedido_list', $response)->render();
  }

  public function show(Request $request, $id)
  {
    $response = array();

    $data = FbzPedido::where("es_carrito",0)->where("idsadcliente",session("cliente_id"))->where("id",$id)->first();

    /*$bAux = MensajeNotificacion::EnviarPedido($id, true);

    exit(var_dump($bAux));*/

    if($data)
    {
      $response['data'] = $data;

      return view('cliente.pedidos.pedido_detail', $response)->render();
    }

    abort(404);
  }

  public function colonias_json($idestado, $idmunicipio)
  {
    $response = array();

    $response["data"] = array();

    $objEstado    = new LocEstado;
    $objMunicipio = new LocMunicipio;
    $objFbazar    = new FbazarService();

    $estado    = $objEstado->find($idestado);
    $municipio = $objMunicipio->find($idmunicipio);

    if($estado && $municipio)
    {
      $apiResponse = $objFbazar->get_colonias($estado->clave, $municipio->clave);

      if($apiResponse["code"] == 200)
      {
        $response["data"] = $apiResponse["data"];
      }
    }

    return response()->json($response);
  }

  public function obtenerJsonColoniaByCodigoPostal($codigoPostal)
  {
    $response = array();

    $response["data"] = array();

    $objColonia = new LocColonia;

    $data = array();

    $colonia = $objColonia->where("codigo_postal",$codigoPostal)->first();

    if($colonia)
    {
      $data = $colonia;
    }

    $response["data"] = $data;

    return response()->json($response);
  }

  public function generarpedido(Request $request, $id)
  {
    $response = array();

    $objPedido = new FbzPedido;
    $objFbazar = new FbazarService();

    $objTmpDisp = new TmpDisponibilidad;
    $objTmpAsig = new TmpAsignacion;

    $pedido = $objPedido->whereId($id)->first();

    $cFormaEntrega = $request->get("formaentrega") ?? ""; # domicilio | farmacia
    $iddireccion   = $request->get("iddireccion") ?? "";
    $iFormaPago    = intval(request()->get("formapago"));
    $iCodSucursal  = intval(request()->get("codigoSucursal"));

    $apiResponse = $objFbazar->get_cliente_ecommerce_by_email(session("cliente_email"));
    $cliente     = $apiResponse["data"];

    $direccionCliente    = array();
    $direccionClienteAux = array();

    if(isset($cliente["direccion"]) && !empty($cliente["direccion"]))
    {
      foreach($cliente["direccion"] as $item)
      {
        if(empty($direccionClienteAux))
        {
          $direccionClienteAux = $item;
        }

        if($item["id"] == $iddireccion)
        {
          $direccionCliente = $item;
        }
      }
    }

    if(empty($direccionCliente))
    {
      $direccionCliente = $direccionClienteAux;
    }

    if(!empty($direccionCliente))
    {
      if($pedido && $pedido->es_carrito == 1)
      {
        $Pedido = array();

        $detallesPedido = $pedido->detalles;
        $iTotProdDet    = $detallesPedido->count();

        $dLatitud  = $direccionCliente["latitud"];
        $dLongitud = $direccionCliente["longitud"];

        if($cFormaEntrega == "farmacia")
        {
          $apiResponse = $objFbazar->obtener_sucursal_por_codigo($iCodSucursal);

          if($apiResponse["code"] == 200)
          {
            $farmaciaAEntregar = $apiResponse["data"];

            $dLatitud  = $farmaciaAEntregar["latitud"];
            $dLongitud = $farmaciaAEntregar["longitud"];
          }
        }

        $apiResponse = $objFbazar->obtener_sucursales_cercanas($dLatitud, $dLongitud);

        if($apiResponse["code"] == 200)
        {
          $arrSucursalCercana = $apiResponse["data"];

          $arrSucursalCercana = sortAssociativeArrayByKey($arrSucursalCercana, "pedidosPendientes", "asc");

          $objTmpDisp->where("idpedido", $id)->delete();

          $arrSucursalPromesa = array();

          foreach($arrSucursalCercana as $SucursalCercana)
          {
            foreach($detallesPedido as $detalle)
            {
              $apiResponse = $objFbazar->obtener_existencias_producto_por_sucursal($SucursalCercana["codigoSucursal"], $detalle->codigoProducto);

              if($apiResponse["code"] == 200)
              {
                $data = $apiResponse["data"][0];

                if(!empty($data))
                {
                  $dato = array();

                  $dato["idpedido"]          = $id;
                  $dato["codigoSucursal"]    = $data["codigoSucursal"];
                  $dato["codigoProducto"]    = $data["codigoProducto"];
                  $dato["kilometros"]        = $SucursalCercana["kilometros"];
                  $dato["pedidosPendientes"] = $SucursalCercana["pedidosPendientes"];
                  $dato["existencia"]        = $data["existencia"];

                  $objTmpDisp->insert($dato);
                }
              }
            }
          }

          $aux = $objTmpDisp->where("idpedido", $id)->get();

          if($aux->count() > 0)
          {
            $iMaxFarm = get_config_db("LIMITE_SUCURSALES_CALCULO_DISPONIBILIDAD") ?: 1;

            $iMaxFarm = intval($iMaxFarm);

            $cSql = "
            SELECT tmpd.codigoSucursal, COUNT(tmpd.id) AS total, SUM(tmpd.existencia) AS existencia, tmpd.pedidosPendientes, tmpd.kilometros
            FROM tmp_disponibilidad tmpd
            WHERE tmpd.idpedido='".$id."'
            GROUP BY tmpd.codigoSucursal
            ORDER BY total DESC, pedidosPendientes ASC, kilometros ASC, existencia DESC
            LIMIT $iMaxFarm
            ";

            $objTmpAsig->where("idpedido",$id)->delete();

            $results = DB::select(DB::raw($cSql));

            $codigoSucPromesa = 0;

            foreach($results as $item)
            {
              if($codigoSucPromesa == 0)
              {
                $codigoSucPromesa = $item->codigoSucursal;
              }

              $disponibles = $objTmpDisp->where("idpedido", $id)->where("codigoSucursal", $item->codigoSucursal)->get();

              foreach($disponibles as $disponible)
              {
                $asig = $objTmpAsig->where("idpedido", $id)
                            ->where("codigoProducto", $disponible->codigoProducto)->first();

                if($asig)
                {
                  $asig->existencia = $asig->existencia + $disponible->existencia;

                  $asig->save();
                }
                else
                {
                  $tmp = array();

                  $tmp["idpedido"]       = $disponible->idpedido;
                  $tmp["codigoProducto"] = $disponible->codigoProducto;
                  $tmp["existencia"]     = $disponible->existencia;

                  $objTmpAsig->insert($tmp);
                }
              }
            }

            #######################################

            $iAux = 0;

            $arrAux = array();

            foreach($detallesPedido as $detalle)
            {
              $asig = $objTmpAsig->where("idpedido",$id)->where("codigoProducto",$detalle->codigoProducto)->first();

              if($asig)
              {
                $arrAux[] = array("codigoProducto"=>$asig->codigoProducto, "existencia"=>$asig->existencia);

                if($detalle->cantidad > 0 && $detalle->cantidad <= $asig->existencia)
                {
                  $iAux = $iAux + 1;
                }
              }
            }

            if($iAux == $iTotProdDet)
            {
              $Pedido = array();

              $apiResponse = $objFbazar->obtener_sucursal_por_codigo($codigoSucPromesa);
              $sucursal = $apiResponse["data"];

              $apiResponse = $objFbazar->get_colonia_by_codigo($direccionCliente["codigoColonia"]);
              $colonia = $apiResponse["data"];

              # generar direccion cliente
              {
                $cDireccionCliente = "";

                $arrAux = array();

                if(!empty($cAux = $direccionCliente["calle"]))
                  $arrAux[] = "Calle: " . trim($cAux);

                if(!empty($cAux = $direccionCliente["numeroExterior"]))
                  $arrAux[] = "NoExt: " . trim($cAux);

                if(!empty($cAux = $direccionCliente["numeroInterior"]))
                  $arrAux[] = "NoInt: " . trim($cAux);

                if(!empty($cAux = $direccionCliente["entre"]))
                  $arrAux[] = "Cruzamientos: " . trim($cAux);

                if(!empty($cAux = $colonia["descripcion"]))
                  $arrAux[] = trim($cAux);

                if(!empty($cAux = $colonia["municipio"]))
                  $arrAux[] = trim($cAux);

                if(!empty($cAux = $direccionCliente["codigoPostal"]))
                  $arrAux[] = "c.p. " . trim($cAux);

                if(!empty($arrAux))
                {
                  $pedido->domicilioCliente = implode(", ", $arrAux);
                  $pedido->save();
                }
              }

              $arrDetalle = array();

              if($cFormaEntrega == "farmacia")
              {
                $sucursal = $farmaciaAEntregar;
              }

              /*$Pedido["clienteId"]              = session("cliente_id");*/
              $Pedido["clienteId"]              = $direccionCliente['clienteId'];
              $Pedido["clienteDireccionId"]     = $direccionCliente['id'];
              $Pedido["cliente"]                = strtoupper($cliente["name"]);
              $Pedido["codigoSucursalAsignado"] = intval($sucursal["codigoSucursal"]);
              $Pedido["sucursalAsignado"]       = strval($sucursal["descripcion"]);
              $Pedido["codigoColonia"]          = $direccionCliente["codigoColonia"];
              $Pedido["colonia"]                = strval($colonia["descripcion"]);
              $Pedido["horaRegistro"]           = strval(nowtz());
              /*$Pedido["subTotal"]               = get_numeric($pedido->subtotal);*/
              $Pedido["subTotal"]               = 0;
              /*$Pedido["iva"]                    = get_numeric($pedido->iva);*/
              $Pedido["iva"]                    = 0;
              $Pedido["total"]                  = get_numeric($pedido->total);
              $Pedido["descuento"]              = 0;
              $Pedido["codigoStatus"]           = 1;
              $Pedido["status"]                 = "Recibido";
              $Pedido["codigoCanalVenta"]       = 5;
              $Pedido["codigoClienteSucursal"]  = 0;
              $Pedido["folioPedidoSucursal"]    = "";
              $Pedido["tipoEnvio"]              = $cFormaEntrega == "domicilio" ? "L" : "R";
              $Pedido["codigoFormaPago"]        = $iFormaPago;
              /*$Pedido["documento"]              = "";
              $Pedido["autorizacion"]           = "";*/

              $Pedido["importePago"]            = $pedido->total;

              if(request()->get("formapago") == 1)
              {
                $dAux = get_numeric(request()->get("supago"));

                if($dAux >= $pedido->total)
                {
                  $Pedido["importePago"] = $dAux;
                }
              }

              foreach($detallesPedido as $detalle)
              {
                $Detalle = array();

                $Detalle["codigoProducto"]      = intval($detalle->codigoProducto);
                $Detalle["producto"]            = strval($detalle->nombre);
                $Detalle["codigoRelacionado"]   = strval($detalle->codigoRelacionado);
                $Detalle["cantidad"]            = intval($detalle->cantidad);
                $Detalle["precioUnitario"]      = floatval($detalle->precio);
                $Detalle["costoUnitario"]       = floatval($detalle->costoUnitario);
                $Detalle["porcentajeDescuento"] = floatval($detalle->porcDescuento);
                $Detalle["importeDescuento"]    = 0;
                $Detalle["codigoDepartamento"]  = intval($detalle->codigoDepartamento);
                $Detalle["tasaIva"]             = intval($detalle->tasaIva);
                $Detalle["importeIva"]          = 0;
                $Detalle["tipoDescuento"]       = "P";

                $arrDetalle[] = $Detalle;
              }

              $Pedido["pedidosDet"] = $arrDetalle;

              /*exit(json_encode($Pedido));*/

              $apiResponse = $objFbazar->crear_pedido($Pedido);

              /*exit(var_dump($apiResponse));*/

              if($apiResponse["code"] == 200)
              {
                $data = $apiResponse["data"];

                $id = intval(after("Pedido:",$data));

                $apiResponse = $objFbazar->obtener_pedido_por_id($id);
                $sadPedido   = $apiResponse["data"];

                $cFormaPago = "";

                switch($iFormaPago)
                {
                  case 1:
                  $cFormaPago = "Efectivo";
                  break;
                  case 4:
                  $cFormaPago = "Tarjeta Débito";
                  break;
                  case 5:
                  $cFormaPago = "Tarjeta Crédito";
                  break;
                }

                $arrAux = [];

                if(!empty($cAux = $sucursal["direccion"]))
                  $arrAux[] = trim($cAux);

                if(!empty($cAux = $sucursal["colonia"]))
                  $arrAux[] = trim($cAux);

                if(!empty($cAux = $sucursal["ciudad"]))
                  $arrAux[] = trim($cAux);

                if(!empty($cAux = $sucursal["municipio"]))
                  $arrAux[] = trim($cAux);

                if(!empty($cAux = $sucursal["estado"]))
                  $arrAux[] = trim($cAux);

                if(!empty($cAux = $sucursal["codigoPostal"]))
                  $arrAux[] = "c.p. " . trim($cAux);

                $pedido->idsadcliente           = session("cliente_id");
                $pedido->idsadpedido            = $sadPedido["id"];
                $pedido->codigoGuid             = $sadPedido["codigoGuid"];
                $pedido->es_carrito             = 0;
                $pedido->nombreCliente          = strtoupper(session("cliente_nombre"));
                $pedido->formaEntrega           = ucfirst(strtolower(request()->get("formaentrega")));
                $pedido->codigoSucursalAsignado = $sadPedido["codigoSucursalAsignado"];
                $pedido->sucursalAsignado       = $sadPedido["sucursalAsignado"];
                $pedido->dirSucursalAsignado    = implode(", ", $arrAux);
                $pedido->formaPago              = $cFormaPago;
                $pedido->created_at             = now();

                $pedido->save();

                MensajeNotificacion::EnviarPedido($pedido->id, true);

                $response["success"] = true;
                $response["message"] = "Pedido Realizado Correctamente.";
                $response["data"]    = $sadPedido;
                $response["id"]      = $pedido->id;
              }
              else
              {
                $response["message"] = "No fue posible realizar el pedido favor de volver a intentar.";
              }
            }
            else
            {
              $response["message"] = "Es necesario realizar un ajuste en la cantidad de sus productos, para finalizar el pedido.";
              $response["json64"]  = base64_encode(json_encode($arrAux));
            }
          }
          else
          {
            $response["message"] = "Ninguna farmacia puede surtir tus productos seleccionados.";
          }
        }
        else
        {
          $response["message"] = "No hay farmacias cerca de su dirección.";
        }
      }
      else
      {
        $response["message"] = "Este carrito ya ha sido pagado.";
      }
    }
    else
    {
      $response["message"] = "No hay una dirección de entrega valida.";
    }

    return response()->json($response);
  }

  public function pdf(Request $request, $id)
  {
    $response = array();

    $objPedido = new FbzPedido;
    $objFbazar = new FbazarService();

    $data = $objPedido->where("es_carrito",0)->where("idsadcliente",session("cliente_id"))->where("id",$id)->first();

    if($data)
    {
      $mpdf = new \Mpdf\Mpdf();

      $mpdf->SetHeader('Farmacias Bazar'.'||');
      $mpdf->SetFooter('||{PAGENO}');

      $stylesheet = config("app.env") == 'production' ? get_asset('/static/generico/css/pdf12col.css') : './static/generico/css/pdf12col.css';

      $stylesheet = file_get_contents($stylesheet);

      $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);

      $out = '';

      $response = array();

      $response["data"] = $data;

      if($data->formaEntrega == "Farmacia")
      {
        $apiResponse = $objFbazar->obtener_sucursal_por_codigo($data->codigoSucursalAsignado);

        if($apiResponse["code"] == 200)
        {
          $farmacia = $apiResponse["data"];

          $arrAux = array();

          if(!empty($cAux = $farmacia["descripcion"]))
            $data->farmaciaNombre = $cAux;

          if(!empty($cAux = $farmacia["direccion"]))
            $arrAux[] = trim($cAux);

          if(!empty($cAux = $farmacia["colonia"]))
            $arrAux[] = trim($cAux);

          if(!empty($cAux = $farmacia["ciudad"]))
            $arrAux[] = trim($cAux);

          if(!empty($cAux = $farmacia["estado"]))
            $arrAux[] = trim($cAux);

          if(!empty($cAux = $farmacia["codigoPostal"]))
            $arrAux[] = "c.p. " . trim($cAux);

          $data->domicilioCliente = implode(", ", $arrAux);
        }
      }

      $out.= view('cliente.pedidos.pedido_pdf')->with($response)->render();

      $mpdf->shrink_tables_to_fit = 0;

      $mpdf->WriteHTML($out);

      $out = "pedido_" . $data->idsadpedido;
      $out = $out."_".date("YmdHi");

      $mpdf->Output($out.'.pdf', (request()->get("download") == 'yes' ? 'D':'I'));
    }

    abort(404);
  }

}
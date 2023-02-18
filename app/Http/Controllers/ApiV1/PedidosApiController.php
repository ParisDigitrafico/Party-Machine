<?php
namespace App\Http\Controllers\ApiV1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use App\Helpers\SitioWeb;

use App\Models\SppPedido;
use App\Models\SppPedidoDetalle;


class PedidosApiController extends MController
{
  public function __construct()
  {
    parent::__construct();
  }

  public function store()
  {
    $response = array();

    $iStatus = 404;

    $data = array();

    $data["ckey"] = md5(now());

    $objBase = new SppPedido;

    if($objBase->insert($data))
    {
      $data = $objBase->latest()->first();

      $response["data"] = $data;

      $iStatus = 201;
    }

    return response()->json($response, $iStatus);
  }

  public function show($idpedido)
  {
    $objBase = new SppPedido;

    $query = $objBase->where("id", $idpedido);

    $record = $query->first();

    $code = "404";

    if($record)
    {
      $code = "200";

      $data = array();

      $data = objectToArray($record);

      $arrAux = objectToArray($record->detalles);

      if(!empty($arrAux))
      {
        $aux = array();

        foreach($arrAux as $item)
        {
          $item["imagenUrl"] = get_const("APP_URL") . "/static/generico/images/404.jpg";

          $aux[] = $item;
        }

        $data["detalle"] = $aux;
      }
    }

    return response()->json($data, $code);
  }

  public function updatePedido($idpedido)
  {

  }

  public function updateProduct($idpedido, $codigoProducto, $cantidad)
  {
  }

  public function addProduct($pedido_id, $cantidad)
  {
    $response = array();

    $iCode = "400";

    $objPedido = new SppPedido;
    $objPedDet = new SppPedidoDetalle;

    $objFbazar = new FbazarService();

    $query = $objPedido->where("id", $idpedido);

    $pedido = $query->first();

    if($pedido)
    {
      $detalle = $objPedDet->where("idpedido", $idpedido)->where("codigoProducto", $codigoProducto)->first();

      if($detalle)
      {
        $data = array();

        $data["cantidad"] = intval($detalle->cantidad) + intval($cantidad);
        $data["total"]    = $detalle->precio * $data["cantidad"];

        $objPedDet->where("id", $detalle->id)->update($data);
      }
      else
      {
        $apiResponse = $objFbazar->get_producto_bazar_bycode($codigoProducto);

        $producto = $apiResponse["data"];

        if(!empty($producto))
        {
          $data = array();

          $data["idpedido"]          = $idpedido;
          $data["codigoProducto"]    = $codigoProducto;
          $data["codigoRelacionado"] = $producto["codigoRelacionado"];
          $data["nombre"]            = $producto["descripcionCorta"];
          $data["precio"]            = $producto["precioVenta"];
          $data["cantidad"]          = intval($cantidad);
          $data["total"]             = $data["cantidad"] * $data["precio"];

          $objPedDet->insert($data);

          $detalle = $objPedDet->latest()->first();
        }
      }

      $objPedido->ActualizarTotalesPorId($idpedido);

      $pedido = $query->first();

      $response["data"] = objectToArray($pedido);

      $iCode = 202;
    }

    return response()->json($response, $iCode);
  }

  public function showPedidoByCKey($ckey)
  {
    $response = array();

    $objBase = new SppPedido;

    $query = $objBase->where("ckey", $ckey)->where("es_venta",1);

    $item = $query->first();

    $iStatus = 404;

    if($item)
    {
      $iStatus = 200;

      $data = array();

      $data = objectToArray($item);

      $arrAux = objectToArray($item->detalles);

      if(!empty($arrAux))
      {
        $aux = array();

        foreach($arrAux as $item)
        {
          $item["imagenUrl"] = get_const("APP_URL") . "/static/generico/images/404.jpg";

          $aux[] = $item;
        }

        $data["detalle"] = $aux;
      }

      $response["data"] = $data;
    }

    $response["status"] = $iStatus;

    return response()->json($response);
  }

  public function addProductoCantidadByCKey($ckey, $producto_id, $cantidad)
  {
    $response = array();

    $response["code"] = 404;

    $objPedido = new SppPedido();
    $objPedDet = new SppPedidoDetalle();


    $query = $objPedido->where("ckey", $ckey)->where("es_venta", 1)->orderBy("id","desc");

    $pedido = $query->first();

    if($pedido)
    {
      $pedido_id = $pedido->id;

      $pedido_det = $pedido->detalles()->where("producto_id", $producto_id)->first();

      if($pedido_det)
      {
        $response["code"] = 200;

        $data = array();

        $pedido_det->cantidad = intval($pedido_det->cantidad) + intval($cantidad);

        $pedido_det->save();
      }
      else
      {
        /*$apiResponse = $objFbazar->get_producto_bazar_bycode($codigoProducto);*/
        $apiResponse = $objFbazar->get_producto_ecommerce_byid($idProducto);

        $producto = $apiResponse["data"];

        if(!empty($producto))
        {
          $data = array();

          $data["idpedido"]          = $idpedido;
          $data["idProducto"]        = $idProducto;
          $data["codigoProducto"]    = $producto["codigoProducto"];
          $data["codigoRelacionado"] = $producto["codigoRelacionado"];
          $data["nombre"]            = $producto["descripcion"];
          $data["precio"]            = $producto["precioVenta"];
          $data["cantidad"]          = intval($cantidad);

          $data["precioUnitario"]     = $producto["precioUnitario"];
          $data["costoUnitario"]      = $producto["costoUnitario"];
          $data["codigoDepartamento"] = $producto["codigoDepartamento"];
          $data["tasaIva"]            = $producto["tasaIva"];
          $data["porcDescuento"]      = $producto["porcDescuento"];
          $data["precioVenta"]        = $producto["precioVenta"];

          $data["delivery"] = $producto["delivery"] == true ? 1:0;
          $data["pickUp"]   = $producto["pickUp"] == true ? 1:0;

          $pedido_det_id = $objPedDet->insertGetId($data);

          if($pedido_det_id)
          {
            $response["code"] = 201;
          }
        }
      }

      $objPedido->ActualizarTotalesPorId($idpedido);

      $pedido = $query->first();

      $response["success"] = true;
      $response["data"]    = objectToArray($pedido);
    }

    return response()->json($response);
  }

  public function updateProductoCantidadByCKey($ckey, $idProducto, $cantidad)
  {
    $response = array();

    $response["code"] = 404;

    $objPedido = new SppPedido;
    $objPedDet = new FbzPedidoDetalle;

    $objFbazar = new FbazarService();

    $pedido = $objPedido->where("ckey", $ckey)->where("es_carrito",1)->orderBy("id","desc")->first();

    if($pedido)
    {
      $pedido_det = $pedido->detalles->where("idProducto", $idProducto)->first();

      if($pedido_det)
      {
        $pedido_det->cantidad = intval($cantidad);

        $pedido_det->save();

        $response["success"] = true;

        $response["code"] = 200;
      }

      $objPedido->ActualizarTotalesPorId($pedido->id);
    }

    return response()->json($response);
  }

  public function removeProductByCKey($ckey, $idProducto)
  {
    $response = array();

    $response['status'] = 404;

    $objPedido = new FbzPedido;
    $objPedDet = new FbzPedidoDetalle;

    $pedido = $objPedido->where("ckey",$ckey)->where("es_carrito",1)->orderBy("id","desc")->first();

    if($pedido)
    {
      $pedido_det = $pedido->detalles()->where("idProducto",$idProducto)->first();

      if($pedido_det)
      {
        $pedido_det->delete();

        $objPedido->ActualizarTotalesPorId($pedido->id);

        $response["success"] = true;

        $response['status'] = 204;
      }
    }

    return response()->json($response);
  }

  public function updateArrProductoCantidadByCKey(Request $request, $ckey)
  {
    $response = array();

    $objApiPed = new self();
    $objPedido = new FbzPedido();

    $pedido = $objPedido->where("ckey",$ckey)->where("es_carrito",1)->orderBy("id","desc")->first();

    if($pedido)
    {
      if($request->has("aux"))
      {
        $aux = $request->get("aux");

        foreach($aux as $elem)
        {
          if($elem["existencia"] > 0)
          {
            $objApiPed->updateProductoCantidadByCKey($ckey, $elem["codigoProducto"], $elem["existencia"]);
          }
          else
          {
            $objApiPed->removeProductByCKey($ckey, $elem["codigoProducto"]);
          }
        }

        $response["success"] = true;
        $response["url"]     = "/carrito/". $ckey ."/resumen/";
      }


    }

    return response()->json($response);
  }

}
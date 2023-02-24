<?php
namespace App\Http\Controllers\ApiV1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use App\Helpers\SitioWeb;

use App\Models\SppPedido;
use App\Models\SppPedidoDetalle;
use App\Models\SppProducto;


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

    $objPedido = new SppPedido;
    $objPedDet = new SppPedidoDetalle;

    $query = $objPedido->where("id", $pedido_id);

    $pedido = $query->first();

    if($pedido)
    {
      $detalle = $objPedDet->where("pedido_id", $pedido_id)->where("id", $id)->first();

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
    }

    return response()->json($response);
  }

  public function showPedidoByCKey($ckey)
  {
    $response = array();

    $objBase = new SppPedido;

    $query = $objBase->where("ckey", $ckey)->where("es_pagado",0);

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

  public function addProductoCantidadByCKey(Request $request, $ckey, $producto_id, $cantidad)
  {
    $response = array();

    $response["status"] = 404;

    $objPedido   = new SppPedido();
    $objPedDet   = new SppPedidoDetalle();
    $objProducto = new SppProducto();

    $query = $objPedido->where("ckey", $ckey)->where("es_pagado", 0)->orderBy("id","desc");

    $pedido = $query->first();

    if($pedido)
    {
      $pedido_det = $pedido->detalles()->where("producto_id", $producto_id)->first();

      if($pedido_det)
      {
        /*$data = array();

        $pedido_det->cantidad = intval($pedido_det->cantidad) + intval($cantidad);

        $pedido_det->save();

        $response["status"]  = 200;
        $response["success"] = true;*/

        $response["status"]  = 200;
        $response["message"] = 'Ya tiene un producto de este tipo.';
      }
      else
      {
        $producto = $objProducto->whereId($producto_id)->first();

        if($producto)
        {
          $arrIdProducto = [];

          $productos_tipo = $objProducto->where('tipo', $producto->tipo)->get();

          foreach($productos_tipo as $item)
          {
            $arrIdProducto[] = $item->id;
          }

          $pedidos_det = $pedido->detalles()->whereIn('producto_id',$arrIdProducto)->get();

          if($pedidos_det->count() > 0)
          {
            $response["message"] = 'Ya tiene un producto de este tipo.';
          }
          else
          {
            $data = array();

            $data["pedido_id"]       = $pedido->id;
            $data["producto_id"]     = $producto->id;
            $data["producto_nombre"] = $producto->nombre;
            $data["precio"]          = $producto->precio;
            $data["cantidad"]        = intval($cantidad);

            $pedido_det_id = $objPedDet->insertGetId($data);

            if($pedido_det_id)
            {
              $response["status"]  = 201;
              $response["success"] = true;
            }
          }
        }
      }

      $objPedido->ActualizarTotalesPorId($pedido->id);

      $pedido = $query->first();

      $response["data"] = objectToArray($pedido);
    }

    return response()->json($response);
  }

  public function updateProductoCantidadByCKey($ckey, $producto_id, $cantidad)
  {
    $response = array();

    $response["code"] = 404;

    $objPedido = new SppPedido;
    $objPedDet = new SppPedidoDetalle;

    $pedido = $objPedido->where("ckey", $ckey)->where("es_pagado",0)->orderBy("id","desc")->first();

    if($pedido)
    {
      $pedido_det = $pedido->detalles->where("producto_id", $producto_id)->first();

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

  public function removeProductByCKey(Request $request, $ckey, $producto_id)
  {
    $response = array();

    $response['status'] = 404;

    $objPedido = new SppPedido;
    $objPedDet = new SppPedidoDetalle;

    $pedido = $objPedido->where("ckey",$ckey)->where("es_pagado",0)->orderBy("id","desc")->first();

    if($pedido)
    {
      $pedido_det = $pedido->detalles()->where("producto_id",$producto_id)->first();

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
    $objPedido = new SppPedido();

    $pedido = $objPedido->where("ckey",$ckey)->where("es_pagado",1)->orderBy("id","desc")->first();

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
<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SppPedido extends SuperModel
{
  protected $table      = 'spp_pedido';
  protected $primaryKey = 'id';

  public function detalles()
  {
    return $this->hasMany(SppPedidoDetalle::class, 'pedido_id');
  }

  public function ActualizarTotalesPorId($pedido_id)
  {
    $response = false;

    $objPedido = new self();
    $objPedDet = new SppPedidoDetalle();

    $pedido = $objPedido->whereId($pedido_id)->first();

    if($pedido)
    {
      $iTotalCantidad = 0;
      $dTotalFinal    = 0;

      $detalles = $objPedDet->where("pedido_id", $pedido_id)->get();

      if($detalles->count() > 0)
      {
        foreach($detalles as $pedido_det)
        {
          $pedido_det->updated_at = now();

          $pedido_det->total    = $pedido_det->precio * $pedido_det->cantidad;
          /*$pedido_det->subtotal = $pedido_det->total;  */

          $pedido_det->save();
        }
      }

      $detalles = $objPedDet->where("pedido_id", $pedido_id)->get();

      if($detalles->count() > 0)
      {
        foreach($detalles as $pedido_det)
        {
          $iTotalCantidad+= $pedido_det->cantidad;
          /*$dTotalSubtotal+= $pedido_det->subtotal;
          $dTotalIva+= $pedido_det->totalIva;
          $dTotalDesc+= $pedido_det->totalDescuento;*/
          $dTotalFinal+= $pedido_det->total;
        }
      }

      $pedido->cantidad       = $iTotalCantidad;
      /*$pedido->subtotal       = $dTotalSubtotal;
      $pedido->totalIva       = $dTotalIva;
      $pedido->totalDescuento = $dTotalDesc;*/
      $pedido->total          = $dTotalFinal;

      $pedido->save();

      $response = true;
    }

    return $response;
  }

}
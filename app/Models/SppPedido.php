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

      $arrDetalle = $objPedDet->where("pedido_id", $pedido_id)->get();

      if(count($arrDetalle) > 0)
      {
        foreach($arrDetalle as $Detalles)
        {
          $iTotalCantidad+= $Detalles->cantidad;
          $dTotalFinal+= $Detalles->total;
        }
      }

      $pedido->cantidad = $iTotalCantidad;
      $pedido->total    = $dTotalFinal;
      #$pedido->iva      = $dTotalFinal * (16 / 100);
      #$pedido->subtotal = $pedido->total - $pedido->iva;

      $pedido->save();
    }

    return $response;
  }

}
<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SppPedido extends SuperModel
{
  protected $table      = 'spp_pedido';
  protected $primaryKey = 'id';

  public function detalle()
  {
    /*return $this->belongsTo(SppPedidoDetalle::class, 'idpedido');*/
    return $this->hasMany(SppPedidoDetalle::class, 'idpedido');
  }

  public function ActualizarTotalesPorId($idpedido)
  {
    $response = false;

    $objPedido = new self;
    $objPedDet = new SppPedidoDetalle;

    $pedido = $objPedido->find($idpedido);

    if($pedido)
    {
      $iTotalCantidad = 0;
      $dTotalFinal    = 0;

      $arrDetalle = $objPedDet->where("idpedido", $idpedido)->get();

      if(count($arrDetalle) > 0)
      {
        foreach($arrDetalle as $Detalle)
        {
          $iTotalCantidad+= $Detalle->cantidad;
          $dTotalFinal+= $Detalle->total;
        }
      }

      $pedido->cantidad = $iTotalCantidad;
      $pedido->total    = $dTotalFinal;
      $pedido->iva      = $dTotalFinal * (16 / 100);
      $pedido->subtotal = $pedido->total - $pedido->iva;

      $pedido->save();
    }

    return $response;
  }

}
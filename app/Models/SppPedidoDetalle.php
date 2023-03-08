<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SppPedidoDetalle extends Model
{
  protected $table      = 'spp_pedido_detalle';
  protected $primaryKey = 'id';

  public function producto()
  {
    return $this->belongsTo(SppProducto::class, 'producto_id');
  }
}
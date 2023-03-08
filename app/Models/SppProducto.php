<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SppProducto extends SuperModel
{
  protected $table      = 'spp_producto';
  protected $primaryKey = 'id';

  protected $columnRelArchivo = "producto_id";

  public function categoria()
  {
    return $this->hasOne(GenCategoria::class, "id", "categoria_id");
  }

  public function obtenerArrIdCategoriaPorTipo($cTipo='plantilla')
  {
    $response = array();

    $objProducto = new SppProducto();

    $productos = $objProducto->filterActivos()->where('tipo',$cTipo)->get();

    if($productos->count() > 0)
    {
      foreach($productos as $producto)
      {
        $response[] = $producto->categoria_id;
      }

      $response = array_unique($response);
    }

    return $response;
  }


}

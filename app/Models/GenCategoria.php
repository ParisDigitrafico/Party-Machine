<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GenCategoria extends SuperModel
{
  protected $table      = 'gen_categoria';
  protected $primaryKey = 'id';
  
  protected $columnRelArchivo = "categoria_id";

  function productos()
  {
    return $this->hasMany(SppProducto::class, 'categoria_id');
  }
}
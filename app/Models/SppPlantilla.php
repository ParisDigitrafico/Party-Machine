<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SppPlantilla extends SuperModel
{
  protected $table      = 'spp_plantilla';
  protected $primaryKey = 'id';

  protected $columnRelArchivo = "plantilla_id";

  public function categorias()
  {
    return $this->hasOne(GenCategoria::class, "id", "categoria_id");
  }
}

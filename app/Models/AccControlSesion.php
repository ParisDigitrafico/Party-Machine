<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccControlSesion extends Model
{
  protected $table      = 'acc_control_sesion';
  protected $primaryKey = 'id';

  public function Existe($usuario_id, $app, $medio)
  {
    $response = false;

    $objSelf = new self();

    $usuario_id = $usuario_id ?? "";
    $app        = $app ?? "";
    $medio      = $medio ?? "";
    
    $record = $objSelf->select()->where("usuario_id", $usuario_id)->where("app", $app)->where("medio", $medio)->first();

    if(!is_null($record))
    {
      $response = true;
    }

    return $response;
  }
}
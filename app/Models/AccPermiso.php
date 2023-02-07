<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccPermiso extends SuperModel
{
  protected $table      = 'acc_permiso';
  protected $primaryKey = 'id';

  public function perfiles()
  {
    return $this->belongsToMany(AccPerfil::class, 'acc_perfil_permiso', 'permiso_id', 'perfil_id');
  }

  static function ConsultarPermisosSuperUsuario()
  {
    $cSql = "
    SELECT DISTINCT
    	acc_permiso.*
    FROM
    	acc_permiso
    WHERE 1=1
    	AND acc_permiso.status = 1
    	AND LENGTH( CHAR ( acc_permiso.deleted_at )) = 0
    ";

    return DB::select($cSql);
  }

  static function ConsultarPermisosPorUsuario($usuario_id)
  {
    $cSql = "
    SELECT DISTINCT
    	acc_permiso.*
    FROM
    	acc_usuario
    	INNER JOIN acc_usuario_perfil ON acc_usuario.id = acc_usuario_perfil.usuario_id
    	INNER JOIN acc_perfil ON acc_usuario_perfil.perfil_id = acc_perfil.id
    	INNER JOIN acc_perfil_permiso ON acc_perfil.id = acc_perfil_permiso.perfil_id
    	INNER JOIN acc_permiso ON acc_perfil_permiso.permiso_id = acc_permiso.id
    WHERE 1
    	AND acc_usuario.status = 1
    	AND LENGTH( CHAR ( acc_usuario.deleted_at )) = 0
    	AND acc_perfil.status = 1
    	AND LENGTH( CHAR ( acc_perfil.deleted_at )) = 0
    	AND acc_usuario.id = '".$usuario_id."'
    ";

    return DB::select($cSql);
  }

}
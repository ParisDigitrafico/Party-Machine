<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccPerfil extends SuperModel
{
  protected $table      = 'acc_perfil';
  protected $primaryKey = 'id';

  public function usuarios()
  {
    return $this->belongsToMany(AccUsuario::class, 'acc_usuario_perfil', 'perfil_id', 'usuario_id');
  }

  public function permisos()
  {
    return $this->belongsToMany(AccPermiso::class, 'acc_perfil_permiso', 'perfil_id', 'permiso_id');
  }

  static function ConsultarPermisosPorUsuario($usuario_id)
  {
    $cSql = "
    SELECT DISTINCT
    	sys_permiso.*
    FROM
    	sys_usuario
    	INNER JOIN sys_usuario_perfil ON sys_usuario.id = sys_usuario_perfil.usuario_id
    	INNER JOIN sys_perfil ON sys_usuario_perfil.perfil_id = sys_perfil.id
    	INNER JOIN sys_perfil_permiso ON sys_perfil.id = sys_perfil_permiso.perfil_id
    	INNER JOIN sys_permiso ON sys_perfil_permiso.permiso_id = sys_permiso.id
    WHERE 1
    	AND sys_usuario.status = 1
    	AND LENGTH(CHAR(sys_usuario.deleted_at)) = 0
    	AND sys_perfil.status = 1
    	AND LENGTH(CHAR(sys_perfil.deleted_at)) = 0
    	AND sys_usuario.id = '$usuario_id'
    ";

    $this->EjecutarQuery($cSql);
  }

  static function ObtenerArrPermisosSeleccionados($perfil_id)
  {
    $response = array();

    $objPerfil = new self;

    $cSql = "
    SELECT * FROM acc_perfil_permiso
    WHERE perfil_id = '$perfil_id'
    ";

    $arrDato = DB::select($cSql);

    foreach($arrDato as $Dato)
    {
      $response[] = $Dato->permiso_id;
    }

    return $response;
  }
}
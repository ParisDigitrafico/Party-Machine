<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccUsuario extends SuperModel
{
  protected $table      = 'acc_usuario';
  protected $primaryKey = 'id';

  protected $columnRelArchivo = "usuario_id";

  public function perfiles()
  {
    return $this->belongsToMany(AccPerfil::class, 'acc_usuario_perfil', 'usuario_id', 'perfil_id');
  }

  public function scopeFilterUserPass($query, $cUser, $cPass)
  {
    return $query->where('user', $cUser)->where("pass", sha1($cPass));
  }

  public function scopeFilterClientes($query)
  {
    return $query->where("es_cliente",1);
  }

  static function EliminarPerfilesRelacionados($usuario_id)
  {
    DB::Table("acc_usuario_perfil")->where('usuario_id', $usuario_id)->delete();
  }

  static function AsignarPerfil($usuario_id,$perfil_id)
  {
    DB::Table("acc_usuario_perfil")->insert(array("usuario_id"=>$usuario_id,"perfil_id"=>$perfil_id));
  }

  static function ObtenerNombreCompletoById($usuario_id)
  {
    $response = "";

    $usuario = self::select()->withTrashed()->where("id",$usuario_id)->first();

    if($usuario)
    {
      $response = $usuario->nombre . " " . $usuario->apellidos;
      /*$response = $usuario->nombre . " " . $usuario->apellido_pat . " " . $usuario->apellido_mat; */
    }

    return trim($response);
  }

  static function ObtenerArrPerfilSeleccionado($usuario_id)
  {
    $response = array();

    if(!empty($usuario_id))
    {
      $cSql = "
      SELECT *
      FROM acc_usuario_perfil t
      WHERE 1
      AND t.usuario_id = '".$usuario_id."'
      ";

      $arrDato = DB::select($cSql);

      foreach($arrDato as $Dato)
      {
        $response[] = $Dato->perfil_id;
      }
    }

    return $response;
  }

  static function AgregarUsuario($Dato)
  {
    $response = false;

    $objSelf = new self();

    $objSelf->clave_folio = ( $Dato["es_cliente"] == 1 ) ? "CLIENTE" : "USUARIO";

    $response = $objSelf->AgregarConFolio($Dato);

    return $response;
  }
}


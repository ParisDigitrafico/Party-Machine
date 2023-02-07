<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccModulo extends SuperModel
{
  protected $table      = 'acc_modulo';
  protected $primaryKey = 'id';

  public function permisos()
  {
    return $this->hasMany(AccPermiso::class, 'idmodulo');
  }

  static function ObtenerArrModuloPorId($id)
  {
    $response = array();

    if(!empty($id))
    {
      if(is_array($id))
      {
        $id = implode(",", $id);
      }

      $arrId = array();

      $modulos = self::select()->filterStatus(1)->whereIn("id", explode(",", $id))->orderBy('orden', 'asc')->get();

      foreach($modulos as $modulo)
      {
        $arrId[] = $modulo->id;
        $arrId[] = $modulo->idpadre;
      }

      $arrId = array_filter(array_unique($arrId));

      $ids = implode(",",$arrId);

      if(!empty($ids))
      {
        /*$cSql = "
        SELECT m.*
        FROM sys_modulo m
        WHERE 1=1
        AND (m.id IN (".$ids.") OR es_publico=1)
      	AND m.status = 1
        AND m.idpadre = 0
      	AND length(char(m.deleted_at)) = 0
        ORDER BY m.orden ASC
        ";*/

        $cSql = "
        SELECT m.*
        FROM acc_modulo m
        WHERE 1=1
        AND (m.id IN (".$ids.") OR es_publico=1)
        AND m.status = 1
        AND m.idpadre = 0
        AND length(char(m.deleted_at)) = 0
        ORDER BY m.orden ASC
        ";

        $modulos = DB::select($cSql);

        foreach($modulos as $modulo)
        {
          $row = json_decode(json_encode($modulo), true);

          $cSql = "
          SELECT m.*
          FROM acc_modulo m
          WHERE 1=1
          AND m.idpadre IN (".$modulo->id.")
          AND (m.id IN (".$ids.") OR es_publico=1)
        	AND m.status = 1
        	AND length(char(m.deleted_at)) = 0
          ORDER BY m.orden ASC
          ";

          $modulos_aux = DB::select($cSql);

          if($modulos_aux)
          {
            $row["SubModulos"] = json_decode(json_encode($modulos_aux), true);
          }

          $response[] = $row;
        }
      }
    }

    return $response;
  }

  static function ObtenerTitulo($clave="")
  {
    $response = "";

    if(!empty($clave))
    {
      $modulo = AccModulo::select()->where("clave",$clave)->first();

      $response = $modulo->titulo ?: $modulo->nombre;
    }

    return $response;
  }
}
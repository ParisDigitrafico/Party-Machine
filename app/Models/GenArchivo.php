<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GenArchivo extends Model
{
  protected $table      = 'gen_archivo';
  protected $primaryKey = 'id';

  public function scopeFilterStatus($query, $status="")
  {
    if($status==1 || $status==0)
    {
      return $query->where("status", "=", $status);
    }

    return $query;
  }

  static function SincronizarArchivos($cCampoId, $id, $cClave="", $arrArchivo=array())
  {
    $objArchivo = new self();

    if(!empty($cCampoId) && !empty($id))
    {
      $query = $objArchivo->where($cCampoId, $id)->whereNull("deleted_at");

      if(!empty($cClave))
      {
        $query->where("clave", $cClave);
      }

      $query->update(array("deleted_at"=>now()));

      if(!empty($arrArchivo) && is_array($arrArchivo))
      {
        $iAux = 1;

        foreach($arrArchivo as $idar => $row)
        {
          $row["updated_at"] = now();
          $row["deleted_at"] = null;

          $row[$cCampoId]    = $id;
          $row["clave"]      = $cClave;
          $row["orden"]      = $iAux;

          $objArchivo->where("id", $idar)->update($row);

          $iAux = $iAux + 1;
        }
      }
    }
  }

  static function LimpiarEliminados()
  {
    $objArchivo = new self();

    $archivos = $objArchivo->where("es_fijo",0)->whereNotNull("deleted_at")->get();

    if($archivos->count())
    {
      foreach($archivos as $archivo)
      {
        $cFilePath   = !empty($archivo->url) ? public_path($archivo->url) : "";
        $cFileTnPath = !empty($archivo->url_tn) ? public_path($archivo->url_tn) : "";

        if(get_minutes_diference(now(), $archivo->deleted_at) > (24 * 60 * 7))
        {
          if(!empty($cFilePath) && is_file($cFilePath))
          {
            @unlink($cFilePath);

            if(!empty($cFileTnPath) && is_file($cFileTnPath))
            {
              @unlink($cFileTnPath);
            }

            if(!is_file($cFilePath))
            {
              $archivo->delete();
            }
          }
          else
          {
            $archivo->delete();
          }
        }
      }
    }
  }
}
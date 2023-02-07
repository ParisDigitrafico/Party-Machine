<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuperModel extends Model
{
  use SoftDeletes;

  public $timestamps = true;

  public function scopeFilterStatus($query, $bStatus=true)
  {
    $iAux = get_bool($bStatus) ? 1:0;

    return $query->where("status", "=", $iAux);
  }

  public function scopeFilterActivos($query)
  {
    return $this->scopeFilterStatus($query, true);
  }

  public function scopeFilterVisibles($query)
  {
    return $this->scopeFilterActivos($query)->where("es_visible", "=", 1);
  }

  public function AgregarConFolio($Dato=array())
  {
    $response = false;

    $objFolio = new AccFolio;

    if(!empty($this->clave_folio))
    {
      $folio = $objFolio::select()->where("clave", $this->clave_folio)->first();

      if(!empty($folio->id))
      {
        $iUltimoFolio = $folio->ultimo_folio + 1;

        $Dato["serie"]  = $folio->serie;
        $Dato["folio"]  = $iUltimoFolio;
        $Dato["codigo"] = $Dato["serie"] . str_pad(intval($Dato["folio"]), 6, '0', STR_PAD_LEFT);

        if(DB::Table($this->table)->insert($Dato))
        {
          $Dato = array();

          $Dato["ultimo_folio"] = $iUltimoFolio;

          $objFolio::where("id", $folio->id)->update($Dato);

          $response = true;
        }
      }
    }

    return $response;
  }

  public function getCategorias($id="", $cTabla="")
  {
    $response = array();

    $objCat = new GenCategoria;

    $cTabla = !empty($cTabla) ? $cTabla : $this->table;

    $data = $objCat->withTrashed()->where("tabla", $cTabla)->get();

    if(count($data) > 0)
    {
      foreach($data as $item)
      {
        if(empty($item->deleted_at) || $item->id == $id)
        {
          $response[] = $item;
        }
      }
    }

    return $response;
  }

  public function archivo($cClave="")
  {

  }

  public function archivos($cClave="")
  {
    $response = [];

    if(!empty($this->columnRelArchivo) && !empty($this->id))
    {
      $objArchivo = new GenArchivo();

      $query = $objArchivo->filterStatus(1)->where($this->columnRelArchivo, $this->id)->whereNull("deleted_at");

      if(!empty($cClave))
      {
        $query->where("clave", $cClave);
      }

      $photos = $query->orderby("orden","ASC")->get();

      if(count($photos) > 0)
      {
        foreach($photos as $photo)
        {
          $response[] = $photo;
        }
      }
    }

    return $response;
  }

  public function photo($cClave="", $bStatus=1)
  {
    $response = null;

    $photo = null;

    $objArchivo = new GenArchivo();

    if(!empty($this->columnRelArchivo) && !empty($this->id))
    {
      $query = $objArchivo->where($this->columnRelArchivo, $this->id)->whereNull("deleted_at")->where("es_foto",1);

      if(!empty($cClave))
      {
        $query->where("clave", $cClave);
      }
      else
      {
        $query->where(function($query) { $query->where('clave','=','')->orWhereNull('clave'); });
      }
      
      $photo = $query->orderBy("orden","ASC")->first();
    }

    if($photo)
    {
      $response = $photo;
    }
    else
    {
      $objArchivo->url    = "/static/generico/images/404.jpg";
      $objArchivo->url_tn = "/static/generico/images/404.jpg";

      $response = $objArchivo;
    }

    return $response;
  }

  public function photos($cClave="", $bSoloActivos=true)
  {
    $response = [];

    if(!empty($this->columnRelArchivo) && !empty($this->id))
    {
      $objArchivo = new GenArchivo();

      $bSoloActivos = get_bool($bSoloActivos);

      $query = $objArchivo->where($this->columnRelArchivo, $this->id)->whereNull("deleted_at")->where("es_foto",1);

      if($bSoloActivos)
      {
        $query->filterStatus(1);
      }

      if(!empty($cClave))
      {
        $query->where("clave", $cClave);
      }
      else
      {
        $query->where(function($query) { $query->where('clave','=','')->orWhereNull('clave'); });
      }

      $photos = $query->orderby("orden","ASC")->get();

      if(count($photos) > 0)
      {
        foreach($photos as $photo)
        {
          $response[] = $photo;
        }
      }
    }

    return $response;
  }

  public function syncArchivos($arrArchivo, $cClave="")
  {
    $objArchivo = new GenArchivo();

    if(!empty($this->columnRelArchivo) && !empty($this->id))
    {
      $query = $objArchivo->where($this->columnRelArchivo, $this->id)->whereNull("deleted_at");

      if(!empty($cClave))
      {
        $query->where("clave", $cClave);
      }
      else
      {
        $query->where(function($query) { $query->where('clave','=','')->orWhereNull('clave'); });
      }

      $query->update(array("deleted_at"=>now()));

      if(!empty($arrArchivo) && is_array($arrArchivo))
      {
        $iAux = 1;

        foreach($arrArchivo as $idar => $row)
        {
          $row["updated_at"] = now();
          $row["deleted_at"] = null;

          $row[$this->columnRelArchivo] = $this->id;

          $row["clave"] = $cClave;
          $row["orden"] = $iAux;

          $objArchivo->where("id", $idar)->update($row);

          $iAux = $iAux + 1;
        }
      }
    }
  }
}
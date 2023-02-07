<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebPagina extends SuperModel
{
  protected $table      = 'web_pagina';
  protected $primaryKey = 'id';

  protected $columnRelArchivo = "pagina_id";

  /*public function banners()
  {
    return $this->hasMany(WebBanner::class, "banner_id");
  }*/

  public function banners()
  {
     return $this->belongsToMany(
          WebBanner::class, # remote model
          'web_rel_pagina_banner', # pivot table
          'pagina_id',  # local id model
          'banner_id'); # remote id model
  }

}

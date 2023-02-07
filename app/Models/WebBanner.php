<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebBanner extends SuperModel
{
  protected $table      = 'web_banner';
  protected $primaryKey = 'id';

  protected $columnRelArchivo = "banner_id";
  
}

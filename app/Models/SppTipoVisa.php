<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SppTipoVisa extends SuperModel
{
  protected $table      = 'spp_tipovisa';
  protected $primaryKey = 'id';
}
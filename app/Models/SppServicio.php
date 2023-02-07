<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SppServicio extends SuperModel
{
  protected $table      = 'spp_servicio';
  protected $primaryKey = 'id';
}
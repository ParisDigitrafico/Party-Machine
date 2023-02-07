<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccConfiguracion extends SuperModel
{
  protected $table      = 'acc_configuracion';
  protected $primaryKey = 'id';
}
<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccFolio extends SuperModel
{
  protected $table      = 'acc_folio';
  protected $primaryKey = 'id';
}
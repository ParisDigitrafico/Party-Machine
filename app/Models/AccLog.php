<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccLog extends SuperModel
{
  protected $table      = 'acc_log';
  protected $primaryKey = 'id';
}
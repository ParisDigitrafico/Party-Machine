<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccCuenta extends Model
{
    protected $table      = 'acc_cuenta';
    protected $primaryKey = 'id';
}
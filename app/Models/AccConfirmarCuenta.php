<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccConfirmarCuenta extends Model
{
    protected $table      = 'acc_confirmar_cuenta';
    protected $primaryKey = 'id';
}
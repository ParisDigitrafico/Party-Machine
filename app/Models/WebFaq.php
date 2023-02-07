<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebFaq extends SuperModel
{
  protected $table      = 'web_faq';
  protected $primaryKey = 'id';

  public function categoria()
  {
    return $this->hasOne(GenCategoria::class, "id", "categoria_id");
  }

}

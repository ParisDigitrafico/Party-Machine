<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Support\Facades\Cache;

error_reporting(E_ALL & ~(E_STRICT|E_NOTICE));

class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public function clearcache()
  {
    $cAux = storage_path('app/menu_website.json');

    if(is_file($cAux))
    {
      \File::delete($cAux);
    }

    Cache::flush();

    /*exit("ok");*/
  }
}
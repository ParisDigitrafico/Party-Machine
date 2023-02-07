<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Usuario;

class HomeController extends Controller
{
  public function index()
  {
    $response = array();

    header("Location:/sistema/login/");
    exit;

    return view('publico.portada', $response)->render();
  }

  public function welcome()
  {
    $response = array();

    return view('welcome', $response)->render();
  }

  public function syncProductosCSV()
  {
    $response = array();

    exit(var_dump(1));
  }

}

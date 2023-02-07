<?php
namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;

use App\Models\AccUsuario;

class ContactoController extends MController
{
	public function __construct()
	{
	  parent::__construct();
	}

  public function index(Request $request)
  {
    $response = array();

    /*exit(var_dump(1)); */

    return view('website.custom.contacto', $response)->render();
  }

}
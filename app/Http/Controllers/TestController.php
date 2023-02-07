<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\CryptoJSAES;
use App\Helpers\MensajeNotificacion;

use App\Models\SysUsuario;
use App\Models\SysModulo;
use App\Models\SysPermiso;
use App\Models\SysControlSesion;
use App\Models\SysRestablecerContrasenia;

use App\Models\TestAutor;
use App\Models\TestCategoria;
use App\Models\TestCliente;
use App\Models\TestLibro;
use App\Models\TestPrestamo;

class TestController extends Controller
{
  public function __construct()
  {
    $this->url_api = "https://reqres.in";
    $this->api_key = "1c7a92ae351d4e21ebdfb897508f59d6";

    $this->guzzle_client = new \GuzzleHttp\Client([ 'base_uri' => $this->url_api ]);
  }

  public function TestPermisos()
  {
    $objUsuario    = new SysUsuario();
    $objModulo     = new SysModulo();
    $objPermiso    = new SysPermiso();
    $objCtrlSesion = new SysControlSesion();

    $usuario_id = 1002;

    $objUsuario->ConsultarRegistro($usuario_id);

    $objPermiso->ConsultarPermisosPorUsuario($usuario_id);

    while($objPermiso->Recorrer())
    {
      $arrModulo[]  = $objPermiso->idmodulo;
      $arrPermiso[] = $objPermiso->clave;
    }

    $arrModulo = $objModulo->ObtenerArrModuloPorId(array_filter(array_unique($arrModulo)));

    exit(var_dump($arrModulo));
  }

  public function testModelos()
  {
    $objAutor = new TestAutor();
    $objLibro = new TestLibro();
    $objCat   = new TestCategoria();

    $cat   = $objCat->whereId(1)->first();
    $libro = $objLibro->whereId(1)->first();
    $autor = $objAutor->whereId(2)->first();

    $prestamos = $cat->prestamos;

    foreach($prestamos as $prestamo)
    {
      exit(var_dump($prestamo->clave));
    }

    exit(var_dump($prestamos->count()));
  }

  public function TestBearer()
  {
    $response = array();

    $objElemento = new SysUsuario();

    $objElemento->Consultar();
    
    $objGuzz = new \GuzzleHttp\Client(['base_uri' => $this->url_api]);

    $arrAux['headers'] = [
      'Authorization' => 'Bearer ' . $this->api_key,
      'Accept' => 'application/json',
    ];

    $arrAux["query"] = array("page"=>"2");

    $response = $objGuzz->request('get', '/api/users/', $arrAux);

    $response = json_decode((string)$response->getBody(), true);

    return response()->json($response);
  }

  private function request_api($type="get", $curl="", $parameters = [] )
  {
    $arrAux = array();

    try
    {
      $type = strtolower($type);

      $arrAux["headers"] = [
        'Authorization' => 'Bearer ' . $this->api_key,
        'Accept' => 'application/json',
      ];

      $arrAux["http_errors"] = false;

      $arrAux[($type == "get"?"query":"json")] = $parameters;

      $response = $this->guzzle_client->request($type, $curl, $arrAux);

      $arrAux = array();

      if($type == "get")
      {
        if($response->getStatusCode() == 200 && !empty($response->getBody()))
        {
          $arrAux = json_decode((string)$response->getBody(), true);
        }
      }
      else
      {
        $arrAux["code"] = (int)$response->getStatusCode();
        $arrAux["data"] = (string)$response->getBody();
      }
    }
    catch(exception $e)
    {
      /*echo $ex->getMessage();*/
      $arrAux = array();
    }

    $arrAux = (array)$arrAux;

    return $arrAux;
  }
}
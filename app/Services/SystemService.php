<?php
namespace App\Services;

use App\Models\FbzCuenta;

class SystemService
{
  private $api_url;
  private $api_key;

  public function __construct()
  {
    /*$cuenta = FbzCuenta::find(1); */

    $this->api_url = get_const("SYSTEM_API_URL") ?: get_host();

    /*$this->api_url = "http://localhost";
    $this->api_key = $this->obtener_token();*/
  }

  public function post_pedidos()
  {
    $response = array();

    $response = $this->request_api( 'post', '/api/v1/pedidos/');

    return $response;
  }

  public function get_pedido($id="")
  {
    $response = array();

    $response = $this->request_api( 'get', '/api/v1/pedidos/'.$id.'/');

    return $response;
  }

  public function put_pedidos()
  {
    $response = array();

    $response = $this->request_api( 'post', '/api/v1/pedido/{pedido}/{producto}/{cantidad}');

    return $response;
  }

  protected function request_api($type="get", $cUrl="", $parameters=array(), $bSecure=true)
  {
    $arrAux = array();

    try
    {
      /*$client = new \GuzzleHttp\Client([
                                  'verify' => false,
                                  ]);*/

      $client = new \GuzzleHttp\Client([ 'base_uri' => $this->api_url ]);

      $type = strtolower($type);

      $Headers = array();

      if($bSecure === true)
      {
        /*$Headers["Authorization"] = 'Bearer ' . $this->api_key;*/
      }

      $Headers["Accept"] = 'application/json';

      $arrAux["headers"] = $Headers;

      $arrAux["http_errors"] = false;

      $arrAux[($type == "get" ? "query":"json")] = $parameters;

      $response = $client->request($type, $cUrl, $arrAux);

      $arrAux = array();

      $arrAux["code"]    = intval($response->getStatusCode());
      $arrAux["headers"] = $response->getHeaders();

      if($type == "get" && $response->getStatusCode() == 200)
      {
        if(!empty($response->getBody()))
        {
          $arrAux["data"] = json_decode((string)$response->getBody(), true);
        }
      }
      else
      {
        $arrAux["data"] = json_decode((string)$response->getBody(), true);
      }
    }
    catch(exception $e)
    {
      $arrAux = array();
    }

    $arrAux = (array)$arrAux;

    return $arrAux;
  }

}
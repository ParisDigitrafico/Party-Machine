<?php
namespace App\Services;

use App\Models\FbzCuenta;

class FbazarService
{
  private $api_url;
  private $api_key;

  public function __construct()
  {
    $cuenta = FbzCuenta::find(1);

    $this->api_url = get_const("FBAZAR_API_URL");
    $this->api_key = $this->obtener_token();
  }

  /*************************************************
  * ACCOUNT
  *************************************************/
  public function obtener_token()
  {
    $response = "";

    $objCuenta = new FbzCuenta();

    $cuenta = $objCuenta->find(1);

    if($cuenta)
    {
      if(get_minutes_diference($cuenta->updated_at, now()) > (60 * 23))
      {
        $apiResponse = $this->request_api("post","/api/cuentas/login/", array("userName"=>$cuenta->username, "password"=>$cuenta->password), false);

        if($apiResponse["code"] == 200)
        {
          $Dato = array();

          $Dato["token"]      = $apiResponse["data"]["token"];
          $Dato["updated_at"] = now();

          $response = $Dato["token"];

          $objCuenta->where("id",1)->update($Dato);
        }
      }
      else
      {
        $response = $cuenta->token;
      }
    }

    return $response;
  }

  /*************************************************
  * Categorias
  *************************************************/
  public function get_categorias_ecommerce($req=array())
  {
    $response = array();

    $defaults = array(
      'Pagina' => 1,
      'RecordPorPagina' => 12,
      'searchWords' => '',
      'idParent' => '',
    );

    $req = array_merge($defaults, $req);

    $req["idParent"] = intval($req["idParent"]);

    if(!empty($cAux = $req["searchWords"]))
    {
      $response = $this->request_api( 'get', '/api/categorias/ObtenerCategoriasEcommerce/'.$cAux.'/', $req);
    }
    else
    {
      if($req["idParent"] > 0)
      {
        $response = $this->request_api( 'get', '/api/categorias/ObtenerCategoriasEcommerce/'. $req["idParent"] .'/', $req);
      }
      else
      {
        $response = $this->request_api( 'get', '/api/categorias/ObtenerCategoriasEcommerce/0/', $req);
      }
    }

    return $response;
  }

  public function post_categoria_ecommerce($req=array())
  {
    $response = array();

    $defaults = array(
      "name" => "",
      "urlPhoto" => "",
      "urlPhotoTn" => "",
      "parent" => "",
    );

    $req = array_merge($defaults, $req);

    $response = $this->request_api( 'post', '/api/categorias/CrearCategoriaEcommerce/', $req);

    return $response;
  }

  public function put_categoria_ecommerce($req=array(), $id)
  {
    $response = array();

    $defaults = array(
      "name" => "",
      "urlPhoto" => "",
      "urlPhotoTn" => "",
      "parent" => "",
    );

    $req = array_merge($defaults, $req);

    $response = $this->request_api( 'put', '/api/categorias/ModificarCategoriaEcommerce/'. $id .'/', $req);

    return $response;
  }

  public function delete_categoria_producto($req=array())
  {
    $response = array();

    $defaults = array(
      "name" => "",
      "urlPhoto" => "",
      "urlPhotoTn" => "",
      "parent" => "",
    );

    $req = array_merge($defaults, $req);

    $response = $this->request_api( 'delete', '/api/categorias/CrearCategoriaEcommerce/', $req);

    return $response;
  }

  /*************************************************
  * Productos
  *************************************************/
  public function get_producto_ecommerce_bycode($req=array())
  {
    $response = array();

    $defaults = array(
      'Pagina' => 1,
      'RecordPorPagina' => 12,
      'searchWords' => '',
    );

    $req = array_merge($defaults, $req);

    if(!empty($cAux = $req["searchWords"]))
    {
      $response = $this->request_api( 'get', '/api/productos/BuscarProductosBazar/'.$cAux.'/', $req);
    }
    else
    {
      $response = $this->request_api( 'get', '/api/productos/ObtenerProductosEcommerce/', $req);
    }

    return $response;
  }

  public function get_productos_ecommerce($req=array())
  {
    $response = array();

    $defaults = array(
      'Pagina' => 1,
      'RecordPorPagina' => 12,
      'searchWords' => '',
    );

    $req = array_merge($defaults, $req);

    if(!empty($cAux = $req["searchWords"]))
    {
      $response = $this->request_api( 'get', '/api/productos/BuscarProductosBazar/'.$cAux.'/', $req);
    }
    else
    {
      $response = $this->request_api( 'get', '/api/productos/ObtenerProductosEcommerce/', $req);
    }

    return $response;
  }

  public function post_productos_ecommerce($req=array())
  {
    $response = array();

    $defaults = array(
      "codigoProducto" => 0,
      "idCategoria" => 0,
      "urlPhoto" => "string",
      "urlGallery" => "string",
      "routeAdm" => "string",
      "presentation" => "string",
      "delivery" => true,
      "pickUp" => true,
      "paqueteria" => true
    );

    $req = array_merge($defaults, $req);

    if(!empty($cAux = $req["searchWords"]))
    {
      $response = $this->request_api( 'get', '/api/productos/BuscarProductosBazar/'.$cAux.'/', $req);
    }
    else
    {
      $response = $this->request_api( 'get', '/api/productos/ObtenerProductosEcommerce/', $req);
    }

    return $response;
  }

  public function put_productos_ecommerce($req=array())
  {
    $response = array();

    $defaults = array(
      "id" => 0,
      "codigoProducto" => 0,
      "idCategoria" => 0,
      "urlPhoto" => "string",
      "urlGallery" => "string",
      "routeAdm" => "string",
      "presentation" => "string",
      "delivery" => true,
      "pickUp" => true,
      "paqueteria" => true,
    );

    $req = array_merge($defaults, $req);

    if(!empty($req["id"]) && !empty($req["codigoProducto"]))
    {
      $response = $this->request_api( 'put', '/api/productos/ModificarProductoEcommerce/'.$req["id"].'/', $req);
    }

    return $response;
  }

  public function get_producto_bazar($id="")
  {
    $response = array();

    if(is_numeric( $codigo ) && $codigo > 0 )
    {
      $response = $this->request_api( 'get', '/api/productos/ObtenerProductoPorCodigoBazar/' . $codigo . '/');
    }

    return $response;
  }

  public function get_producto_bazar_bycode($codigo="")
  {
    $response = array();

    if(is_numeric( $codigo ) && $codigo > 0 )
    {
      $response = $this->request_api( 'get', '/api/productos/ObtenerProductoPorCodigoBazar/' . $codigo . '/');
    }

    return $response;
  }

  public function get_productos_bazar($req=array())
  {
    $response = array();

    $defaults = array(
      'Pagina' => 1,
      'RecordPorPagina' => 12,
      'searchWords' => '',
    );

    $req = array_merge($defaults, $req);

    if(!empty($cAux = $req["searchWords"]))
    {
      $response = $this->request_api( 'get', '/api/productos/BuscarProductosBazar/'.$cAux.'/', $req);
    }
    else
    {
      $response = $this->request_api( 'get', '/api/productos/ObtenerProductosBazar/', $req);
    }

    return $response;
  }

  /*************************************************
  * SADCLIENTES
  *************************************************/
  public function get_cliente($id="")
  {
    $response = array();

    if(is_numeric( $id ) && $id > 0 )
    {
      $response = $this->request_api( 'get', '/api/sadclientes/ObtenerClientePorId/' . $id . '/');
    }
    else
    {
      $response = $this->request_api( 'get', '/api/sadclientes/');
    }

    return $response;
  }

  public function obtener_estados()
  {
    $response = array();

    $response = $this->request_api( 'get', '/api/sadclientes/getestados/');         

    return $response;
  }

  /*************************************************
  * SADPEDIDOS
  *************************************************/
  public function ver_status_pedido()
  {
    $response = array();

    if(is_numeric( $id ) && $id > 0 )
    {
      $response = $this->request_api( 'get', '/api/sadclientes/' . $id . '/');
    }
    else
    {
      $response = $this->request_api( 'get', '/api/sadclientes/');
    }

    return $response;
  }

  public function crear_pedido($info=array())
  {
    $response = array();

    /*
    {
      "clienteId": 6598,
      "codigoSucursalAsignado": 3,
      "codigoClienteSad": 2195,
      "codigoColonia": 0,
      "horaRegistro": "2022-08-15T16:02:29.280Z",
      "subtotal": 10.34,
      "iva": 0,
      "total": 10.34,
      "descuento": 0,
      "detalle": [
        {
          "codigoProducto": 3785,
          "cantidad": 1
        }
      ]
    }
    */

    $Pedido = array(
      "clienteId" => 6586,
      "codigoSucursalAsignado" => 3,
      "horaRegistro" => "2022-09-01T21:52:43.854Z",
      "detalle" => array(
        array(
          "codigoProducto" => 3785,
          "cantidad" => 1,
        ),
      ),
    );

    $response = $this->request_api( 'post', '/api/sadpedidos/CrearPedido/', $Pedido);

    exit(var_dump($response));

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
        $Headers["Authorization"] = 'Bearer ' . $this->api_key;
      }

      $Headers["Accept"] = 'application/json';

      $arrAux["headers"] = $Headers;

      $arrAux["http_errors"] = false;

      $arrAux[($type == "get" ? "query":"json")] = $parameters;

      if(config("app.env") != "production")
      {
        /*$arrAux["verify"] = app_path() . "/cacert.pem";*/
        /*$arrAux["cert"] = [app_path() . '/cacert.pem'];*/
      }

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
    catch(\exception $ex)
    {
      $arrAux = array();
    }

    $arrAux = (array)$arrAux;

    return $arrAux;
  }

}
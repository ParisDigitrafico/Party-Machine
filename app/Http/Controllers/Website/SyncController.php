<?php
namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use App\Models\AccUsuario;
use App\Models\AccPago;

use App\Models\FbzCliente;
use App\Models\FbzProducto;
use App\Models\FbzProductoCategoria;

use App\Services\FbazarService;

class SyncController extends MController
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getToken(Request $request)
  {
    $objFBazar = new FbazarService();

    $response = $objFBazar->obtener_token();
    /*$response = $objFBazar->obtener_categorias_productos(); */

    /*$Req = array();

    $Req["name"]       = "1demo";
    $Req["urlPhoto"]   = "https://cdn1.coppel.com/images/catalog/pr/5141792-1.jpg";
    $Req["urlPhotoTn"] = "https://cdn1.coppel.com/images/catalog/pr/5141792-1.jpg";
    $Req["parent"]     = "";

    $response = $objFBazar->crear_categoria_producto($Req);*/

    /*$objFBazar->crear_pedido(); */

    exit(var_dump($response));
  }

  public function SyncClientes(Request $request)
  {
    $response = array();

    $objFBazar  = new FbazarService();
    $objCliente = new FbzCliente();
    $objPago    = new AccPago();

    $pago = $objPago->find(1);

    exit(var_dump($pago->nombre));

    $arrResponse = $objFBazar->obtener_clientes();

    if($arrResponse["code"] == 200)
    {
      $objCliente->where("id","!=",0)->update(["status" => 0]);

      $arrRecord = $arrResponse["data"];

      foreach($arrRecord as $record)
      {
        $elem = $objCliente->where("id", $record["id"])->first();

        if($elem)
        {
          $record["status"] = 1;

          $objCliente->where("id", $record->id)->update($record);
        }
        else
        {
          $objCliente->insert($record);
        }
      }
    }

    exit("-- end --");
  }

  public function SyncProductos(Request $request)
  {
    $response = array();

    $objFBazar   = new FbazarService();
    $objProducto = new FbzProducto();

    $objProducto->where("id","!=",0)->update(["status" => 0]);

    $arrReq = array();

    $arrReq["Pagina"] = 1;
    $arrReq["RecordPorPagina"] = 999;

    $arrRes = $objFBazar->obtener_productos($arrReq);

    while(!empty($arrRes["data"]))
    {
      $arrProducto = $arrRes["data"];

      foreach($arrProducto as $Producto)
      {
        $record = $objProducto->where("codigoProducto", $Producto["codigoProducto"])->first();

        $item = array();

        $item["codigoProducto"]     = $Producto["codigoProducto"];
        $item["nombreProducto"]     = $Producto["descripcionCorta"];
        $item["codigoRelacionado"]  = $Producto["codigoRelacionado"];
        $item["codigoDepartamento"] = $Producto["codigoDepartamento"];
        $item["descripcion"]        = $Producto["descripcion"];
        $item["descripcionCorta"]   = $Producto["descripcionCorta"];
        $item["familia"]            = $Producto["familia"];
        $item["controlado"]         = $Producto["controlado"];
        $item["precioVenta"]        = $Producto["precioVenta"];

        if($record)
        {
          $item["status"] = 1;

          $objProducto->where("id", $record->id)->update($item);
        }
        else
        {
          $objProducto->insert($item);
        }
      }

      $arrReq["Pagina"] = $arrReq["Pagina"] + 1;

      /*exit; */

      $arrRes = $objFBazar->obtener_productos($arrReq);
    }

    exit("-- end --");
  }

  public function SyncProductoCategoria()
  {
    $objProducto = new FbzProducto();
    $objProdCat  = new FbzProductoCategoria();

    $objProdCat::truncate();
    $productos = DB::Select("SELECT prd.familia FROM fbz_producto prd GROUP BY prd.familia");

    if($productos)
    {
      foreach($productos as $producto)
      {
        $arrAux = explode(" / ", $producto->familia);

        $Dato = array();

        if(isset($arrAux[0]) && !empty($cAux = $arrAux[0]))
        {
          $Dato["cat"] = $cAux;
        }

        if(isset($arrAux[1]) && !empty($cAux = $arrAux[1]))
        {
          $Dato["sub_cat"] = $cAux;
        }

        if(isset($arrAux[2]) && !empty($cAux = $arrAux[2]))
        {
          $Dato["sub_sub_cat"] = $cAux;
        }

        if(isset($arrAux[3]) && !empty($cAux = $arrAux[3]))
        {
          $Dato["sub_sub_sub_cat"] = $cAux;
        }

        if(isset($arrAux[4]) && !empty($cAux = $arrAux[4]))
        {
          $Dato["sub_sub_sub_sub_cat"] = $cAux;
        }

        $objProdCat->insert($Dato);
      }

      exit(var_dump(count($productos)));
    }
  }

  public function SyncEstados(Request $request)
  {
    $response = array();

    $objFBazar  = new FbazarService();
    $objProdCat = new FbzProductoCategoria();

    $arrResponse = $objFBazar->obtener_estados();

    /*$this->SyncProductoCategoria(); */

    /*$arrCategoria = $objProdCat->obtenerArrCategoriaSubcategoria();

    PrettyPrintJson(json_encode($arrCategoria));
    exit;*/

    exit(var_dump($arrResponse));
  }

  public function syncProductosCSV()
  {
    $response = array();

    $objProdCat = new FbzProductoCategoria;

    /*$cFilename = public_path('media/productoscsv/productos_2022_09_26.csv');*/
    $cFilename = "";

    $bProducto = false;

    if(is_file($cFilename))
    {
      $gestor = null;

      if(($gestor = fopen($cFilename, "r")) !== false)
      {
        echo "<br />Inicia sincronizado: " . basename($cFilename) . "<br />";

        DB::table('fbz_producto_csv')->truncate();

        $encabezado_a_sql = array(
          "sku" => "sku",
          "desc_iqvia" => "desc_iqvia",
          "cod_bazar" => "cod_bazar" ,
          "desc_bisoft" => "desc_bisoft",
          "desc_corta" => "desc_corta",

          "sal" => "sal",
          "laboratorio" => "laboratorio",
          "segmento" => "segmento",
          "cod_cat_padre" => "cod_cat_padre",
          "cod_cat" => "cod_cat",

          "cat_padre" => "cat_padre",
          "cat_1" => "cat_1",
          "tags" => "tags",
          "via_admon" => "via_admon",
          "indicaciones" => "indicaciones",

          "dosis" => "dosis",
          "contraindicaciones" => "contraindicaciones",
          "uso_pediatrico" => "uso_pediatrico",
          "presentacion" => "presentacion",
          "empty" => "empty",

          "delivery" => "delivery",
          "pickup" => "pickup",
          "venta_con_receta" => "venta_con_receta",
          "paqueteria" => "paqueteria",
        );

        $encabezado_cargado = false;

        $iFila = 1;

        while(($datos = fgetcsv($gestor, 1000, ",", '"')) !== false)
        {
          if($encabezado_cargado==false)
          {
            $encabezado = $datos;
            $encabezado_cargado = true;
            $iColumnas = count($encabezado);
          }
          else
          {
            $Dato = array();

            $Dato["id"] = $iFila;

            for($i=0; $i<$iColumnas; ++$i)
            {
              $datos[$i] = utf8_encode($datos[$i]);
              $datos[$i] = trim($datos[$i]);

              if(!empty($encabezado_a_sql[$encabezado[$i]]))
              {
                $Dato[$encabezado_a_sql[$encabezado[$i]]] = trim($datos[$i]);
              }
            } # endfor encabezados

            if(!empty($Dato["cod_bazar"]))
            {
              DB::table('fbz_producto_csv')->insert($Dato);
            }

          }

          $iFila = $iFila + 1;
        }

        $bProducto = true;
      }

      if($gestor !== null)
      {
        fclose($gestor);
      }
    }

    /*if($bProducto)*/
    if(true)
    {
      $objProdCat->sincronizarDesdeProductosCSV();
      /*$objSincro->ValidarProductos();
      $objSincro->SincronizarProductos();*/
    }

    exit("-- end --");
  }

}
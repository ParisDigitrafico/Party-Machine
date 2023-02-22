<?php
namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use App\Helpers\HtmlHelper;
use App\Helpers\ConektaHelper;
use App\Helpers\MensajeNotificacion;

use App\Models\AccUsuario;
use App\Models\AccModulo;
use App\Models\AccPerfil;
use App\Models\AccConfiguracion;

use App\Models\SppPedido;

use App\Models\GenArchivo;


class PedidosController extends MController
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index(Request $request)
  {
    abort(404);
  }

  public function show(Request $request, $id)
  {
    abort(404);
  }

  public function StoreCarrito(Request $request)
  {
    $response = array();

    $objLocalServ = new SystemService();

    $arrRes = $objLocalServ->post_pedidos();

    $response["data"] = $arrRes["data"];

    exit(var_dump($response));

    return view("website.comprar.carrito", $response)->render();
  }

  public function ShowCarritoById($id)
  {
    $response = array();

    $objSystemServ = new SystemService();

    $arrRes = $objSystemServ->get_pedido(1);

    $response["data"] = $arrRes["data"];

    return view("website.carrito.carrito_form", $response)->render();
  }

  public function ShowCarritoByCKey($ckey)
  {
    $response = array();

    $pedido = SppPedido::where("ckey",$ckey)->get();

      return view("website.carrito.carrito_form", $response)->render();
  }

  public function ShowResumenByCKey($ckey)
  {
    $response = array();

    $pedido = FbzPedido::where("ckey", $ckey)->first();

    if($pedido)
    {
      $idpedido = $pedido->id;

      $objSysServ = new SystemService();

      if(!empty(session("cliente_id")))
      {
        $objFbazar = new FbazarService();

        $apiResponse = $objSysServ->get_pedido($idpedido);

        $data = $apiResponse["data"];

        if(count($data) > 0)
        {
          $response["data"] = $data;
          $response["ckey"] = $ckey;

          $aux = array();

          $apiResponse = $objFbazar->get_cliente_ecommerce_by_email(session("cliente_email"));

          if($apiResponse["code"] == 200)
          {
            $aux = $apiResponse["data"];
          }

          if(isset($aux["direccion"]) && count($aux["direccion"]) == 0)
          {
            $aux1 = array();

            $aux1["code"]    = 500;
            $aux1["message"] = "Para realizar pedidos de producto es necesario agregar al menos una dirección de domicilio en su cuenta.";

            header("location:/cliente/direcciones/?" . http_build_query($aux1));
            exit;
          }

          $response["cliente"] = $aux;

          $aux = Cache::remember('api_sucursales', (6*60), function () use ($objFbazar) {
            $arr = [];

            $apiResponse = $objFbazar->obtener_sucursales();

            if($apiResponse["code"] == 200)
            {
              $arr = $apiResponse["data"];
            }

            return $arr;
          });

          $response["sucursales"] = $aux;

          # combo de cambios
          {
            $dTotal = $data["total"];

            $arrPago = array();

            $iAux = 0;

            while($iAux < $dTotal)
            {
              $iAux = $iAux + 50;
            }

            $pagoMultiplo50 = $iAux;

            $PagoMax500 = 500 * ceil(($iAux / 500));

            $iAux = 0;

            while($iAux < $dTotal)
            {
              $iAux = $iAux + 100;
            }

            $pagoMultiplo100 = $iAux;

            $iAux = 0;

            while($iAux < $dTotal)
            {
              $iAux = $iAux + 200;
            }

            $pagoMultiplo200 = $iAux;

            ###############################

            if($pagoMultiplo50 < $pagoMultiplo100)
            {
              $arrPago[] = $pagoMultiplo50;
            }

            if($pagoMultiplo100 < $pagoMultiplo200)
            {
              $arrPago[] = $pagoMultiplo100;
            }

            if($pagoMultiplo200 < $PagoMax500)
            {
              $arrPago[] = $pagoMultiplo200;
            }

            if($pagoMultiplo50 == $pagoMultiplo100 && $pagoMultiplo100 == $pagoMultiplo200)
            {
              $pagoAux = $pagoMultiplo100 + 50;

              if($pagoAux < $PagoMax500)
              {
                $arrPago[] = $pagoAux;
              }

              $pagoAux = $pagoMultiplo100 + 100;

              if($pagoAux < $PagoMax500)
              {
                $arrPago[] = $pagoAux;
              }

              $pagoAux = $pagoMultiplo100 + 200;

              if($pagoAux < $PagoMax500)
              {
                $arrPago[] = $pagoAux;
              }
            }

            $arrPago[] = $PagoMax500;

            $arrPago = array_unique($arrPago);

            $response["pagos"] = $arrPago;
          }

          return view("website.carrito.carrito_resumen", $response)->render();
        }
        else
        {
          header("location:/productos/");
          exit;
        }
      }
      else
      {
        header("location:/cliente/");
        exit;
      }
    }

    header("location:/productos/");
    exit;
  }

  public function ShowPdfByCKey(Request $request, $ckey)
  {
    $response = array();

    $objPedido = new FbzPedido();
    $objFbazar = new FbazarService();

    $data = $objPedido->where("es_carrito",0)->where("ckey",$ckey)->first();

    if($data)
    {
      $mpdf = new \Mpdf\Mpdf();

      /*MensajeNotificacion::EnviarPedido(317,true); */

      $mpdf->SetHeader('Farmacias Bazar'.'||');
      $mpdf->SetFooter('||{PAGENO}');

      $stylesheet = config("app.env") == 'production' ? get_asset('/static/generico/css/pdf12col.css') : './static/generico/css/pdf12col.css';

      $stylesheet = file_get_contents($stylesheet);

      $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);

      $out = '';

      $response = array();

      $response["data"] = $data;
      $response["bMostrarLogo"] = true;

      $out.= view('cliente.pedidos.pedido_pdf')->with($response)->render();

      $mpdf->shrink_tables_to_fit = 0;

      $mpdf->WriteHTML($out);

      $out = "pedido_" . $data->idsadpedido;
      $out = $out."_".date("YmdHi");

      $mpdf->Output($out.'.pdf', ($request->get("download") == 'yes' ? 'D':'I'));
    }

    abort(404);
  }

}
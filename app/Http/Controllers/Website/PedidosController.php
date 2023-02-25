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

    $data = SppPedido::where("ckey",$ckey)->first();

    $response["data"] = $data;

    return view("website.carrito.carrito_form", $response)->render();
  }

  public function ShowResumenByCKey($ckey)
  {
    $response = array();

    $pedido = SppPedido::where("ckey", $ckey)->first();

    $response["data"] = $data;

    return view("website.carrito.carrito_resumen", $response)->render();

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
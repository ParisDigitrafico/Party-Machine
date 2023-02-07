<?php
namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Helpers\HtmlHelper;
use App\Helpers\ConektaHelper;

use App\Models\AccUsuario;
use App\Models\AccModulo;
use App\Models\AccPerfil;
use App\Models\AccConfiguracion;

use App\Models\SppPedido;

use App\Models\GenArchivo;

use App\Services\SystemService;

class PedidosController extends MController
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index(Request $request)
  {
    $response = array();

    $convencion = CncConvencion::find(1);

    $response["convencion"] = $convencion;

    return view('website.comprar.comprar_form', $response)->render();
  }

  public function show(Request $request, $id)
  {
    $response = array();

    $carnet = CncCarnet::find($id);

    if($carnet)
    {
      if($carnet->es_agotado == 1)
      {
        header("location:/");
        exit;
      }

      $hospedajes = CncHospedaje::select()->where("idconvencion", $carnet->idconvencion)->where("es_agotado",0)->get();

      $hospedajes = $hospedajes->sortBy(function($query){
                               return $query->hotel->nombre;
                            })->all();

      $response["carnet"]       = $carnet;

      $response["vuelos"]       = CncVuelo::select()->where("idconvencion", $carnet->idconvencion)->get();
      $response["hospedajes"]   = $hospedajes;

      $response["delegaciones"] = CatDelegacion::getDelegaciones();
      $response["sectores"]     = CatSector::select()->orderBy("nombre","asc")->get();

      /*$response["num_charter_disponibles"] = CncConvencion::obtenerNumVuelosCharterDisponibles($carnet->idconvencion);*/
      $response["num_charter_disponibles"] = 0;

      $arrAux = array();

      $records = CncHospedaje::select()->where("idconvencion", $carnet->idconvencion)->get();

      foreach($records as $record)
      {
        $arrAux[$record->idhotel] = CncConvencion::obtenerNumHabitacionesDisponibles($carnet->idconvencion, $record->idhotel);
      }

      $response["arrHospedaje"] = $arrAux;

      return view("website.comprar.comprar_form", $response)->render();
    }

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

    $pedido = SppPedido::where("ckey",$ckey)->first();

    if($pedido)
    {
      $idpedido = $pedido->id;

      $objSystemServ = new SystemService();

      $apiResponse = $objSystemServ->get_pedido($idpedido);

      $response["data"] = $apiResponse["data"];

      return view("website.carrito.carrito_form", $response)->render();
    }

    abort(404);
  }

  public function ShowResumenByCKey($ckey)
  {
    $response = array();

    $pedido = SppPedido::where("ckey", $ckey)->first();

    if($pedido)
    {
      $idpedido = $pedido->id;

      $objSysServ = new SystemService();

      $apiResponse = $objSysServ->get_pedido($idpedido);

      $response["data"] = $apiResponse["data"];

      return view("website.comprar.carrito_resumen", $response)->render();
    }

    abort(404);
  }

  public function ShowResumen(Request $request, $idpedido)
  {
    $response = array();

    $objLocalServ = new SystemService();

    $arrRes = $objLocalServ->get_pedidos($idpedido);

    $response["data_pedido"] = $arrRes["data"];

    return view("website.comprar.carrito_resumen", $response)->render();
  }

  public function ShowStatus(Request $request, $idpedido)
  {
    $response = array();

    $objLocalServ = new SystemService();

    $arrRes = $objLocalServ->get_pedidos($idpedido);

    $response["data_pedido"] = $arrRes["data"];

    return view("website.comprar.carrito_status", $response)->render();
  }

  public function crearresumen(Request $request)
  {
    $response = array();

    if(session("controlform") == true)
    {
      header("location:/");
      exit;
    }

    $objUsuario     = new AccUsuario();
    $objCarnet      = new CncCarnet();
    $objInscripcion = new CncInscripcion();

    try
    {
      $carnet = CncCarnet::find($request->get("idcarnet"));

      $Dato = $request->get("Dato");
      $DatoUser = $request->get("DatoUser");
      $optFacturacion = $request->get("optFacturacion");

      $cEmail = trim(strtolower($DatoUser["user"]));

      $cFormaPago = $request->get("txtFormaPago");

      $usuario = $objUsuario->withTrashed()->where("user",$cEmail)->first();

      $DatoInscripcion = array();

      if(!$usuario)
      {
        $DatoUser["es_cliente"] = 1;

        $objUsuario->AgregarUsuario($DatoUser);

        $DatoInscripcion["usuario_id"] = $objUsuario->latest('id')->first()->id;
      }
      else
      {
       $DatoInscripcion["usuario_id"] = $usuario->id;
      }

      $DatoInscripcion["idconvencion"] = $request->get("idconvencion");
      $DatoInscripcion["idcarnet"]     = $request->get("idcarnet");

      # form
      {
        if(!empty($optFacturacion))
        {
          $DatoInscripcion["razon_social"]    = $Dato["razon_social"];
          $DatoInscripcion["rfc"]             = $Dato["rfc"];
          $DatoInscripcion["correo_contacto"] = $Dato["correo_contacto"];

          if($request->hasfile('files'))
          {
            foreach($request->file('files') as $file)
            {
              $url = "/media/temp";
              $name = date("YmdHis") . '.' . get_random_string("3") .'.'.$file->extension();
              $file->move(base_path() . $url, $name);
            }
          }
        }

        if(!empty($Dato["socio"]))
        {
          $DatoInscripcion["socio"] = $Dato["socio"];
        }

        if(!empty($Dato["iddelegacion"]))
        {
          $DatoInscripcion["iddelegacion"] = $Dato["iddelegacion"];
        }

        if(!empty($Dato["idsector"]))
        {
          $DatoInscripcion["idsector"] = $Dato["idsector"];
        }

        if(!empty($Dato["con_acompaniante"]))
        {
          $DatoInscripcion["con_acompaniante"] = 1;

          if(!empty($Dato["nombre_acompaniante"]))
          {
            $DatoInscripcion["nombre_acompaniante"] = $Dato["nombre_acompaniante"];
          }
        }

        if(!empty($Dato["modo_llegada"]))
        {
          $DatoInscripcion["modo_llegada"] = $Dato["modo_llegada"];
        }

        if(!empty($request->get("fecha_salida")))
        {
          $DatoInscripcion["fecha_salida"] = $request->get("fecha_salida");
        }

        if($Dato["modo_llegada"] == "CHARTER")
        {
          $DatoInscripcion["fecha_llegada"] = $request->get("fecha_llegada_charter");

          if(!empty($Dato["idvuelosalida"]))
          {
            $DatoInscripcion["idvuelosalida"] = $Dato["idvuelosalida"];
          }
        }
        elseif($Dato["modo_llegada"] == "VUELO_COMERCIAL")
        {
          $DatoInscripcion["fecha_llegada"] = $request->get("fecha_llegada_comercial");

          if(!empty($Dato["num_vuelo"]))
          {
            $DatoInscripcion["num_vuelo"] = $Dato["num_vuelo"];
          }
        }
        elseif($Dato["modo_llegada"] == "CARRETERA")
        {
          $DatoInscripcion["fecha_llegada"] = $request->get("fecha_llegada_comercial");

          if(!empty($Dato["hora_llegada"]))
          {
            $DatoInscripcion["hora_llegada"] = $Dato["hora_llegada"];
          }
        }

        if(!empty($Dato["idhotel"]))
        {
          $DatoInscripcion["idhotel"] = $Dato["idhotel"];
        }
      }

      if(!empty($cFormaPago))
      {
        $DatoInscripcion["forma_pago"] = $cFormaPago;
      }

      if(in_array($cFormaPago, array("TRANSFERENCIA","PAGO_TARJETA")))
      {
        $DatoInscripcion["updated_at"] = now();

        $dIva = number_truncate(($carnet->precio * 0.16), 2);

        $DatoInscripcion["subtotal"] = $carnet->precio;
        $DatoInscripcion["iva"]      = $dIva;
        $DatoInscripcion["total"]    = $carnet->precio + $dIva;
        $DatoInscripcion["moneda"]   = "MXN";
      }

      $DatoInscripcion["created_at"] = now();

      $objInscripcion->insert($DatoInscripcion);

      $inscripcion = CncInscripcion::find($objInscripcion->latest('id')->first()->id);

      $response["carnet"]      = $carnet;
      $response["inscripcion"] = $inscripcion;

      if($inscripcion)
      {
        session()->put('controlform', true);
        session()->save();

        if(!empty($optFacturacion))
        {
          if($request->hasfile('file_constancia'))
          {
            foreach($request->file('file_constancia') as $file)
            {
              $directory = "/media/temp";
              $name = date("YmdHis") . '.' . get_random_string("3") . '.' . $file->extension();
              $file->move(public_path() . $directory . "/", $name);

              $Dato = array();

              $Dato["idinscripcion"] = $inscripcion->id;
              $Dato["clave"]         = 'CONSTANCIA_FISCAL';
              $Dato["url"]           = $directory . "/" . $name;
              $Dato["orden"]         = 1;

              GenArchivo::insert($Dato);

              continue;
            }
          }
        }

        if($inscripcion->forma_pago == "TRANSFERENCIA")
        {
          $objInscripcion->NotificarPendienteTransferenciaPago($inscripcion->id);

          header("location:/website/comprar/resumen/transferencia/".$inscripcion->id);
          exit;
        }
        else
        {
          $inscripcion->etapa = "PENDIENTE";

          $inscripcion->save();

          $objInscripcion->NotificarStatusInscripcion($inscripcion->id);

          header("location:/website/comprar/resumen/pago/tarjeta/".$inscripcion->id);
          exit;
        }
      }
      else
      {
        header("location:/");
        exit;
      }
    }
    catch(exception $ex)
    {
      $response["message"] = $ex->getMessage();

      header("location:/");
      exit;
    }
  }

}
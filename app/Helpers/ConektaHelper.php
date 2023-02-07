<?php
namespace App\Helpers;

class ConektaHelper
{
  var $apiKey;
  var $pubKey;

  function __construct()
  {
    if(config("app.env") == "production")
    {
      $this->pubKey = "key_XdCUFyLnsQhFCvZBeyJFcfW";
      $this->apiKey = "key_im3YU1v9n7Ha3xKOhhqPu2";
    }
    else
    {
      $this->pubKey = "key_AJTfL9qiWVPefxFsUNKLTvd";
      $this->apiKey = "key_PVLCG7p6aSHBxUI5axeiMf";
    }
  }

  public function CrearOrden($idorden)
  {
    $arrResponse = array();

    $arrResponse['status'] = false;

    $arrOrderItems = array();

    $objOrden        = new PgsOrden();
    $objOrdenDetalle = new PgsOrdenDetalle();

    \Conekta\Conekta::setApiKey($this->apiKey);

    try
    {
      if($ObjPagosOrden->Consultar("idorden", $idorden))
      {
        if($ObjPagosItem->Consultar("idorden", $ObjPagosOrden->idorden))
        {
          for($i=0; $i<$ObjPagosItem->NumeroRegistros; ++$i)
          {
            $item = array();

            if(!empty($ObjPagosItem->nombre))
            {
              $item['name'] = $ObjPagosItem->nombre;
            }

            if(!empty($ObjPagosItem->descripcion))
            {
              $item['description'] = $ObjPagosItem->descripcion;
            }

            if(!empty($ObjPagosItem->precio))
            {
              $item['unit_price']  = $ObjPagosItem->precio * 100;
            }

            if(!empty($ObjPagosItem->cantidad))
            {
              $item['quantity'] = $ObjPagosItem->cantidad;
            }

            $arrOrderItems[] = $item;

            $ObjPagosItem->Siguiente();
          }
        }

        $arrOrder = array(
        'line_items'=> $arrOrderItems,
        'currency'  => $ObjPagosOrden->moneda,
        );
      }

      $order = \Conekta\Order::create($arrOrder);

      if(!empty($order->_values["id"]))
      {
        $arrResponse['status'] = true;
        $arrResponse['clave']  = $order->_values["id"];
      }
    }
    catch (\Conekta\Handler $ex)
    {
      $arrResponse['error'] = $ex->getMessage();
    }

    return $arrResponse;
  }

  public function EditarOrden($cOrdenConekta, $arrOrder)
  {
    $arrResponse = array();
    $arrResponse['status'] = false;

    $ObjPagosOrden = new pagos_orden();
    $ObjPagosItem  = new pagos_ordendetalle();

    try
    {
      \Conekta\Conekta::setApiKey($this->apiKey);
      \Conekta\Conekta::setApiVersion($this->version);

      $order = \Conekta\Order::find($cOrdenConekta);

      $order->update($arrOrder);

      $arrResponse['status'] = true;
    }
    catch (\Conekta\Handler $error)
    {
      $arrResponse['error'] = $error->getMessage();
    }

    return $arrResponse;
  }

  public function EsPagado($cOrdenConekta)
  {
    $response = false;

    if(!empty($cOrdenConekta))
    {
      try
      {
        \Conekta\Conekta::setApiKey($this->apiKey);

        $order = \Conekta\Order::find($cOrdenConekta);

        $order = objectToArray($order);

        if(isset($order["charges"]["data"][0]["status"]) && $order["charges"]["data"][0]["status"] == "paid")
        {
          $response = true;
        }
      }
      catch(\Conekta\Handler $ex)
      {
        $response = false;
      }
    }

    return $response;
  }

  public function CrearCargoTarjeta($cOrdenConekta, $conektaTokenId, $cAmount)
  {
    $arrResponse = array();

    $arrResponse["success"] = false;

    try
    {
      \Conekta\Conekta::setApiKey($this->apiKey);

      $order = \Conekta\Order::find($cOrdenConekta);

      $charge = $order->createCharge(array(
        'payment_method' => array(
          'type'     => 'card',
          'token_id' => $conektaTokenId,
        ),
      ));

      /*$charge = $order->createCharge(
         [
            'amount' => $cAmount,
            'payment_method' => [
              'type'     => 'card',
              'token_id' => $conektaTokenId,
            ]
         ]
      );*/

      if($charge->status == 'paid')
      {
        $arrResponse["success"] = true;
      }
    }
    catch(\Conekta\Handler $ex)
    {
      $arrResponse["message"] = $ex->getMessage();
    }

    return $arrResponse;
  }

  public function CrearCargoOXXO($cOrdenConekta, $dtVigencia)
  {
    $arrResponse = array();

    $arrResponse["success"] = false;

    \Conekta\Conekta::setApiKey($this->apiKey);

    try
    {
      $order = \Conekta\Order::find($cOrdenConekta);

      $charge = $order->createCharge(array(
        "payment_method" => array(
          "type" => "oxxo_cash",
          "expires_at" => strtotime($dtVigencia),
        ),
      ));

      $arrResponse["success"]   = true;
      $arrResponse["reference"] = $charge->payment_method->reference;
    }
    catch(\Conekta\Handler $ex)
    {
      $arrResponse["message"] = $ex->getMessage();
    }

    return $arrResponse;
  }

  public function CrearCargoSPEI($cOrdenConekta, $dtVigencia)
  {
    $arrResponse = array();
    $arrResponse['status'] = false;

    \Conekta\Conekta::setApiKey($this->apiKey);
    \Conekta\Conekta::setApiVersion($this->version);

    try
    {
      $order = \Conekta\Order::find($cOrdenConekta);

      $charge = $order->createCharge(
        array(
          "payment_method" => array(
            "type" => "spei",
            "expires_at" =>strtotime($dtVigencia),
          )
        )
      );

      if(!empty($charge->payment_method->clabe))
      {
        $arrResponse['status'] = true;
        $arrResponse['banco']  = $charge->payment_method->receiving_account_bank;
        $arrResponse['clabe']  = $charge->payment_method->receiving_account_number;
      }
    }
    catch (\Conekta\Handler $error)
    {
      $arrResponse['error'] = $error->getMessage();
    }

    return $arrResponse;
  }

  public function ObtenerHtmlOXXO($arrDato=array())
  {
    $arrDefault = array(
      "total" => "9500",
      "moneda" => "MXN",
      "referencia" => "981273826327",
      "vigencia" => "12 agosto 2022",
    );

    $arrDato = array_merge($arrDefault, $arrDato);

    $respuesta = '
    <div class="opps">
    <div class="opps-header">
    <!--<div class="opps-reminder">Ficha digital. No es necesario imprimir.</div>-->
    <div class="opps-info">
    <div class="opps-brand"><img src="https://mundocontact.com/wp-content/uploads/2017/03/OXXO-PAY.jpg" alt="OXXOPay" width="150"></div>
    <div class="opps-ammount">
    <h3>Monto a pagar</h3>
    <h2>$ '.number_format($arrDato['total'], 2, ".", ",").' <sup>'.$arrDato['moneda'].'</sup></h2>
    <p>OXXO cobrará una comisión adicional al momento de realizar el pago.</p>
    </div>
    </div>
    <div class="opps-reference">
    <h3>Referencia</h3>

    <p style="text-align:center;">
    <img src="/plugins/barcode/barcode.php?f=jpg&s=code-128&d='.$arrDato['referencia'].'&sf=2&h=100" alt=""><br>
    <span style="font-size: 14pt; color: #000">'.separateString($arrDato['referencia']).'</span>
    </p>

    <p style="text-align:center">
    Fecha Vencimiento: '.$arrDato['vigencia'].'
    </p>

    </div>
    </div>
    <div class="opps-instructions">
    <h3>Instrucciones</h3>
    <ol>
    <li>Acude a la tienda OXXO más cercana. <a href="https://www.google.com.mx/maps/search/oxxo/" target="_blank">Encuéntrala aquí</a>.</li>
    <li>Indica en caja que quieres ralizar un pago de <strong>OXXOPay</strong>.</li>
    <li>Dicta al cajero el número de referencia en esta ficha para que tecleé directamete en la pantalla de venta.</li>
    <li>Realiza el pago correspondiente con dinero en efectivo.</li>
    <li>Al confirmar tu pago, el cajero te entregará un comprobante impreso. <strong>En el podrás verificar que se haya realizado correctamente.</strong> Conserva este comprobante de pago.</li>
    </ol>
    <div class="opps-footnote">Al completar estos pasos recibirás un correo de <strong>'.$this->nombreempresa.'</strong> confirmando tu pago.</div>
    </div>
    </div>';

    return $respuesta;
  }

}//fin de la clase
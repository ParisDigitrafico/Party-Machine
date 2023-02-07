@extends('website.master')

@push("content")
<div class="content">

@include("website.partials.breadcrumbs", array("title"=>"Resumen"))

<div class="container area_resumen_carrito" style="padding-top: 30px;">
  <!--<div class="row mb-5">
    <div class="col-md-12">
      <div class="bg-light rounded p-3">
        <p class="mb-0">Returning customer? <a href="#" class="d-inline-block">Click here</a> to login</p>
      </div>
    </div>
  </div>-->
  <div class="row">
    <div class="col-md-6 mb-5 mb-md-0">
      <h2 class="h3 mb-3 text-black">Instrucción de entrega</h2>
      <div class="p-3 p-lg-5 border">
        <div class="">
        <label style="padding: 5px;"><input type="radio" name="entrega"
          value="domicilio" />&nbsp;Entrega a Domicilio</label>

        <label style="padding: 5px; padding-left: 0;"><input type="radio" name="entrega"
          value="tienda" />&nbsp;Recoger en Farmacia</label>
        </div>

        <div class="row">
          <div class="col-12" style="padding-bottom: 10px;">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title"><label><input type="radio" name="direccion" value="1" />&nbsp;Dirección 1</label></h5>
                <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
                <p class="card-text">Dirección 1</p>
                <p class="card-text"></p>
              </div>
            </div>
          </div>

          <div class="col-12" style="padding-bottom: 10px;">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title"><label><input type="radio" name="direccion" value="2" />&nbsp;Dirección 2</label></h5>
                <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
                <p class="card-text">Dirección 2</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <!--<div class="row mb-5">
        <div class="col-md-12">
          <h2 class="h3 mb-3 text-black">Coupon Code</h2>
          <div class="p-3 p-lg-5 border">

            <label for="c_code" class="text-black mb-3">Enter your coupon code if you have one</label>
            <div class="input-group w-75">
              <input type="text" class="form-control" id="c_code" placeholder="Coupon Code" aria-label="Coupon Code" aria-describedby="button-addon2">
              <div class="input-group-append">
                <button class="btn btn-primary btn-sm px-4" type="button" id="button-addon2">Apply</button>
              </div>
            </div>

          </div>
        </div>
      </div>-->

      <div class="row mb-5">
        <div class="col-md-12">
          <div class="row">
            <div class="col-8"><h2 class="h3 mb-3 text-black">Resumen Orden</h2></div>
            <div class="col-4" style="text-align: right; padding-top: 16px;"><a href="/carrito/{{ $data['ckey'] }}/">Editar</a></div>
          </div>


          <div class="p-3 p-lg-5 border">
            <table class="table site-block-order-table mb-5">
              <thead>
              <tr>
                <th style="width: 60%">Producto</th>
                <th style="width: 10%; text-align: center">Cant.</th>
                <th style="width: 30%; text-align: right">Total</th>
              </tr></thead>
              <tbody>
                @if(!empty($arrAux = $data["detalle"]))
                @foreach($arrAux as $row)
                  <tr id="tr_{{ $row['id'] }}"
                      data-id="{{ $row['id'] }}"
                      data-codigoProducto="{{ $row['codigoProducto'] }}"
                      data-cantidad="{{ $row['cantidad'] }}"
                      >
                    <td>{{ $row["nombre"] }}</td>
                    <td style="text-align: center"><b>{{ $row["cantidad"] }}</b></td>
                    <td style="text-align: right">{{ number_format($row["precio"],2) }}&nbsp;MXN</td>
                  </tr>
                @endforeach
                @endif

              <tr>
                <td>Subtotal</td>
                <td><div style="text-align: center"></div></td>
                <td style="text-align: right">{{ number_format($data['subtotal'],2) }}&nbsp;MXN</td>
              </tr>

              <tr>
                <td>IVA.</td>
                <td><div style="text-align: center"></div></td>
                <td style="text-align: right">{{ number_format($data['iva'],2) }}&nbsp;MXN</td>
              </tr>

              <tr>
                <td><b>Total</b></td>
                <td><div style="text-align: center"></div></td>
                <td style="text-align: right"><b>{{ number_format($data['total'],2) }}</b>&nbsp;MXN</td>
              </tr>
              </tbody>
            </table>

            <div>
              <select class="optFormaPago">
                <option value="">-- Seleccione Forma de Pago --</option>
                <option value="tarjeta">Pago con Tarjeta</option>
                <option value="efectivo">Pago en Efectivo</option>
              </select>
            </div><br>

            <!--<div class="border mb-3" style="padding:10px;">

            </div>

            <div class="border mb-3 area_pago area_pago_tarjeta" style="padding:10px;">

            </div>

            <div class="border mb-3 area_pago area_pago_efectivo" style="padding:10px;">

            </div>-->

            <div class="form-group">
              <button
                class="btn btn-primary btn-lg btn-block btnVerificarFinalizar">Verificar existencias y Finalizar</button>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>
  <!-- </form> -->
</div>

</div>
@endpush

@push("js")
<script type="text/javascript">
$(document).ready(function($) {
/*  $(".btnVerificarPagar").click(function(evt) {
    evt.preventDefault();

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(geoSucc, geoErr, geoOpt);
    } else {
      $("#demo").html('Geolocation is not supported by this browser.');
    }
  });
*/
  function geoSucc(position)
  {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
    alert(latitude);
    alert(longitude);
  }

  function geoErr(error) {
    switch(error.code) {
      case error.PERMISSION_DENIED:
        $("#demo").html('User denied the request for Geolocation.');
        break;
      case error.POSITION_UNAVAILABLE:
        $("#demo").html('Location information is unavailable.');
        break;
      case error.TIMEOUT:
        $("#demo").html('The request to get user location timed out.');
        break;
      case error.UNKNOWN_ERROR:
        $("#demo").html('An unknown error occurred.');
        break;
    }
  }

  var geoOpt = {
    timeout: 3000,
    maximumAge: 30,
  }
});

componente_venta.init();
</script>
</script>
@endpush


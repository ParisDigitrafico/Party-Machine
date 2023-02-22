@extends('website.layouts.master')

@push("css")
<style type="text/css">
.radio-entrega{
  font-size:11pt;
  color:#102360;
  border:2px solid #102360;
  box-shadow:inset 0px 0.1em 0.1em rgba(0,0,0,0.3);
}

.radio-direccion{
  font-size:11pt;
  color:#102360;
  border:2px solid #102360;
  box-shadow:inset 0px 0.1em 0.1em rgba(0,0,0,0.3);
}

.modal-dialog
{
  max-width: 1200px;
}

.sucursales,
.sucursales section:nth-of-type(1)
{
  margin-top: 0;
  padding-top: 0;
}

.btnAzul
{
  border: 1px solid #102360; /*anchura, estilo y color borde*/
  padding: 10px; /*espacio alrededor texto*/
  background-color: #102360; /*color botón*/
  color: #ffffff; /*color texto*/
  text-decoration: none; /*decoración texto*/
  border-radius: 50px; /*bordes redondos*/
  cursor:pointer;
}

.btn-link,
.btnSel{
  border-radius: 50px;
}

</style>
@endpush

@push("content")
<div class="content">
  <!-- @include("website.partials.breadcrumbs", array("title"=>"Resumen")) -->
  <div class="container area_resumen_carrito">
      <!--<div class="row mb-5">
        <div class="col-md-12">
          <div class="bg-light rounded p-3">
            <p class="mb-0">Returning customer? <a href="#" class="d-inline-block">Click here</a> to login</p>
          </div>
        </div>
      </div>-->
      <div class="row">
        <div class="col-12 col-lg-6">
          <?php
          $horaRepartoInicia  = get_config_db('HOUR_DELIVER_START');
          $horaRepartoTermina = get_config_db('HOUR_DELIVER_END');

          $currentTime = strtotime(date("H:i"));
          $startTime   = strtotime($horaRepartoInicia);
          $endTime     = strtotime($horaRepartoTermina);
          ?>

          <h2 class="h3 mb-3 text-black">Instrucción de entrega</h2>

          @if(!($currentTime >= $startTime && $endTime >= $currentTime))
          <p>El horario para entregas a domicilio es de: {{ horaEspaniol($horaRepartoInicia) }} a {{ horaEspaniol($horaRepartoTermina) }} por su comprensión gracias. </p>
          @endif

          <div class="border">
            <div class="area_opciones" style="padding-left: 10px;">

            @if($currentTime >= $startTime && $endTime >= $currentTime)
            <label style="padding: 5px;"><input type="radio" name="formaentrega" class="checkradios radio-entrega optEntrega"
              value="domicilio" />&nbsp;<b>Entrega a Domicilio</b></label>
            @endif

            <label style="padding: 5px; padding-left: 0;"><input type="radio" name="formaentrega" class="checkradios radio-entrega optEntrega"
              value="farmacia" />&nbsp;<b>Recoger en Farmacia</b></label>
            </div>

            <div class="row forma_entrega_mensaje">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Seleccione una forma de entrega para su pedido.</h5>
                  </div>
                </div>
              </div>
            </div>

            @if($currentTime >= $startTime && $endTime >= $currentTime)
            <div class="row forma_entrega forma_entrega_direcciones">
              @if(isset($cliente["direccion"]) && !empty($cliente["direccion"]))
                @foreach($cliente["direccion"] as $direccion)

                @php
                $objBazar = new \App\Services\FbazarService();

                $apiResponse = $objBazar->get_colonia_by_codigo($direccion["codigoColonia"]);
                $colonia = $apiResponse["data"];
                @endphp

                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title" style="line-height: 1.2;"><label><input type="radio" name="direccion" class="optDireccion checkradios radio-direccion"
                      value="{{ $direccion['id'] }}" />&nbsp;<span style="font-weight: bold">{{ $colonia["descripcion"] }}, {{ $colonia["municipio"] }}</span></label></h5>

                      @if(trim($direccion["calle"]))
                      <p class="card-text">Calle: <b>{{ $direccion["calle"] }}</b></p>
                      @endif

                      @if(trim($direccion["numeroExterior"]))
                      <p class="card-text">No. Exterior: <b>{{ $direccion["numeroExterior"] }}</b></p>
                      @endif

                      @if(trim($direccion["numeroInterio"]))
                      <p class="card-text">No. Interior: <b>{{ $direccion["numeroInterio"] }}</b></p>
                      @endif

                      @if(trim($direccion["entre"]))
                      <p class="card-text">Cruzamientos: <b>{{ $direccion["entre"] }}</b></p>
                      @endif

                      @if(trim($direccion["codigoPostal"]))
                      <p class="card-text">C.P. <b>{{ $direccion["codigoPostal"] }}</b></p>
                      @endif

                      <p class="card-text"></p>
                    </div>
                  </div>
                </div>
                @endforeach

              @else
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Es necesario agregar una dirección de entrega, <a href="/cliente/direcciones/"
                        style="text-decoration:underline;">agrega una ahora</a>.</h5>
                  </div>
                </div>
              </div>
              @endif
            </div>
            @endif

            <div class="row forma_entrega forma_entrega_farmacia">
              <div class="col-12">
                <div class="card">
                  <div class="card-body plugin_mapa">
                    <h5 class="card-title"><span style="font-size: 14pt">Para continuar con la forma de entrega en farmacia, es necesario
                    ubicar el punto donde desea recojer su pedido.</span></h5>

                    <input type="hidden" id="codigoSucursal" name="codigoSucursal" value="" />

                    <div><h4 id="farmacia_nombre"></h4></div>
                    <div><h4 id="farmacia_direccion"></h4></div>
                    <div><h4 id="farmacia_codigo_postal"></h4></div>

                    <div style="text-align: right"><button class="btnEdit btnAzul" data-codigo-sucursal="">Buscar</button></div><br>


                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

        <div class="col-12 col-lg-6">
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

          <div class="row mb-5 resumen_ord">
            <div class="col-md-12">
              <div class="row">
                <div class="col-8"><h2 class="h3 mb-3 text-black">Resumen de Orden</h2></div>
                <div class="col-4" style="text-align: right; padding-top: 16px;">
                  <a class="btn_edit d-flex edit"  href="/carrito/{{ $data['ckey'] }}/">
                    <i class="title">Editar</i>
                    <i class="icon-edit"></i>
                  </a>
                  </div>

              </div>

              <div class="border">
                <div class="">
                  <table class="table site-block-order-table mb-5" style="width: 100%;">
                    <thead>
                    <tr>
                      <th style="width: 60%">Producto</th>
                      <th style="width: 10%; text-align: center">Precio</th>
                      <th style="width: 10%; text-align: center">Cant.</th>
                      <th style="width: 20%; text-align: right">Total</th>
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
                          <td style="text-align: right"><b>{{ number_format($row["precio"],2) }}</b></td>
                          <td style="text-align: center"><b>{{ $row["cantidad"] }}</b></td>
                          <td style="text-align: right">{{ number_format($row["total"],2) }}&nbsp;MXN</td>
                        </tr>
                      @endforeach
                      @endif


                    @if($data['subtotal'] > 0)
                    <tr>
                      <td style="text-align: right">Subtotal</td>
                      <td><div style="text-align: center"></div></td>
                      <td><div style="text-align: center"></div></td>
                      <td style="text-align: right">{{ number_format($data['subtotal'],2) }}&nbsp;MXN</td>
                    </tr>
                    @endif

                    @if($data['totalIva'] > 0)
                    <tr>
                      <td style="text-align: right">IVA.</td>
                      <td><div style="text-align: center"></div></td>
                      <td><div style="text-align: center"></div></td>
                      <td style="text-align: right">{{ number_format($data['totalIva'],2) }}&nbsp;MXN</td>
                    </tr>
                    @endif

                    @if($data['total'] > 0)
                    <tr>
                      <td style="text-align: right"><b>Total</b></td>
                      <td><div style="text-align: center"></div></td>
                      <td><div style="text-align: center"></div></td>
                      <td style="text-align: right"><b>{{ number_format($data['total'],2) }}</b>&nbsp;MXN</td>
                    </tr>
                    @endif

                    </tbody>
                  </table>
                </div>
                <div class="simple"
                  style="padding-bottom: 10px;font-size: 12pt">*Forma de Pago</div>

                  <select class="optFormaPago form-control">
                    <!--<option value="">-- Seleccione Forma de Pago --</option>-->
                    <!--<option value="tarjeta">Pago con Tarjeta</option>-->
                    <option value="">-- Seleccione --</option>
                    <option value="1">Efectivo</option>
                    <option value="4">Terminal Electrónica (Tarjeta Débito)</option>
                    <option value="5">Terminal Electrónica (Tarjeta Crédito)</option>
                  </select>
                </div>
                <br>

                <div class="area_su_pago">
                <div class="simple"
                  style="padding-bottom: 10px; font-size: 12pt;">*Su pago (Si requiere cambio)</div>

                  <select class="optSuPago form-control">
                    <!--<option value="">-- Seleccione Forma de Pago --</option>-->
                    <!--<option value="tarjeta">Pago con Tarjeta</option>-->
                    <option value="">-- Seleccione --</option>
                    <option value="{{ $data['total'] }}">{{ number_format($data['total'],2) }} MXN (Exacto)</option>

                    @foreach($pagos as $pago)
                    <option value="{{ $pago }}">{{ $pago }} MXN</option>
                    @endforeach

                  </select>
                </div>
                <!-- <br> -->

                <!--<div class="border mb-3" style="padding:10px;">

                </div>

                <div class="border mb-3 area_pago area_pago_tarjeta" style="padding:10px;">

                </div>

                <div class="border mb-3 area_pago area_pago_efectivo" style="padding:10px;">

                </div>-->

                <div class="form-group">
                 <a class="btn btn-primary">
                    <span>Verificar existencias y finalizar</span>
                    <button
                    class="btn-block btnVerificarFinalizar"
                    data-idpedido="{{ $data['id'] }}" data-ckey="{{ $ckey }}"></button>
                  </a>
                </div>

              </div>
            </div>
          </div>

      </div>
  </div>
  <!-- </form> -->
</div>
@endpush

@push("js")
<script type="text/javascript">
$('.checkradios').checkradios({
  checkbox: {
    iconClass:'fas fa-circle'
  },

  radio: {
    iconClass:'fas fa-circle'
  }
});

$(document).on("click", ".btnEdit", function(evt){
  evt.preventDefault();

  $btnEdit = $(this);

  codigoSucursal = $btnEdit.data("codigo-sucursal") || "";
  codigoSucursal = codigoSucursal.toString();

  cTxtInitialBtn = $btnEdit.text();
  $btnEdit.text("Cargando...").prop('disabled', true);

  var ajx = $.get('/sucursales/mapa/', { 'codigoSucursal': codigoSucursal}, null, 'html');

  ajx.done(function(response){
    bootstrap.showModal({
      title: 'Seleccione una sucursal',
      body: response,
      footer: '<button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancel</button>',
      onShown: function (modal) {
        $modal = $(modal.element);

        componente_mapa.load_mapa_sucursales(true);

        $(document).off("click", ".btnSel").on("click", ".btnSel", function(evt){
          evt.preventDefault();

          $btnSel = $(this);

          iAux = $btnSel.data("sucursal-codigo") || "";
          iAux = parseInt(iAux);

          if(iAux > 0)
          {
            $btnEdit.data("codigo-sucursal", iAux);

            cSucursalNombre = $btnSel.data("sucursal-nombre") || "";
            cSucursalDir    = $btnSel.data("sucursal-direccion") || "";
            cSucursalCp     = $btnSel.data("sucursal-codigo-postal") || "";

            $("#codigoSucursal").val(iAux);
            $("#farmacia_nombre").html("<b>" + cSucursalNombre + "</b>");
            $("#farmacia_direccion").html("<b>Dirección:</b> " + cSucursalDir);
            $("#farmacia_codigo_postal").html("<b>Código Postal:</b> " + cSucursalCp);

            util.setStorage("sucursal_codigo",iAux,60);
            util.setStorage("sucursal_nombre",cSucursalNombre,60);
            util.setStorage("sucursal_direccion",cSucursalDir,60);
            util.setStorage("sucursal_codigo_postal",cSucursalCp,60);
          }

          modal.hide();
        });

      }
    });

    ajx.always(function(){
      $btnEdit.text("Buscar").prop('disabled', false);
    });


  });

});
</script>
@endpush


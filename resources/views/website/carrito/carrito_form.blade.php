@extends('website.layouts.default')

@push("css")
<style type="text/css">
/*.js-btn-minus{
background: #D7D7D7;
height: 28px;
width: 28px;
margin-right: 5px;
box-shadow: 0 3px 6px rgba(0,0,0,0.3);
display: block;
position: relative;
}

.js-btn-plus{
background: #D7D7D7;
height: 28px;
width: 28px;
margin-right: 5px;
box-shadow: 0 3px 6px rgba(0,0,0,0.3);
display: block;
position: relative;
}*/

</style>
@endpush

@push("main")
<div class="content pagina_carrito">


<section class="ftco-section area_carrito">
<div class="container">
<div class="row">
<div class="col-lg-12">

@if(request()->get("j64"))
<div class="alert alert-warning">
  <p style="margin:0;font-size: 12pt">
Es necesario ajustar la cantidad de productos en el pedido, puede realizar esta acción de forma automatica,
pulsando el botón <b>Ajustar Todo</b>.
</p>
</div>
<br>
@endif

<h2>Carrito</h2>
<div class="table-responsive">
  <table class="table" data-ckey="{{ $data['ckey'] }}">
    <thead>
      <tr>
        <th scope="col" style="width: 30%;">Producto</th>
        <th scope="col" >Precio</th>
        <th scope="col">Cantidad</th>

        @if(request()->get("j64"))
        <th scope="col">
        Stock disponible
        <div style="padding-top: 6px;">
        <a href="#" class="btnAjustarTodos"><span
         style="color: #56AB2B; text-decoration: underline;">Ajustar Todo </span></a>
         <a class="help" data-toggle="popover" title="Al hacer clic en el botón se actualiza automáticamente a la cantidad de productos disponible">
           <i class="icon-help"></i>
         </a>

        </div>
        </th>
        @endif

        <th scope="col" >Total</th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody>
    @if(!empty($detalle = $data["detalle"]))

    @php
    $arrAux = array();

    if(request()->get("j64"))
      $arrAux = json_decode(base64_decode(request()->get("j64")), true);
    @endphp

    @foreach($detalle as $item)
      <tr>
        <td>
          <div class="align_center">
            <div class="img lazy" data-src="{{ get_photo_bazar($item['codigoRelacionado']) }}"></div>  <span>{{ $item["nombre"] }}</span>
          </div>
        </td>
        <td>
          @if($item["precioUnitario"] > $item["precio"])
          <span style="text-decoration:line-through;">${{ number_format($item["precioUnitario"], 2) }} MXN</span><br>
          @endif

          <strong>${{ number_format($item["precio"], 2) }} MXN</strong>
        </td>
        <td><div class="input-group mb-3" style="max-width: 120px;">
                          <div class="input-group-prepend">
                            <button class="btn btn-outline-primary js-btn-minus" type="button">−</button>
                          </div>
                          <input type="text" class="form-control text-center txtCantidad" value="{{ $item['cantidad'] }}" placeholder=""
                              data-ckey="{{ $data['ckey'] }}"
                              data-producto-id="{{ $item['idProducto'] }}"
                              data-producto-codigo="{{ $item['codigoProducto'] }}"
                              data-existencia="{{ $aux['existencia'] }}"
                              aria-label="Example text with button addon" aria-describedby="button-addon1" />
                          <div class="input-group-append">
                            <button class="btn btn-outline-primary js-btn-plus" type="button">+</button>
                          </div>
                        </div></td>

                        @if(request()->get("j64"))
                        <td>
                          @php
                          $aux = array_search_first($arrAux, "codigoProducto", $item["codigoProducto"]);
                          @endphp

                          @if($item['cantidad'] > $aux["existencia"])
                          <a href="#" class="btnAjustar btnAjustar_{{ $item['codigoProducto'] }}" title="Ajustar Cantidad"
                           data-existencia="{{ $aux['existencia'] ?? 0 }}"
                           style="text-decoration:underline;">
                           @if($aux["existencia"] > 0)
                           <span style="font-size: 12pt; font-weight: bold; color: #FF6600;">Ajustar Cantidad</span>
                           @else
                           <span style="font-size: 12pt; font-weight: bold; color: #FF6600;">Sin Existencias<br>(Quitar)</span>
                           @endif
                          </a>
                          @else
                          <span class="btnAjustar_{{ $item['codigoProducto'] }}"
                            data-existencia="{{ $aux['existencia'] ?? 0 }}"
                            style="font-size: 16pt; font-weight: bold; color:blue;"><i class="fas fa-check-circle"></i></span>
                          @endif

                        </td>
                        @endif

        <td>
          <strong>
            ${{ number_format($item["total"], 2) }} MXN
          </strong>
        </td>
        <td>
            <a href="#" class="btnRemove"
               data-ckey="{{ $data['ckey'] }}"
               data-producto-id="{{ $item['idProducto'] }}"
               data-producto-codigo="{{ $item['codigoProducto'] }}">
              <i class="title">Quitar</i>
              <i class="icon-delete"></i>
            </a>
        </td>
      </tr>
    @endforeach
    @endif
    </tbody>
  </table>
</div>
</div><!--table-responsive"-->

<div class="col-md-6 col-sm-12 col-xs-12 col d-none">
  <div class="row mb-5">
    <div class="col-md-6 mb-3 mb-md-0">
      <!--<button class="btn btn-primary btn-md btn-block">Update Cart</button> -->
    </div>
    <div class="col-md-6">
      <!--<button class="btn btn-outline-primary btn-md btn-block">Continuar Comprando</button>  -->
    </div>
  </div>
  <div class="row">
    <!--<div class="col-md-12">
      <label class="text-black h4" for="coupon">Coupon</label>
      <p>Enter your coupon code if you have one.</p>
    </div>
    <div class="col-md-8 mb-3 mb-md-0">
      <input type="text" class="form-control py-3" id="coupon" placeholder="Coupon Code">
    </div>
    <div class="col-md-4">
      <button class="btn btn-primary btn-md px-4">Apply Coupon</button>
    </div>-->
  </div>
</div>

<div class="offset-lg-6 offset-md-6 col-md-6 col-sm-12 col-xs-12">
  <div class="row justify-content-end">
    <div class="col-md-12 col-lg-10 col-sm-12 col-xs-12  total_card">
      <!--<div class="row">
        <div class="col-md-12 text-right border-bottom mb-5">
          <h3 class="text-black h4 text-uppercase">Totales</h3>
        </div>
      </div>-->

      @if($data["subtotal"] > 0)
      <div class="row">
        <div class="col-md-6 col">
          <span class="text-black">Subtotal</span>
        </div>
        <div class="col-md-6 col text-right">
          ${{ number_format($data["subtotal"],2) }}&nbsp;MXN
        </div>
      </div>
      @endif

      @if($data["totalIva"] > 0)
      <div class="row">
        <div class="col-md-6 col">
          <span class="text-black">IVA</span>
        </div>
        <div class="col-md-6 col text-right">
          ${{ number_format($data["totalIva"],2) }}&nbsp;MXN
        </div>
      </div>
      @endif

      @if($data["total"] > 0)
      <div class="row mb-4">
        <div class="col-md-6 col">
          <span class="text-azul"><b>Total</b></span>
        </div>
        <div class="col-md-6 col text-right">
          <span  class="text-azul">
            <b>${{ number_format($data["total"],2) }}&nbsp;MXN</b>
          </span>
        </div>
      </div>
      @endif

      <div class="row">
        <div class="col-md-12">
          @if(0)
          <a href="#" class="btn btn-primary btn-lg btn-block btnAjustarTodos"
          style="">Ajustar Todo y Continuar</a>
          @else
          <a href="#" class="btn btn-primary btn-lg btn-block btnSiguiente" data-idcliente="{{ intval(session('cliente_id')) }}"
          data-url="/carrito/{{ $data['ckey'] }}/resumen/">
            <span>Siguiente Paso</span>
          </a>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

</div>
</div>
</section>

</div>
@endpush

@push("js")
<script type="text/javascript">
$(document).ready(function(){
  $('[data-toggle="popover"]').popover();
});
</script>
@endpush



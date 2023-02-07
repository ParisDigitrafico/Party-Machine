@extends('website.master')

@push("content")
<div class="content">

@include("website.partials.breadcrumbs", array("title"=>"Carrito"))

<section class="ftco-section item_carrito">
<div class="container">
<div class="row">
<div class="col-lg-12">
<table class="table">
  <thead>
    <tr>
      <th scope="col">Imagen</th>
      <th scope="col" style="width: 30%;">Producto</th>
      <th scope="col">Precio Unitario</th>
      <th scope="col">Cantidad</th>
      <th scope="col">Total</th>
      <th scope="col">Quitar</th>
    </tr>
  </thead>
  <tbody>
  @if(!empty($detalle = $data["detalle"]))
  @foreach($detalle as $item)
    <tr>
      <td><img src="{{ $item['imagenUrl'] }}" alt="" style="max-height: 100px;" /></td>
      <td>{{ $item["nombre"] }}</td>
      <td>{{ number_format($item["precio"], 2)  }}</td>
      <td><div class="input-group mb-3" style="max-width: 120px;">
                        <div class="input-group-prepend">
                          <button class="btn btn-outline-primary js-btn-minus" type="button">−</button>
                        </div>
                        <input type="text" class="form-control text-center" value="{{ $item['cantidad'] }}" placeholder=""
                            aria-label="Example text with button addon" aria-describedby="button-addon1">
                        <div class="input-group-append">
                          <button class="btn btn-outline-primary js-btn-plus" type="button">+</button>
                        </div>
                      </div></td>
      <td>{{ number_format($item["total"], 2)  }}</td>
      <td><a href="#">Quitar</a></td>
    </tr>
  @endforeach
  @endif
  </tbody>
</table>
</div>

<div class="col-md-6">
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

<div class="col-md-6 pl-5">
  <div class="row justify-content-end">
    <div class="col-md-7">
      <!--<div class="row">
        <div class="col-md-12 text-right border-bottom mb-5">
          <h3 class="text-black h4 text-uppercase">Totales</h3>
        </div>
      </div>-->
      <div class="row mb-3">
        <div class="col-md-6">
          <span class="text-black">Subtotal</span>
        </div>
        <div class="col-md-6 text-right">
          <strong class="text-black">{{ $data["subtotal"] }}</strong>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <span class="text-black">IVA</span>
        </div>
        <div class="col-md-6 text-right">
          <strong class="text-black">{{ $data["iva"] }}</strong>
        </div>
      </div>

      <div class="row mb-5">
        <div class="col-md-6">
          <span class="text-black"><b>Total</b></span>
        </div>
        <div class="col-md-6 text-right">
          <strong class="text-black">{{ $data["total"] }}</strong>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <a href="#" class="btn btn-primary btn-lg btn-block btnSiguiente" data-idcliente="">Siguiente Paso (Cliente no logueado)</a>
          <a href="#" class="btn btn-primary btn-lg btn-block btnSiguiente" data-idcliente="1"
                    data-url="/carrito/{{ $data['id'] }}/resumen/">Siguiente Paso</a>
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

</script>
@endpush



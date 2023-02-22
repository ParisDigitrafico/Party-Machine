@extends('website.layouts.master')

@push("content")
<div class="content">

@include("website.partials.breadcrumbs", array("title"=>"Carrito Status"))

<div class="container">
  <div class="row">
    <div class="col-md-12 text-center">
      <br><br>
      @if($data_pedido["etapa"] == "ENTREGADO")
      <span class="icon-check_circle display-3 text-primary"></span>
      <h2 class="display-3 text-black">¡ Entrega exitosa !</h2>
      <p class="lead mb-5">Este pedido ha sido entregado con exito.</p>
      @elseif($data_pedido["etapa"] == "ENVIADO")
      <span class="icon- display-3 text-primary"></span>
      <h2 class="display-3 text-black">¡ Producto Enviado !</h2>
      <p class="lead mb-5">Esta pedido esta pagada y esta en proceso de entrega.</p>
      @else
      <script type="text/javascript">
      location.href = "/website/productos/";
      </script>
      @endif
      <p><a href="/website/productos/" class="btn btn-md height-auto px-4 py-3 btn-primary">Regresar a la tienda</a></p>
      <br><br>
    </div>
  </div>
</div>
</div>

@endpush

@push("js")
<script type="text/javascript">

</script>
@endpush






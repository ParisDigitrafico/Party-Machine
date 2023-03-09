@extends('website.layouts.default')

@push("css")
<style type="text/css">
.radio-entrega {
    font-size: 11pt;
    color: #102360;
    border: 2px solid #102360;
    box-shadow: inset 0px 0.1em 0.1em rgba(0, 0, 0, 0.3);
}

.radio-direccion {
    font-size: 11pt;
    color: #102360;
    border: 2px solid #102360;
    box-shadow: inset 0px 0.1em 0.1em rgba(0, 0, 0, 0.3);
}

.modal-dialog {
    max-width: 1200px;
}

.sucursales,
.sucursales section:nth-of-type(1) {
    margin-top: 0;
    padding-top: 0;
}

.btnAzul {
    border: 1px solid #102360;
    /*anchura, estilo y color borde*/
    padding: 10px;
    /*espacio alrededor texto*/
    background-color: #102360;
    /*color botón*/
    color: #ffffff;
    /*color texto*/
    text-decoration: none;
    /*decoración texto*/
    border-radius: 50px;
    /*bordes redondos*/
    cursor: pointer;
}

.btn-link,
.btnSel {
    border-radius: 50px;
}
</style>
@endpush

@push("main")
<div class="content">

    <div class="container area_resumen_carrito">

        <div class="row">

            <div class="col-12 col-lg-6">

                <div class="row mb-5 resumen_ord">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-8">
                                <h2 class="h3 mb-3 text-black">Resumen de Orden</h2>
                            </div>
                            <div class="col-4" style="text-align: right; padding-top: 16px;">
                                <a class="btn_edit d-flex edit" href="/carrito/{{ $data['ckey'] }}/">
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($detalles = $data->detalles))
                                        @foreach($detalles as $item)
                                        <tr>
                                            <td>{{ $item->producto_nombre }}</td>
                                            <td style="text-align: right"><b>{{ number_format($item->precio,2) }}</b>
                                            </td>
                                            <td style="text-align: center"><b>{{ $item->cantidad }}</b></td>
                                            <td style="text-align: right">{{ number_format($item->total,2) }}&nbsp;MXN
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif


                                        @if($data['subtotal'] > 0)
                                        <tr>
                                            <td style="text-align: right">Subtotal</td>
                                            <td>
                                                <div style="text-align: center"></div>
                                            </td>
                                            <td>
                                                <div style="text-align: center"></div>
                                            </td>
                                            <td style="text-align: right">
                                                {{ number_format($data['subtotal'],2) }}&nbsp;MXN</td>
                                        </tr>
                                        @endif

                                        @if($data['totalIva'] > 0)
                                        <tr>
                                            <td style="text-align: right">IVA.</td>
                                            <td>
                                                <div style="text-align: center"></div>
                                            </td>
                                            <td>
                                                <div style="text-align: center"></div>
                                            </td>
                                            <td style="text-align: right">
                                                {{ number_format($data['totalIva'],2) }}&nbsp;MXN</td>
                                        </tr>
                                        @endif

                                        @if($data['total'] > 0)
                                        <tr>
                                            <td style="text-align: right"><b>Total</b></td>
                                            <td>
                                                <div style="text-align: center"></div>
                                            </td>
                                            <td>
                                                <div style="text-align: center"></div>
                                            </td>
                                            <td style="text-align: right">
                                                <b>{{ number_format($data['total'],2) }}</b>&nbsp;MXN
                                            </td>
                                        </tr>
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                            <div class="simple" style="padding-bottom: 10px;font-size: 12pt">*Forma de Pago</div>

                            <select class="optFormaPago form-control">
                                <!--<option value="">-- Seleccione Forma de Pago --</option>-->
                                <!--<option value="tarjeta">Pago con Tarjeta</option>-->
                                <option value="">-- Seleccione --</option>
                                <!-- <option value="1">Efectivo</option> -->
                                <option value="4">Terminal Electrónica (Tarjeta Débito)</option>
                                <option value="5">Terminal Electrónica (Tarjeta Crédito)</option>
                            </select>
                        </div>
                        <br>

                        <div class="area_su_pago">
                            <div class="simple" style="padding-bottom: 10px; font-size: 12pt;">*Su pago (Si requiere
                                cambio)</div>

                            <select class="optSuPago form-control">
                                <!--<option value="">-- Seleccione Forma de Pago --</option>-->
                                <!--<option value="tarjeta">Pago con Tarjeta</option>-->
                                <option value="">-- Seleccione --</option>
                                <option value="{{ $data['total'] }}">{{ number_format($data['total'],2) }} MXN (Exacto)
                                </option>


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
                            <a class="btn btn-primary btnComprar" data-pedido_id="{{ $data['id'] }}" data-ckey="{{ $ckey }}">
                                <span>Comprar</span>
                                <!-- <i class="fas fa-long-arrow-alt-right ms-2"></i> -->
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

</script>
@endpush
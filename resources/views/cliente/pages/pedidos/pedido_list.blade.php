@extends('website.layouts.cliente')

@push("main")

<div class="col-lg-12">
    <div class="container py-5">
        <table class="table-borderless m-auto">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>No. Pedido</th>
                    <th>Forma Entrega</th>
                    <th>Código Guía</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Forma Pago</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Dom</td>
                    <td>6000</td>
                </tr>
                <tr class="active-row">
                    <td>Melissa</td>
                    <td>5150</td>
                </tr>
                <!-- and so on... -->
            </tbody>
        </table>
    </div>
</div>

@endpush

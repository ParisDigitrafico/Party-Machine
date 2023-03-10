@extends('website.layouts.cliente')

@push("main")

<div class="col-lg-12">
    <div class="container py-5">
        <table class="table-borderless m-auto">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Forma Pago</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                <tr>
                    <td>{{ fechaespaniol($item->created_at) }}</td>
                    <td></td>
                    <td>${{ $item->total }}&nbsp;MXN</td>
                    <td></td>
                    <td></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endpush

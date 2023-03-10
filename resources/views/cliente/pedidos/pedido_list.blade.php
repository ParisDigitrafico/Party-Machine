@extends('website.layouts.cliente')

@push('main')
<div class="content dash pt-4">
  <div class="container">
    <div class="row d-flex">

      <div class="col-12 py-4">
          <h3>Mis Pedidos</h3>
      </div>

      <div class="col-12 mb-2">
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
  </div>
</div>
@endpush

@extends('sistema.layouts.master')

@push('content')
<!--<div class="row">
<div class="col-md-12">
<div class="overview-wrap">
<h2 class="title-1">overview</h2>
<button class="au-btn au-btn-icon au-btn--blue" id="btnRPanel" >
<i class="zmdi zmdi-plus"></i>add item</button>
</div>
</div>
</div>-->

<div class="row m-t-25">

@if(!empty($item = $clientes))
<div class="col-xl-6 col-lg-6 col-md-6">
<div class="card card-park">
<div class="row" style="margin-right: 0px;">
<div class="col-6"><h5 class="card-header">Clientes</h5></div>
<div class="col-6"><div class="card-icon"><i class="fas fa-address-book"></i></div></div>
</div>

<div class="card-body">
<div class="row">
<div class="col-6 mb-3"><div style="text-align: center">{{ $item["activos"] }}<br>Activos</div></div>
<div class="col-6 mb-3"><div style="text-align: center">{{ $item["inactivos"] }}<br>Inactivos</div></div>
<div class="col-6 mb-3"><div style="text-align: center">{{ $item["eliminados"] }}<br>Eliminados</div></div>

</div>


</div>
</div>
</div>
@endif


@if(!empty($item = $inscripciones))
<div class="col-xl-6 col-lg-6 col-md-6">
<div class="card card-park">
<div class="row" style="margin-right: 0px;">
<div class="col-6"><h5 class="card-header">Inscripciones</h5></div>
<div class="col-6"><div class="card-icon"><i class="fas fa-book-reader"></i></div></div>
</div>

<div class="card-body">
<div class="row">
<div class="col-4 mb-3"><div style="text-align: center">{{ $item["pendientes"] }}<br>Pendientes</div></div>
<div class="col-4 mb-3"><div style="text-align: center">{{ $item["apartados"] }}<br>Apartados</div></div>
<div class="col-4 mb-3"><div style="text-align: center">{{ $item["cortesias"] }}<br>Cortesías</div></div>
<div class="col-4 mb-3"><div style="text-align: center">{{ $item["pagados"] }}<br>Pagados</div></div>
<div class="col-4 mb-3"><div style="text-align: center">{{ $item["cancelados"] }}<br>Cancelados</div></div>

</div>


</div>
</div>
</div>
@endif

@if(!empty($brazaletes))
<div class="col-xl-6 col-lg-6 col-md-6">
<div class="card card-park">
<div class="row" style="margin-right: 0px;">
<div class="col-6"><h5 class="card-header">Brazaletes</h5></div>
<div class="col-6"><div class="card-icon"><i class="fas fa-barcode"></i></div></div>
</div>

<div class="card-body">

</div>

</div>
</div>
@endif

@if(!empty($reservaciones))
<div class="col-xl-6 col-lg-6 col-md-6">
<div class="card card-park">
<div class="row" style="margin-right: 0px;">
<div class="col-6"><h5 class="card-header">Reservaciones</h5></div>
<div class="col-6"><div class="card-icon"><i class="fas fa-calendar"></i></div></div>
</div>

<div class="card-body">
<div class="row">
<div class="col-6 mb-3"><div style="text-align: center">{{ $reservaciones["pendientes"] }}<br>Pendientes</div></div>
<div class="col-6 mb-3"><div style="text-align: center">{{ $reservaciones["confirmados"] }}<br>Confirmados</div></div>
<div class="col-6 mb-3"><div style="text-align: center">{{ $reservaciones["pagados"] }}<br>Pagados</div></div>
<div class="col-6 mb-3"><div style="text-align: center">{{ $reservaciones["cancelados"] }}<br>Cancelados</div></div>
</div>
</div>
</div>
</div>
@endif

@if(!empty($notificaciones) && 1==0)
<div class="col-xl-6 col-lg-6 col-md-6">
<div class="card card-park">

<div class="row" style="margin-right: 0px;">
<div class="col-6"><h5 class="card-header">Últimas notificaciones</h5></div>
<div class="col-6"><div class="card-icon"><i class="fas fa-address-book"></i></div></div>
</div>

<div class="card-body">
@foreach($notificaciones as $row)

@endforeach
</div>

</div>
</div>
@endif

</div>
@endpush

@push('js')

@endpush
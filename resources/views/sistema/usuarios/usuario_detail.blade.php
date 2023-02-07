<div class="area_input area_simple">

<div class="row">
<div class="col-lg-11">
<div class="row">

@if(!empty($cAux = $data->codigo))
<div class="col-lg-6">
<b>Código:</b>&nbsp;{{ $cAux }}
</div>
@endif

@if(!empty($cAux = $data->user))
<div class="col-lg-11">
<b>Usuario:</b>&nbsp;{{ $cAux }}
</div>
@endif

@if(!empty($cAux = $data->nombre))
<div class="col-lg-6">
<b>Nombre:</b>&nbsp;{{ $cAux }}
</div>
@endif

@if(!empty($cAux = $data->apellidos))
<div class="col-lg-6">
<b>Apellidos:</b>&nbsp;{{ $cAux }}
</div>
@endif

@if(!empty($cAux = $data->apellido_pat))
<div class="col-lg-6">
<b>Apellido Paterno:</b>&nbsp;{{ $cAux }}
</div>
@endif

@if(!empty($cAux = $data->apellido_mat))
<div class="col-lg-6">
<b>Apellido Materno:</b>&nbsp;{{ $cAux }}
</div>
@endif

@if(!empty($cAux = $data->telefono))
<div class="col-lg-6">
<b>Teléfono:</b>&nbsp;{{ $cAux }}
</div>
@endif

@if(!empty($cAux = $data->created_at))
<div class="col-lg-6">
<b>Fecha Alta:</b>&nbsp;{!! FechaEspaniol($cAux, true).'&nbsp;hrs' !!}
</div>
@endif



</div>

</div>
</div>

</div>

<div class="area_button" style="width: 100%; padding: 10px 4px; border-top: 1px solid #e8e8e8;">
<button type="submit" class="btn btn-secondary btn-sm CLOSE_AJAX">
Cerrar
</button>
</div>
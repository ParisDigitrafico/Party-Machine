<div class="area_input area_simple">

<div class="row">
<div class="col-lg-11">
<div class="row">

@if(!empty($cAux = $data->clave))
<div class="col-lg-10">
<b>Clave:</b>&nbsp;{{ $cAux }}
</div>
@endif

@if(!empty($cAux = $data->nombre))
<div class="col-lg-10">
<b>Nombre:</b>&nbsp;{{ $cAux }}
</div>
@endif

@if(!empty($cAux = $data->created_at ))
<div class="col-lg-10">
<b>Fecha:</b>&nbsp;{{ FechaEspaniol($cAux, true ) }}
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
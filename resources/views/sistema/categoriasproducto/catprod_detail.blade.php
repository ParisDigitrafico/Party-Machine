<div class="area_input" style="width: 100%; padding:4px; height: calc(100% - 60px); overflow: hidden; overflow-y: hidden; overflow-y: scroll;">

<div class="row">
<div class="col-lg-11">
<div class="row">

@if(!empty($cAux = $Dato["clave"]))
<div class="col-lg-6">
<b>Clave:</b>&nbsp;{{ $cAux }}
</div>
@endif

@if(!empty($cAux = $Dato["nombre"]))
<div class="col-lg-6">
<b>Nombre:</b>&nbsp;{{ $cAux }}
</div>
@endif

@if(!empty($cAux = $Dato["direccion"]))
<div class="col-lg-6">
<b>Dirección:</b>&nbsp;{{ $cAux }}
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
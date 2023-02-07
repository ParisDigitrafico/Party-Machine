<!--<form action="descargarrespaldo.php" style="padding:0; height: 100%;">-->
<form action="{{ $url }}" method="post" style="padding:0; height: 100%;">
<div class="area_input" style="width: 100%; padding:4px; height: calc(100% - 60px); overflow: hidden; overflow-y: hidden; overflow-y: scroll;">
<input type="hidden" name="_token" value="{{ csrf_token() }}" />

@if($mostrar_desde === true)
<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">Desde el</label>
<div class="input-group datetimepicker">
<input type="text" name="fecha_desde" value="" class="form-control date required"
data-msg-required="El campo <b>Desde el</b> es obligatorio."
style="" />
<div class="input-group-append btnClear" style="cursor: pointer;" title="Limpiar">
<div class="input-group-text"><i class="fa fa-times"></i></div>
</div>
</div>
</div>
</div>
@endif

@if($mostrar_hasta === true)
<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">Hasta el</label>
<div class="input-group datetimepicker">
<input type="text" name="fecha_hasta" value="" class="form-control date required"
data-msg-required="El campo <b>Hasta el</b> es obligatorio."
style="" />
<div class="input-group-append btnClear" style="cursor: pointer;" title="Limpiar">
<div class="input-group-text"><i class="fa fa-times"></i></div>
</div>
</div>
</div>
</div>
@endif

@if(!empty($arr = $arrComision) && is_array($arr))
<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">Comisi&oacute;n</label>
<select name="idcomision" class="form-control required"
data-msg-required="El campo <b>Comisi&oacute;n</b> es obligatorio."
style="" />
<option value="">-- Seleccione --</option>
@foreach($arr as $row)
<option value="{{ $row['id'] }}">{{ $row['nombre'] }}</option>
@endforeach
</select>
</div>
</div>
@endif

<!--<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">Nombre*</label>
<input type="text" name="Dato[nombre]" value="{{ $Dato['nombre'] }}" class="form-control required"
data-msg-required="Campo <b>Nombre</b> obligatorio."
style="" />
</div>
</div>-->



</div>

<div class="area_button" style="width: 100%; padding: 10px 4px; border-top: 1px solid #e8e8e8;">
<button type="button" class="btn btn-primary btn-sm btnSubmit">
<i class="fa fa-dot-circle-o"></i>Descargar
</button>
<button type="button" class="btn btn-secondary btn-sm CLOSE_AJAX">
Cerrar
</button>
</div>
</form>

<script type="text/javascript">
flatpickr(".date", {
  "locale": "es",
  enableTime: false,
  dateFormat: "Y-m-d",
  allowInput: true,
  /*dateFormat: "Y-m-d H:i",*/
});

$(".btnClear").each(function(index, element){
  $btnClear = $(element);

  $btnClear.on("click", function(evt){
    evt.preventDefault();

    $datetimepicker = $(this).closest(".datetimepicker").first();

    $datetimepicker.find("input").val("");
  });
});
</script>
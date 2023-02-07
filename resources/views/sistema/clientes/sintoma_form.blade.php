<form action="javascript:void(0);" style="padding:0; height: 100%;">
<div class="area_input" style="width: 100%; padding:4px; height: calc(100% - 60px); overflow: hidden; overflow-y: scroll;">

<input type="hidden" name="Dato[id]" value="{{ ( request()->get('id') ?: md5(microtime(true)) ) }}" />

<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">Tipo*</label>
<select name="Dato[clave]" class="form-control">
<option value="">-- Seleccione --</option>
<option value="ENFERMEDAD" <?= validate_selection("ENFERMEDAD", request()->get('clave')) ?>>Enfermedad</option>
<option value="ALERGIA" <?= validate_selection("ALERGIA", request()->get('clave')) ?>>Alergia</option>
</select>
</div>
</div>

<div class="row">
<div class="form-group col-lg-8">
<label class="form-control-label">Descripción*</label>
<input type="text" name="Dato[descripcion]" value="{{ request()->get('descripcion') }}" class="form-control input-phone"
data-msg-required="Campo <b>Descripción</b> obligatorio."
style="" />
</div>
</div>


<!--<div class="row">
<div class="col-sm-8">
Activo<br>
<label class="switch switch-3d switch-primary mr-3">
<input name="Dato[status]" type="checkbox" class="switch-input" <?= (request()->get('status') ? "checked":"") ?>>
<span class="switch-label"></span>
<span class="switch-handle"></span>
</label>
</div>
</div>-->

</div>

<div class="area_button" style="width: 100%; padding: 10px 4px; border-top: 1px solid #e8e8e8;">
<button type="button" class="btn btn-primary btn-sm btnSubmit">
<i class="fa fa-dot-circle-o"></i>Aceptar
</button>
<button type="button" class="btn btn-secondary btn-sm CLOSE_AJAX">
<i class="fa fa-ban"></i>Cancelar
</button>
</div>
</form>

<script type="text/javascript">
/*$("#es_ajustable_venta").on("change", function(evt){
  evt.preventDefault();

  cOpt = $(this).val();

  $(".campo_es_precio_fijo").hide();
  $(".campo_tipo_descuento").hide();
  $(".campo_precio_fijo").hide();
  $(".campo_descuento").hide();

  if(cOpt == 1)
  {

  }
  else
  {
    $(".campo_es_precio_fijo").show();

    $("#es_precio_fijo").change();
  }
});

$("#es_precio_fijo").on("change", function(evt){
  evt.preventDefault();

  cOpt = $(this).val();

  $(".campo_precio_fijo").hide();
  $(".campo_tipo_descuento").hide();
  $(".campo_descuento").hide();

  if(cOpt == 1)
  {
    $(".campo_precio_fijo").show();
  }
  else
  {
    if($("#es_ajustable_venta").val() == 0)
    {
      $(".campo_tipo_descuento").show();
      $(".campo_descuento").show();
    }
  }
});

$("#es_ajustable_venta").change();
$("#es_precio_fijo").change();*/
</script>

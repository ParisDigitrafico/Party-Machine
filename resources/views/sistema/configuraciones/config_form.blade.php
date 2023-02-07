<form action="javascript:void(0);" style="padding:0; height: 100%;">
<div class="area_input area_simple">
<input type="hidden" name="_token" value="{{ csrf_token() }}" />
<input type="hidden" name="id" value="{{ intval($data->id) }}" />

@if($data->tipo == "BOOL")
<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">Valor*</label>
<select name="Dato[valor]" class="form-control required">
<option value="true" <?= validate_selection('true', $data->valor) ?>>True</option>
<option value="false" <?= validate_selection('false', $data->valor) ?>>False</option>
</select>
</div>
</div>
@else
<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">Valor*</label>
<input type="text" name="Dato[valor]" value="{{ $data->valor }}" class="form-control required"
data-msg-required="Campo <b>Valor</b> obligatorio."
style="" />
</div>
</div>
@endif

@if(!empty($data->id))
<!--<div class="row">
<div class="col-sm-8">
Activo<br>
<label class="switch switch-3d switch-primary mr-3">
<input name="Dato[status]" type="checkbox" class="switch-input" <?= ($Dato["status"]) ? "checked":"" ?>>
<span class="switch-label"></span>
<span class="switch-handle"></span>
</label>
</div>
</div>-->
@endif

</div>

<div class="area_button" style="width: 100%; padding: 12px 4px; border-top: 1px solid #e8e8e8;">

<div class="row">
<div class="col-6">
<button type="button" class="btn btn-primary btn-sm btnSubmit">
<i class="fa fa-dot-circle-o"></i>Guardar
</button>
<button type="button" class="btn btn-secondary btn-sm CLOSE_AJAX">
<i class="fa fa-ban"></i>Cancelar
</button>
</div>
</div>

</div>
</form>
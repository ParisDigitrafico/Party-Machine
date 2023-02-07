<form action="javascript:void(0);" style="padding:0; height: 100%;">
<div class="area_input" style="width: 100%; padding:4px; height: calc(100% - 60px); overflow: hidden; overflow-y: scroll;">
  
<input type="hidden" name="_token" value="{{ csrf_token() }}" />
<input type="hidden" name="id" value="{{ intval($Dato['id']) }}" />

<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">Clave*</label>
<input type="text" name="Dato[clave]" value="{{ $Dato['clave'] }}" class="form-control required"
data-msg-required="Campo <b>Clave</b> obligatorio."
style="" />
</div>
</div>

<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">Nombre*</label>
<input type="text" name="Dato[nombre]" value="{{ $Dato['nombre'] }}" class="form-control required"
data-msg-required="Campo <b>Nombre</b> obligatorio."
style="" />
</div>
</div>


<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">Dirección*</label>
<textarea class="form-control required" name="Dato[direccion]"
data-msg-required="Campo <b>Dirección</b> obligatorio."
style="">{{ $Dato['direccion'] }}</textarea>
</div>
</div>

@if(!empty($Dato['id']))
<div class="row">
<div class="col-sm-8">
Activo<br>
<label class="switch switch-3d switch-primary mr-3">
<input name="Dato[status]" type="checkbox" class="switch-input" <?= ($Dato["status"]) ? "checked":"" ?>>
<span class="switch-label"></span>
<span class="switch-handle"></span>
</label>
</div>
</div>
@endif

</div>

<div class="area_button" style="width: 100%; padding: 12px 4px; border-top: 1px solid #e8e8e8;">

<div class="row">
<div class="col-6">
<button type="button" class="btn btn-primary btn-sm btnSubmit">
<i class="fa fa-dot-circle-o"></i>Guardar
</button>
<button type="button" class="btn btn-danger btn-sm CLOSE_AJAX">
<i class="fa fa-ban"></i>Cancelar
</button>
</div>
</div>

</div>
</form>
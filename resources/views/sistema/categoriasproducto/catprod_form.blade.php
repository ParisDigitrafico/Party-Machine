<form action="javascript:void(0);" style="padding:0; height: 100%;">
<div class="area_input" style="width: 100%; padding:4px; height: calc(100% - 60px); overflow: hidden; overflow-y: scroll;">
<input type="hidden" name="_token" value="{{ csrf_token() }}" />
<input type="hidden" name="id" value="{{ intval(request()->get('id')) }}" />
<input type="hidden" name="Dato[parent]" value="{{ intval(request()->get('parent')) }}" />

<div class="row">

<div class="col-10 col-lg-8">
<div class="form-group">
<label class="form-control-label">Nombre*</label>
<input type="text" name="Dato[name]" value="{{ $Dato['name'] }}" class="form-control required"
data-msg-required="Campo <b>Nombre</b> obligatorio."
style="" />
</div>
</div>

<div class="col-10 col-lg-8">
<div class="form-group">
<label class="form-control-label">Foto</label> <br>
@if(!empty($cAux = $Dato['urlPhoto']))
<img src="{{ get_const('FBAZAR_IMAGES_HOST').$Dato['urlPhoto'] }}?{{ microtime(true) }}" style="max-height: 200px;" alt="" /><br><br>
<input type="hidden" name="Dato[urlPhoto]" value="{{ $Dato['urlPhoto'] }}" />
<input type="hidden" name="Dato[urlPhotoTn]" value="{{ $Dato['urlPhotoTn'] }}" />
@endif

<input type="file"
  name="photo"
  accept="image/png, image/jpeg">

@if(!empty($cAux))
<br>
<b><span style="font-size: 10pt">* Al Cargar una nueva foto se borrara la anterior.</span></b>
@endif

</div>
</div>

</div>

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
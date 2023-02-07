<form action="javascript:void(0);" style="padding:0; height: 100%;">
<div class="area_input" style="width: 100%; padding:4px; height: calc(100% - 60px); overflow: hidden; overflow-y: scroll;">

<input type="hidden" name="_token" value="{{ csrf_token() }}" />
<input type="hidden" name="id" value="{{ intval($data->id) }}" />

<div class="row">

<div class="col-10 col-lg-8">
<div class="form-group">
<label class="form-control-label">Nombre*</label>
<input type="text" name="Dato[nombre]" value="{{ $data->nombre }}" class="form-control required"
data-msg-required="Campo <b>Nombre</b> obligatorio."
style="" />
</div>
</div>

<div class="col-10 col-lg-10">
<div class="form-group">
<label class="form-control-label">Descripción</label>
<textarea name="Dato[descripcion]" id="" class="form-control ckeditor"
data-msg-required="Campo <b>Respuesta</b> obligatorio."
cols="30" rows="3" style="" >{{ $data->descripcion }}</textarea>
</div>
</div>


<div class="col-10 col-lg-11">
<div class="area_multi_upload" style="width: 96%;">
<div class="filelist"
     data-input="arrPhoto"
     data-input-name="nombre"
     data-input-description="descripcion"
     data-extension-valid="jpg,jpeg,png,gif"
     data-max-item="1"
     style="margin-left: -10px;">

@if(!empty($arrAux = $data->photos()))
@foreach($arrAux as $item)
<div class="item" data-id="{{ $item->id }}"
                  data-value-name="{{ $item->nombre }}"
                  data-value-description="{{ $item->descripcion }}"
                  data-url="{{ $item->url }}"
style=""></div>
@endforeach
@endif
</div>

<div class="clearfix"></div>

<div class="upload_input" style="padding: 20px 10px; border: 2px dotted;"></div>
<div class="progress" style="margin-top: 10px; display: none;">
<div class="progress-bar" role="progressbar" style="width: 25%"></div>
</div>
</div>
<br>
</div>

@if(!empty($data->id))
<div class="col-10 col-lg-8">
<div class="form-group">
Activo<br>
<label class="switch switch-3d switch-primary mr-3">
<input name="Dato[status]" type="checkbox" class="switch-input" <?= validate_checked($data->status, 1) ?>>
<span class="switch-label"></span>
<span class="switch-handle"></span>
</label>
</div>
</div>
@endif
</div>

</div>

<div class="area_button" style="width: 100%; padding: 10px 4px; border-top: 1px solid #e8e8e8;">
<button type="button" class="btn btn-primary btn-sm btnSubmit">
<i class="fa fa-dot-circle-o"></i>Guardar
</button>
<button type="button" class="btn btn-secondary btn-sm CLOSE_AJAX">
<i class="fa fa-ban"></i>Cancelar
</button>
</div>
</form>

<script type="text/javascript">
$(document).ready(function(){

  if($(".area_multi_upload").length > 0)
  {
    componente_extras.load_plugin_multi_upload($(".area_multi_upload"));
  }

});
</script>
<form action="javascript:void(0);" style="padding:0; height: 100%;">
<div class="area_input area_tabs">
<input type="hidden" name="_token" value="{{ csrf_token() }}" />
<input type="hidden" name="id" value="{{ intval($data->id) }}" />

<div class="vtabs">
<nav>
<a href="javascript:void(0);" style="text-decoration: none;"><div>Info. Básica</div></a>
<a href="javascript:void(0);" style="text-decoration: none;"><div>Foto Encabezado</div></a>
<a href="javascript:void(0);" style="text-decoration: none;"><div>Banners</div></a>
</nav>

<div class="item" ref="Info. Básica">
<div class="row">

@if(!empty($data->id))
<div class="col-10 col-lg-8">
<div class="form-group">
<label class="form-control-label">*Clave</label>

@if($data->es_fijo)
<div style="font-weight: bold;">{{ $data->clave }}</div>
@else
<input type="text" name="Dato[clave]" value="{{ $data->clave }}" class="form-control required"
data-msg-required="Campo <b>Clave</b> obligatorio."
style="" />
@endif
</div>
</div>
@else
<div class="col-10 col-lg-8">
<div class="form-group">
<label class="form-control-label">Clave (Dejar en blanco para asignación automática)</label>
<input type="text" name="Dato[clave]" value="{{ $data->clave }}" class="form-control"
data-msg-required="Campo <b>Clave</b> obligatorio."
style="" />
</div>
</div>
@endif

<div class="col-10 col-lg-8">
<div class="form-group">
<label class="form-control-label">*Nombre (Esp)</label>
<input type="text" name="Dato[nombre]" value="{{ $data->nombre }}" class="form-control required"
data-msg-required="Campo <b>Nombre</b> obligatorio."
style="" />
</div>
</div>

<div class="col-10 col-lg-8">
<div class="form-group">
<label class="form-control-label">Nombre (Eng)</label>
<input type="text" name="Dato[nombre_en]" value="{{ $data->nombre_en }}" class="form-control"
data-msg-required="Campo <b>Nombre</b> obligatorio."
style="" />
</div>
</div>

<div class="col-10 col-lg-8">
<div class="form-group">
<label class="form-control-label">Url</label>
<input type="text" name="Dato[url]" value="{{ $data->url }}" class="form-control"
data-msg-required="Campo <b>Url</b> obligatorio."
style="" />
</div>
</div>

<div class="col-11 col-lg-11">
<div class="form-group area_summernote">
<label class="form-control-label">Descripción (Esp)</label>
<textarea name="Dato[descripcion]" class="summernote">{!! $data->descripcion !!}</textarea>
</div>
</div>

<div class="col-11 col-lg-11">
<div class="form-group area_summernote">
<label class="form-control-label">Descripción (Eng)</label>
<textarea name="Dato[descripcion_en]" class="summernote">{!! $data->descripcion_en !!}</textarea>
</div>
</div>

<div class="col-11 col-lg-11">
<div class="form-group area_summernote">
<label class="form-control-label">Estilos CSS (Separados por comma)</label>
<textarea name="Dato[text_css]" class="form-control" placeholder="Ej.: /css/style1.css, /css/style2.css">{!! $data->text_css !!}</textarea>
</div>
</div>

<div class="col-11 col-lg-8">
<div class="form-group">
<label class="form-control-label">Keywords</label>
<textarea name="Dato[keywords]" class="form-control" placeholder="Ej.: Visas, Viajes, Journeys...">{!! $data->keywords !!}</textarea>
</div>
</div>

<div class="col-10 col-lg-8">
<div class="form-group">
Menú<br>
<label class="switch switch-3d switch-primary mr-3">
<input name="Dato[es_menu]" type="checkbox" class="switch-input" <?= validate_checked($data->es_menu, 1) ?>>

<span class="switch-label"></span>
<span class="switch-handle"></span>
</label>
</div>
</div>

<div class="col-10 col-lg-8">
<div class="form-group">
Visible<br>
<label class="switch switch-3d switch-primary mr-3">
@if(!empty($data->id))
<input name="Dato[es_visible]" type="checkbox" class="switch-input" <?= validate_checked($data->es_visible, 1) ?>>
@else
<input name="Dato[es_visible]" type="checkbox" class="switch-input" checked="checked">
@endif

<span class="switch-label"></span>
<span class="switch-handle"></span>
</label>
</div>
</div>

<div class="col-10 col-lg-8">
<div class="form-group">
Mostrar Suscribete<br>
<label class="switch switch-3d switch-primary mr-3">
@if(!empty($data->id))
<input name="Dato[mostrar_suscribete]" type="checkbox" class="switch-input" <?= validate_checked($data->mostrar_suscribete, 1) ?>>
@else
<input name="Dato[mostrar_suscribete]" type="checkbox" class="switch-input">
@endif

<span class="switch-label"></span>
<span class="switch-handle"></span>
</label>
</div>
</div>

<div class="col-10 col-lg-8">
<div class="form-group">
Abrir Otra Ventana<br>
<label class="switch switch-3d switch-primary mr-3">
@if(!empty($data->id))
<input name="Dato[abrir_otra_ventana]" type="checkbox" class="switch-input" <?= validate_checked($data->abrir_otra_ventana, 1) ?>>
@else
<input name="Dato[abrir_otra_ventana]" type="checkbox" class="switch-input">
@endif

<span class="switch-label"></span>
<span class="switch-handle"></span>
</label>
</div>
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

<div class="item" ref="Foto Encabezado">
<div class="row">
<div class="col-10 col-lg-11">
<div class="area_multi_upload" style="width: 96%;">
<div class="filelist"
     data-input="arrPhotoEncabezado"
     data-input-name="nombre"
     data-input-name-en="nombre_en"
     data-input-description="descripcion"
     data-input-description-en="descripcion_en"
     data-extension-valid="jpg,jpeg,png,gif"
     style="margin-left: -10px;">

@if(!empty($arrAux = $data->photos("FOTO_ENCABEZADO")))
@foreach($arrAux as $item)
<div class="item" data-id="{{ $item->id }}"
                  data-value-name="{{ $item->nombre }}"
                  data-value-name-en="{{ $item->nombre_en }}"
                  data-value-description="{{ $item->descripcion }}"
                  data-value-description-en="{{ $item->descripcion_en }}"
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
</div>
</div>

<div class="item" ref="Banners">
@php
if($data->banners)
{
  $arrAux = [];

  foreach($data->banners as $banner)
  {
    $arrAux[] = $banner->id;
  }
}
@endphp

@foreach($banners as $banner)
<label>
<input type="checkbox" name="banners[]" value="{{ $banner->id }}" <?= validate_checked($arrAux, $banner->id) ?> />&nbsp;{{ $banner->nombre }}
</label><br>
@endforeach

</div>
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
$("form").on("change", ".imgp", function(evt){
  e.preventDefault();

  var $self  = $(this);
  var ctext  = $self.val();
  var target = $(this).data("target");

  $("#"+target).attr("src",ctext);
});

/*$('.justdate').datetimepicker({
  format: 'L',
});*/

/*$(function(){
  $('#datetimepicker1').datetimepicker({
    ignoreReadonly: true,
  });
});*/

flatpickr(".date", {
  "locale": "es",
  altInput: true,
  altFormat: "j F Y",
  dateFormat: "Y-m-d",
  /*dateFormat: "Y-m-d H:i",*/
  enableTime: false,
  /*
  enableTime: true,
  noCalendar: true,
  dateFormat: "H:i",
  time_24hr: true,
  */
});

$(".btnClear").each(function(index, element){
  $btnClear = $(element);

  $btnClear.on("click", function(evt){
    evt.preventDefault();

    $datetimepicker = $(this).closest(".datetimepicker").first();

    $datetimepicker.find("input").val("");
  });
});

/*
$('.input-group-text').click(function(evt){
  evt.preventDefault();
  alert("ok");
});*/

componente_extras.load_plugin_summernote();
componente_extras.load_plugin_multi_upload($(".area_multi_upload"));
</script>
<form action="javascript:void(0);" style="padding:0; height: 100%;">
<div class="area_input" style="width: 100%; padding:4px; height: calc(100% - 60px); overflow: hidden; overflow-y: scroll;">

<input type="hidden" name="_token" value="{{ csrf_token() }}" />
<input type="hidden" name="id" value="{{ intval($data->id) }}" />

<div class="row">

<div class="col-10 col-lg-8">
<div class="form-group">
<label class="form-control-label">Categoría*</label>
<select name="Dato[idcategoria]" class="form-control">
<option value="">-- Seleccione --</option>
@foreach($data->getCategorias($data->idcategoria) as $item)
<option value="{{ $item->id }}" <?= validate_selection($item->id, $data->idcategoria) ?>>{{ $item->nombre }}</option>
@endforeach
</select>
</div>
</div>

<div class="col-10 col-lg-8">
<div class="form-group">
<label class="form-control-label">Pregunta*</label>
<textarea name="Dato[descripcion]" id="" class="form-control required"
data-msg-required="Campo <b>Pregunta</b> obligatorio."
cols="30" rows="10" style="" >{{ $data->descripcion }}</textarea>
</div>
</div>

<div class="col-10 col-lg-10">
<div class="form-group">
<label class="form-control-label">Respuesta*</label>
<textarea name="Dato[respuesta]" id="" class="form-control ckeditor required"
data-msg-required="Campo <b>Respuesta</b> obligatorio."
cols="30" rows="10" style="" >{{ $data->respuesta }}</textarea>
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
$("form").on("change", ".imgp", function(e){
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

$("form").find(".ckeditor").ckeditor();
</script>
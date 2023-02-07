<style type="text/css">
<!--
.table-data3 tbody td
{
  padding: 12px;
}

.table-data3 tr td
{
  vertical-align: middle;
}

.table-data3 tbody tr
{
  border-left: 0;
  border-right: 0;
}
-->
</style>

<form action="javascript:void(0);" style="padding:0; height: 100%;">
<div class="area_input area_tabs">
<input type="hidden" name="_token" value="{{ csrf_token() }}" />
<input type="hidden" name="id" id="id" value="{{ intval($data->id) }}" />

<div class="vtabs">
<nav>
<a href="javascript:void(0);" style="text-decoration: none;"><div>Info. Básica</div></a>
<a href="javascript:void(0);" style="text-decoration: none;"><div>Fotos</div></a>
</nav>

<div class="item" ref="Info. Básica">
<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">Usuario* (Correo Electrónico)</label>
<input type="text" name="Dato[user]" value="{{ $data->user }}" class="form-control required email"
data-msg-required="Campo <b>Usuario</b> obligatorio."
data-msg-email="Campo <b>Usuario</b> no es correo."
style="" />
</div>
</div>

@if(!empty($data->id))
<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">Contraseña (Restablecer)</label>
<input type="text" name="pass" class="form-control"
style="" />
</div>
</div>
@else
<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">Contraseña</label>
<input type="text" name="pass" class="form-control"
data-msg-required="Campo <b>Contraseña</b> obligatorio."
style="" />
</div>
</div>
@endif

<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">Nombre*</label>
<input type="text" name="Dato[nombre]" value="{{ $data->nombre }}" class="form-control required"
data-msg-required="Campo <b>Nombre</b> obligatorio."
style="" />
</div>
</div>

<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">Apellidos*</label>
<input type="text" name="Dato[apellidos]" value="{{ $data->apellidos }}" class="form-control required"
data-msg-required="Campo <b>Apellido Paterno</b> obligatorio."
style="" />
</div>
</div>

<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">Empresa</label>
<input type="text" name="Dato[empresa]" value="{{ $data->empresa }}" class="form-control"
data-msg-required="Campo <b>Apellido Paterno</b> obligatorio."
style="" />
</div>
</div>

<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">Teléfono</label>
<input type="text" name="Dato[telefono]" value="{{ $data->telefono }}" class="form-control"
data-msg-required="Campo <b>Apellido Paterno</b> obligatorio."
style="" />
</div>
</div>

<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">Fecha Nacimiento</label>
<div class="input-group datetimepicker">
<input type="text" name="Dato[fecha_nacimiento]" value="{{ get_date($data->fecha_nacimiento) }}" class="form-control date"
data-msg-required="Campo <b>Fecha Nacimiento</b> obligatorio."
style="" />
<div class="input-group-append btnClear" style="cursor: pointer;" title="Limpiar">
<div class="input-group-text"><i class="fa fa-times"></i></div>
</div>
</div>
</div>
</div>

<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">Género</label>
<select name="Dato[genero]" class="form-control">
<option value="">-- Seleccione --</option>
<option value="M" <?= validate_selection("M", $data->genero) ?>>Masculino</option>
<option value="F" <?= validate_selection("F", $data->genero) ?>>Femenino</option>
</select>
</div>
</div>

@if(!empty($data->id))
<div class="row">
<div class="col-sm-8">
Activo<br>
<label class="switch switch-3d switch-primary mr-3">
<input name="Dato[status]" type="checkbox" class="switch-input" <?= ($data->status == 1) ? "checked":"" ?>>
<span class="switch-label"></span>
<span class="switch-handle"></span>
</label>
</div>
</div>
@endif
</div>

<div class="item" ref="Fotos">

<div class="area_multi_upload" style="width: 96%;">
<div class="filelist"
     data-input="arrFoto"
     data-input-name="nombre"
     data-input-description="descripcion"
     data-extension-valid="jpg,jpeg,png,gif"
     style="margin-left: -10px;">

@if(!empty($arr = $data->photos("FOTO_VISITA")) && is_array($arr))
  @foreach($arr as $row)
  <div class="item" data-id="{{ $row->id }}"
                    data-value-name="{{ $row->nombre }}"
                    data-value-description="{{ $row->descripcion }}"
                    data-url="{{ $row->url }}"
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

if($(".area_multi_upload").length > 0)
{
  componente_extras.load_plugin_multi_upload($(".area_multi_upload"));
}

$("form").find(".ckeditor").ckeditor();
</script>
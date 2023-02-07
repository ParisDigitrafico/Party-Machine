<form action="javascript:void(0);" style="padding:0; height: 100%;">
<div class="area_input" style="width: 100%; padding:4px; height: calc(100% - 60px); overflow: hidden; overflow-y: hidden; overflow-y: scroll;">
<input type="hidden" name="_token" value="{{ csrf_token() }}" />
<input type="hidden" name="id" value="{{ intval($Dato['id']) }}" />

<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">*Usuario (Correo Electrónico)</label>
<input type="text" name="Dato[user]" value="{{ $Dato['user'] }}" class="form-control required email"
data-msg-required="Campo <b>Usuario</b> obligatorio."
data-msg-email="Campo <b>Usuario</b> no es correo."
style="" />
</div>
</div>

@if(!empty($Dato['id']))
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
<label class="form-control-label">Contraseña*</label>
<input type="text" name="pass" class="form-control required"
data-msg-required="Campo <b>Contraseña</b> obligatorio."
style="" />
</div>
</div>
@endif

<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">*Nombre</label>
<input type="text" name="Dato[nombre]" value="{{ $Dato['nombre'] }}" class="form-control required"
data-msg-required="Campo <b>Nombre</b> obligatorio."
style="" />
</div>
</div>

<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">*Apellidos</label>
<input type="text" name="Dato[apellidos]" value="{{ $Dato['apellidos'] }}" class="form-control required"
data-msg-required="Campo <b>Apellidos</b> obligatorio."
style="" />
</div>
</div>

<div class="row">
<div class="form-group col-lg-8">
<label class="form-control-label">Teléfono</label>
<input type="text" name="Dato[telefono]" value="{{ $Dato['telefono'] }}" class="form-control input-phone"
data-msg-required="Campo <b>Teléfono</b> obligatorio."
style="" />
</div>
</div>

<!--<div class="row">
<div class="form-group col-sm-12">
<label class="form-control-label">Descripción Completa</label>
<textarea name="Dato[apellido_mat]" class="ckeditor">{{ $Dato['apellido_mat'] }}</textarea>
</div>
</div>-->


@if(!empty($Dato['id']) && $Dato['id'] == session('usuario_id') && $Dato["es_super"] == 0)
<div style="display: none">
@endif

<fieldset class="border p-2">
<legend class="w-auto">Perfiles</legend>
@if(!empty($arrPerfil))
@foreach($arrPerfil as $Perfil)

@if($Perfil["id"] != 5)
<div class="row">
<div class="form-group col-sm-8" style="margin-bottom: 0;">
<label class="form-control-label">
<input type="checkbox" name="DatoPerfil[]" {!! $Dato["es_super"] == 1 ? 'onclick="return false;"' : '' !!}
value="{{ $Perfil['id'] }}" {!! in_array($Perfil['id'], $arrPerfilSel) ? 'checked' : '' !!} />&nbsp;{{ $Perfil['nombre'] }}
</label>
</div>
</div>
@endif

@endforeach
@endif
</fieldset>

<div class="row m-t-20"></div>

@if(session('es_super') == true && session('usuario_id') != $Dato["id"] && ($Dato["status"] == 1 or $Dato["status"] == "") )
<div class="row">
<div class="col-sm-12">
Súper Usuario <span style="color: #0066FF">Habilite esta opción solo a usuarios de confianza.</span><br>
<label class="switch switch-3d switch-primary mr-3">
<input name="es_super" type="checkbox" class="switch-input" <?= ($Dato["es_super"])? "checked":"" ?>>
<span class="switch-label"></span>
<span class="switch-handle"></span>
</label>
</div>
</div>
@endif

@if(!empty($Dato['id']) && $Dato["es_super"] != 1 )
<div class="row">
<div class="col-sm-8">
Activo<br>
<label class="switch switch-3d switch-primary mr-3">
<input name="Dato[status]" type="checkbox" class="switch-input" <?= ($Dato["status"])? "checked":"" ?>>
<span class="switch-label"></span>
<span class="switch-handle"></span>
</label>
</div>
</div>
@endif

@if(!empty($Dato['id']) && $Dato['id'] == session('usuario_id') && $Dato["es_super"] == 0)
</div>
@endif

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
$elem = $(".input-phone");

$elem.toArray().forEach(function(field){
  new Cleave(field, {
    phone: true,
    phoneRegionCode: 'MX',
  });
});
</script>
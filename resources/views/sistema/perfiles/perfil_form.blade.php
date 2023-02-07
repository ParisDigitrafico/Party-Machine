<form action="javascript:void(0);" style="padding:0; height: 100%;">
<div class="area_input" style="width: 100%; padding:4px; height: calc(100% - 60px); overflow: hidden; overflow-y: hidden; overflow-y: scroll;">

<input type="hidden" name="_token" value="{{ csrf_token() }}" />
<input type="hidden" name="id" value="{{ intval($Dato['id']) }}" />
<input type="hidden" name="Dato[updated_at]" value="{{ now() }}" />

@if($Dato['es_fijo'] == 0)
<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">*Nombre</label>
<input type="text" name="Dato[nombre]" value="{{ $Dato['nombre'] }}" class="form-control required"
data-msg-required="Campo <b>Nombre</b> obligatorio."
style="" />
</div>
</div>
@else
<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label mb-0">Nombre</label>
<div><b>{{ $Dato['nombre'] }}</b></div>
</div>
</div>
@endif

<fieldset class="border p-2">
<legend  class="w-auto">Permisos</legend>
@if(!empty($arrModulo))
@foreach($arrModulo as $Modulo)

  @if($Modulo["tiene_permisos"] == 1)
  <div class="row">
  <div class="form-group col-sm-12" style="margin-bottom: 6px;">
  <div style="padding-bottom: 2px;"><label style="margin: 0; padding: 0;"><input
        type="checkbox" class="chkAll chkAll-{{ $Modulo['id'] }}" data-modulo="{{ $Modulo['id'] }}" />&nbsp;{{ $Modulo['titulo'] ?: $Modulo['nombre'] }}</label></div>

    @foreach($Modulo["permisos"] as $Permiso)
      <div style="padding-left: 10px;"><label style="margin: 0; padding: 0;"><input type="checkbox"
      class="chkPermiso chkModulo-{{ $Modulo['id'] }}" data-modulo="{{ $Modulo['id'] }}"
      name="arrPermiso[]" value="{{ $Permiso['id'] }}"
      <?= (in_array($Permiso['id'], $arrPermisoSel) ? 'checked="checked"' : '' ) ?>
      />&nbsp;<b>{{ $Permiso['clave'] }}</b>&nbsp;{{ $Permiso['descripcion'] }}</label></div>
    @endforeach

  </div>
  </div>
  @endif

@endforeach
@endif
</fieldset>

<div class="row m-t-20"></div>

@if(!empty($Dato['id']) && $Dato["es_super"] != 1)
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
  $(".chkAll").each(function(index, element){
    $checkAll = $(element);

    $checkAll.change(function(evt){
      evt.preventDefault();

      $(".chkModulo-" + $(this).data("modulo")).prop('checked', $(this).prop("checked"));
    });

    var iSize        = $(".chkModulo-" + $checkAll.data("modulo")).length;
    var iSizechecked = $(".chkModulo-" + $checkAll.data("modulo")).filter(':checked').length;

    $(".chkAll-" + $checkAll.data("modulo")).prop('checked', false);

    if(iSize == iSizechecked)
    {
      $(".chkAll-" + $checkAll.data("modulo")).prop('checked', true);
    }
  });

  $(".chkPermiso").on("change", function(evt){
    evt.preventDefault();

    $chkPermiso = $(this);

    var iSize        = $(".chkModulo-" + $chkPermiso.data("modulo")).length;
    var iSizechecked = $(".chkModulo-" + $chkPermiso.data("modulo")).filter(':checked').length;

    $(".chkAll-" + $chkPermiso.data("modulo")).prop('checked', false);

    if(iSize == iSizechecked)
    {
      $(".chkAll-" + $chkPermiso.data("modulo")).prop('checked', true);
    }
  });
});
</script>
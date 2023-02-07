<form action="javascript:void(0);" style="padding:0; height: 100%;">
<div class="area_input" style="width: 100%; padding:4px; height: calc(100% - 60px); overflow: hidden; overflow-y: scroll;">

<input type="hidden" id="id" value="{{ request()->get('id') }}" />

@if(!empty($arr = $arrCliente) && is_array($arr))
<div class="row">
<div class="form-group col-8">
<label class="form-control-label">Cliente</label>

<select name="Dato[idpariente]" id="idpariente" class="select2"
data-placeholder="-- Seleccione --"
data-allowClear="true"
style="width: 100%">
<option></option>
@foreach($arr as $row)
<option value="{{ $row['id'] }}"
data-nombre="{!! $row['nombre'] . " " . $row['apellido_pat'] . " " . $row['apellido_mat'] !!}"
<?= validate_selection(request()->get('idpariente'), $row['id']) ?>
>{!! $row['codigo'] . " - " . $row['nombre'] . " " . $row['apellido_pat'] . " " . $row['apellido_mat'] . " " . $row['user'] !!}</option>
@endforeach
</select>

</div>
</div>
@endif

@if(!empty($arr = $arrParentesco) && is_array($arr))
<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">Parentesco*</label>
<select name="Dato[idparentesco]" id="idparentesco" class="form-control">
<option value="">-- Seleccione --</option>
@foreach($arr as $row)
<option value="{{ $row['id'] }}"
data-nombre="{{ $row['nombre'] }}"
<?= validate_selection(request()->get('idparentesco'), $row['id']) ?>
>{{ $row['nombre'] }}</option>
@endforeach
</select>
</div>
</div>
@endif

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
load_select2(".select2");
</script>
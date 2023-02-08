<form action="{{ before('search', url()->current()) }}" method="get" style="padding:0; height: 100%;">
<div class="area_input" style="width: 100%; padding:4px; height: calc(100% - 60px); overflow: hidden; overflow-y: hidden; overflow-y: scroll;">
@php $b = request()->get("b"); @endphp

<div class="row">
<div class="form-group col-sm-8">
<label class="form-control-label">Palabras Clave</label>
<input type="text" name="b[keywords]" value="{{ $b['keywords'] }}" class="form-control">
</div>
</div>

<div class="row">
<div class="form-group col-sm-8">
<label for="street" class=" form-control-label">Status</label>
<select name="b[status]" class="form-control">
<option value="">-- Todas --</option>
<option value="1" <?= (($b['status'] == "1") ? "selected": "") ?>>Activo</option>
<option value="0" <?= (($b['status'] == "0") ? "selected": "") ?>>Inactivo</option>
</select>
</div>
</div>

</div>

<div class="area_button" style="width: 100%; padding: 10px 4px; border-top: 1px solid #e8e8e8;">
<button type="submit" class="btn btn-primary btn-sm btnSubmit">
<i class="fa fa-dot-circle-o"></i>Buscar
</button>
<button type="button" class="btn btn-secondary btn-sm CLOSE_AJAX">
<i class="fa fa-ban"></i>Cancelar
</button>
</div>
</form>
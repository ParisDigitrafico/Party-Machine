<div class="row">
<div class="col-md-12">
<div class="table-data__tool m-b-0">
<div class="table-data__tool-left">
<h3 class="title-5 m-b-35">{{ request()->get('name') }}</h3>
</div>

<div class="table-data__tool-right">
<a href="#" class="au-btn au-btn-load au-btn--small btnOpenPanelSearch"
data-title="Buscar Subcategoría"
data-buscar="{!! (!empty($b = request()->get('b')) ? http_build_query(['b'=>$b]) : '') !!}">Buscar</a>

@if(in_array("C_CATALOGO", session("permisos")))
<a href="#" class="au-btn au-btn-icon au-btn--blue au-btn--small btnOpenPanelForm"
data-id=""
data-parent="{{ $parent }}"
data-title="Agregar Subcategoría">
<i class="zmdi zmdi-plus"></i>Agregar</a>
@endif

<!--<div class="rs-select2--dark rs-select2--sm rs-select2--dark2">
<select class="js-select2" name="type">
<option selected="selected">Export</option>
<option value="">Option 1</option>
<option value="">Option 2</option>
</select>
<div class="dropDownSelect2"></div>
</div>-->
</div>
</div>

</div>
</div>

<div class="row">
<div class="col col-lg-12" style=" border-radius: 50px;">
<section class="card au-card--border-10">
<div class="card-body">
<table class="tblBase" class="display responsive" style="width:100%">
<thead>
<tr>
<th class="d-none d-md-block" style="text-align: left"><input id="optall" type="checkbox"></th>

<th data-priority="1">Nombre</th>
<th data-priority="2">Foto</th>

<th data-priority="5" style="text-align: right">Opciones</th>
</tr>
</thead>
</table>
</div>
</section>
</div>

</div>
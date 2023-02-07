<form action="javascript:void(0);" style="padding:0; height: 100%;">
<div class="area_input" style="width: 100%; padding:4px; height: calc(100% - 60px); overflow: hidden; overflow-y: hidden; overflow-y: scroll;">
<input type="hidden" name="_token" value="{{ csrf_token() }}" />

@if(!empty($Dato['id']))
<div class="d-none">
<input type="text" name="id" value="{{ $Dato['id'] }}" class="form-control">
</div>
@endif

<h2><?= $Dato['nombre'] ?></h2>
<br><br>
<p><?= $Dato['descripcion'] ?></p>

</div>

<div class="area_button" style="width: 100%; padding: 10px 4px; border-top: 1px solid #e8e8e8;">
<!--<button type="button" class="btn btn-primary btn-sm btnSubmit">
<i class="fa fa-dot-circle-o"></i>Guardar
</button>-->
<button type="button" class="btn btn-secondary btn-sm CLOSE_AJAX <?= $Dato['es_leido'] == 0 ? "btnClose" : "" ?>">
Cerrar
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

$("form").find(".ckeditor").ckeditor();
</script>
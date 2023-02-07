@extends('sistema.layouts.master')

@push('content')
<div class="content">
<input type="hidden" id="token" value="{{ csrf_token() }}" />
<div class="row">
<div class="col-md-12">
<div class="table-data__tool m-b-0">
<div class="table-data__tool-left">
<h3 class="title-5 m-b-35"><?= $title ?></h3>
</div>

<div class="table-data__tool-right">
<a href="#" class="au-btn au-btn-load au-btn--small btnOpenPanelSearch"
data-title="Buscar Hotel"
data-buscar="{!! (!empty($b = request()->get('b')) ? http_build_query(['b'=>$b]) : '') !!}">Buscar</a>

@if(in_array("C_CATALOGO", session("permisos")))
<a href="#" class="au-btn au-btn-icon au-btn--blue au-btn--small btnOpenPanelForm"
data-id=""
data-title="Agregar Hotel">
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
<th data-priority="2">Descripcion</th>
<th data-priority="3">Cantidad</th>
<th data-priority="4">Precio</th>

<th data-priority="8" style="max-width:90px;">Fecha Creación</th>
<th data-priority="9" style="max-width:160px;">Creado por</th>
<th data-priority="4" style="max-width:90px;">Status</th>
<th data-priority="3" style="max-width:100px; text-align: right">Opciones</th>
</tr>
</thead>
</table>
</div>
</section>
</div>

</div>
</div>
@endpush

@push('js')
<script type="text/javascript">
$(document).ready(function(){
  var $content = $(".content").first();

  var $dtBase = $content.find('.tblBase').DataTable({
    responsive: true,
    "language": {
    "url": "/static/sistema/libs/DataTables/Spanish.json",
    },
    "searching": false,
    "order": [],
    "processing": true,
    "serverSide": true,
    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
    "ajax": "/sistema/productos/paginate/" + "?" + "{!! (!empty($b = request()->get('b')) ? http_build_query(['b'=>$b]) : '') !!}",
    "columns": [
      { "data": "opt", "orderable":false, "className":"d-none d-md-revert" },

      { "data": "nombre", "orderable":false, },
      { "data": "descripcion", "orderable":false, },
      { "data": "cantidad", "orderable":false, },
      { "data": "precio", "orderable":false, },

      { "data": "fecha", "orderable":false, },
      { "data": "creador", "orderable":false, },
      { "data": "status", "orderable":false, },
      { "data": "opciones", "orderable":false, },
    ],
    "initComplete": function(settings, json){
      $(this).closest(".dataTables_wrapper").on('page.dt', function(){
        $("html, body").animate({scrollTop:0}, "slow");
      });
    },
  });

  $content.on("click", ".btnOpenPanelSearch", function(evt){
    evt.preventDefault();

    if(document.getElementById("pp0")) return false;

    var _title  = $(this).data("title");
    var _buscar = $(this).data("buscar");

    var idp0 = $.crmPanel({
      href: "/sistema/productos/search/" + "?" + _buscar,
      title: _title,
      onLoad: function(){
        var $pp0 = $("#"+idp0);

        $pp0.find(".btnSubmit").off("click").on("click", function(evt){
          evt.preventDefault();

          $form = $(this).closest("form");

          $form.submit();
        });
      },
    });
  });

  $content.on("click", ".btnOpenPanelForm", function(evt){
    evt.preventDefault();

    if(document.getElementById("pp0")) return false;

    var _id    = $(this).data("id");
    var _title = $(this).data("title");

    var idp0 = $.crmPanel({
      href: "/sistema/productos/form/" + "?" + $.param({"id":_id}),
      title: _title,
      onLoad: function()
      {
        var $pp0 = $("#"+idp0);

        $pp0.find(".btnSubmit").off("click").on("click", function(evt){
          evt.preventDefault();

          $btnSubmit = $(this);

          $form = $pp0.find("form");

          if(componente_formulario.ValidarForm($form))
          {
            $btnSubmit.prop('disabled', true);

            var qs = $form.serialize();

            $.crmPanel.showLoader(idp0);

            var xhr = $.post("/sistema/productos/save/", qs, null, 'json');

            xhr.done(function(response){
              if(response.success)
              {
                $dtBase.ajax.reload( null, false );

                $.crmPanel.close(idp0);

                toastr.success(response.message);
              }
              else
              {
                $.crmPanel.hideLoader(idp0);

                swal({
                  type: "error",
                  text: response.message,
                });
              }
            });

            xhr.fail(function(jqXHR, textStatus){
              if(textStatus != "success")
              {
                alert("Error: " + jqXHR.statusText);
              }
            });

            xhr.always(function(){
              $btnSubmit.prop('disabled', false);
              $.crmPanel.hideLoader(idp0);
            });
          }
        });
      },
    });
  });

  $content.on("click", ".btnOpenPanelDetail", function(evt){
    evt.preventDefault();

    if(document.getElementById("pp0")) return false;

    var _id    = $(this).data("id") || 0;
    var _title = $(this).data("title") || "";

    var idp0 = $.crmPanel({
      href: "/sistema/productos/" + _id + "/",
      title: _title,
      onLoad: function(){

      },
    });
  });

  $content.on("click", ".btnDelete", function(evt){
    evt.preventDefault();

    $btn = $(this);

    swal({
      text: $btn.data("title"),
      type: "warning",
      showCancelButton: true,
    }).then((result) => {
      if(result.value)
      {
        var xhr = $.post('/sistema/productos/'+ $btn.data("id") +'/action/delete/', { _token: window.laravel.token }, null, 'json');

        xhr.done(function(response){
          if(response.success)
          {
            toastr.success(response.message);

            $dtBase.ajax.reload( null, false );
          }
          else
          {
            swal({
              type: "error",
              text: response.message,
            });
          }
        });
      }
    });
  });

  $content.on("click", ".btnRestore", function(evt){
    evt.preventDefault();

    $btn = $(this);

    swal({
      text: $btn.data("title"),
      type: "warning",
      showCancelButton: true,
    }).then((result) => {
      if(result.value)
      {
        var xhr = $.post('/sistema/productos/'+ $btn.data("id") +'/action/undelete/', { _token: window.laravel.token }, null, 'json');

        xhr.done(function(response){
          if(response.success)
          {
            toastr.success(response.message);

            $dtBase.ajax.reload( null, false );
          }
          else
          {
            swal({
              type: "error",
              text: response.message,
            });
          }
        });
      }
    });
  });

});
</script>
@endpush
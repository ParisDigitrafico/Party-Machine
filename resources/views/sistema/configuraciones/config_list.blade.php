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
data-title="Buscar Configuración"
data-buscar="{!! (!empty($b = request()->get('b')) ? http_build_query(['b'=>$b]) : '') !!}">Buscar</a>
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
<th data-priority="1">Clave</th>
<th data-priority="2">Descripción</th>
<th data-priority="3">Valor</th>
<th data-priority="4">Tipo</th>
<th data-priority="5" style="text-align: right">Opciones</th>
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

  var cUrlController = "/sistema/configuraciones";

  var $dtBase = $content.find('.tblBase').DataTable({
    responsive: true,
    "language": { "url":"/static/sistema/libs/DataTables/Spanish.json", },
    "searching": false,
    "order": [],
    "processing": true,
    "serverSide": true,
    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],       
    "ajax": "/sistema/configuraciones/paginate/" + "?" + "{!! (!empty($b = request()->get('b')) ? http_build_query(['b'=>$b]) : '') !!}",
    "columns": [
      { "data": "opt", "orderable":false, "className":"d-none d-md-revert" },
      { "data": "clave", "orderable":false, },
      { "data": "descripcion", "orderable":false, },
      { "data": "valor", "orderable":false, },
      { "data": "tipo", "orderable":false, },
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

    $btn = $(this);

    $btn.prop('disabled', true);
    setTimeout(function(){
      $btn.prop('disabled', false);
    }, 1999);

    var _title  = $btn.data("title");
    var _buscar = $btn.data("buscar");

    var idp0 = $.crmPanel({
      href: cUrlController + "/search/" + "?" + _buscar,
      title: _title,
      onLoad: function()
      {
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

    var _id    = $(this).data("id");
    var _title = $(this).data("title");

    var idp0 = $.crmPanel({
      href: cUrlController + "/form/" + "?" + $.param({"id":_id}),
      title: _title,
      onLoad: function()
      {
        var $pp0 = $("#"+idp0);

        $pp0.find(".btnSubmit").off("click").on("click", function(evt){
          evt.preventDefault();

          $form = $pp0.find("form");

          if(componente_formulario.ValidarForm($form))
          {
            qs = $form.serialize();

            $.crmPanel.showLoader(idp0);

            $.post(cUrlController + "/save/", qs, function(response){
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
            }, "json" );
          }
        });
      },
    });
  });

});
</script>
@endpush
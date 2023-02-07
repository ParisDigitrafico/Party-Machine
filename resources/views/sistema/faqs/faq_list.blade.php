@extends('sistema.layouts.master')

@push('content')
<div class="content">
<div class="row">
<div class="col-md-12">
<div class="table-data__tool m-b-0">
<div class="table-data__tool-left">
<h3 class="title-5 m-b-35">{{ $title }}</h3>
</div>

<div class="table-data__tool-right">
<a href="#" class="au-btn au-btn-load au-btn--small btnOpenPanelSearch"
data-title="Buscar {{ $name }}"
data-buscar="{!! (!empty($b = request()->get('b')) ? http_build_query(['b'=>$b]) : '') !!}">Buscar</a>

@if(find_string_in_array('C_CATALOGO', session("permisos")))
<a href="#" class="au-btn au-btn-load au-btn--small btnOpenPanelCategoriaList"
data-title="Lista Categorías">Categorías</a>
@endif

@if(in_array("C_FAQS", session("permisos")))
<a href="#" class="au-btn au-btn-icon au-btn--blue au-btn--small btnOpenPanelForm"
data-id=""
data-title="Agregar {{ $name }}">
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

<th data-priority="1" style="max-width:400px;">Pregunta</th>
<th data-priority="2" style="max-width:400px;">Categoría</th>

<th data-priority="7" style="max-width:90px;">Fecha</th>
<th data-priority="8" style="max-width:160px;">Creado por</th>
<th data-priority="6">Status</th>
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

  var cUrlController = "/sistema/faqs";

  var $dtBase = $content.find('.tblBase').DataTable({
    responsive: true,
    "language": { "url": "/static/sistema/libs/DataTables/Spanish.json", },
    "searching": false,
    "order": [],
    "processing": true,
    "serverSide": true,
    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
    "ajax": cUrlController + "/paginate/" + "?" + "{!! (!empty($b = request()->get('b')) ? http_build_query(['b'=>$b]) : '') !!}",
    "columns": [
      { "data": "opt", "orderable":false, "className":"d-none d-md-revert" },

      { "data": "pregunta", "orderable":false, },
      { "data": "categoria", "orderable":false, },

      { "data": "fecha", "orderable":false, },
      { "data": "creador", "orderable":false, },
      { "data": "status", "orderable":false, },
      { "data": "opciones", "orderable":false, },
    ],
    "initComplete": function(settings, json){
      $(this).closest(".dataTables_wrapper").on('page.dt', function(){
        $('html, body').animate({
          scrollTop: $("div.main-content").first().offset().top,
        }, 'slow');
      });
    },
  });

  $content.on("click", ".btnOpenPanelSearch", function(evt){
    evt.preventDefault();

    if(document.getElementById("pp0")) return false;

    $btn = $(this);

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

    $dtBase.ajax.reload( null, false );

    if(document.getElementById("pp0")) return false;

    $btn = $(this);

    var _id    = $btn.data("id");
    var _title = $btn.data("title");

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

  $content.on("click", ".btnOpenPanelDetail", function(evt){
    evt.preventDefault();

    $dtBase.ajax.reload( null, false );

    if(document.getElementById("pp0")) return false;

    var _id    = $(this).data("id") || 0;
    var _title = $(this).data("title") || "&nbsp;";

    $.crmPanel({
      href: cUrlController + "/" + _id + "/",
      title: _title,
    });
  });

  $content.on("click", ".btnAction", function(evt){
    evt.preventDefault();

    $btn = $(this);

    swal({
      text: $btn.data("title"),
      type: "warning",
      showCancelButton: true,
    }).then((result) => {
      if(result.value)
      {
        var xhr = $.post(cUrlController + "/" + $btn.data("id") + "/action/" + $btn.data("action") + "/", { _token: window.laravel.token }, null, 'json');

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

  $content.on("click", ".btnOpenPanelCategoriaList", function(evt){
    evt.preventDefault();

    if(document.getElementById("pp0")) return false;

    var _title = $(this).data("title");

    var _cTabla = "fbz_faq";

    var idp0 = $.crmPanel({
      href: "/sistema/categorias/" + _cTabla + "/openpanel/" + "?" + $.param({}),
      title: _title,
      onLoad: function()
      {
        var $pp0 = $("#"+idp0);

        var $dtCategorias = $pp0.find('.tblBase').DataTable({
          responsive: true,
          "language": { "url": "/static/sistema/libs/DataTables/Spanish.json", },
          searching: false,
          order: [],
          processing: true,
          serverSide: true,
          "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
          ajax: "/sistema/categorias/" + _cTabla + "/paginate/" + "?" + "",
          columns: [
            { "data": "opt", "orderable": false, },
            { "data": "nombre", "orderable": false, },
            { "data": "status", "orderable": false, },
            { "data": "opciones", "orderable": false, },
          ],
        });

        $pp0.on("click",".btnOpenPanelForm", function(evt){
          evt.preventDefault();

          if(document.getElementById("pp1")) return false;

          var _id    = $(this).data("id") || "";
          var _title = $(this).data("title") || "";

          var idp1 = $.crmPanel({
            href: "/sistema/categorias/" + _cTabla + "/form/" + "?" + $.param({"id":_id}),
            title: _title,
            onLoad: function()
            {
              var $pp1 = $("#"+idp1);

              $pp1.find(".btnSubmit").off("click").on("click", function(evt){
                evt.preventDefault();

                $form = $pp1.find("form");

                if(componente_formulario.ValidarForm($form))
                {
                  qs = $form.serialize();

                  $.crmPanel.showLoader(idp1);

                  $.post("/sistema/categorias/" + _cTabla + "/save/", qs, function(response){
                    if(response.success)
                    {
                      $dtBase.ajax.reload( null, false );
                      $dtCategorias.ajax.reload( null, false );

                      $.crmPanel.close(idp1);

                      toastr.success(response.message);
                    }
                    else
                    {
                      $.crmPanel.hideLoader(idp1);

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

        $pp0.on("click",".btnAction", function(evt){
          evt.preventDefault();

          $btn = $(this);

          swal({
            text: $btn.data("title"),
            type: "warning",
            showCancelButton: true,
          }).then((result) => {
            if(result.value)
            {
              var xhr = $.post("/sistema/categorias/" + $btn.data("id") + "/action/" + $btn.data("action") + "/", { _token: window.laravel.token }, null, "json");

              xhr.done(function(response){
                if(response.success)
                {
                  toastr.success(response.message);

                  $dtCategorias.ajax.reload( null, false );
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

      },
    });
  });

});
</script>
@endpush
@extends('sistema.layouts.master')

@push('content')
<div class="content">
<div class="row">
<div class="col-md-12">
<div class="table-data__tool m-b-0">
<div class="table-data__tool-left">
<h3 class="title-5 m-b-35"><?= $title ?></h3>
</div>

<div class="table-data__tool-right">
<a href="#" class="au-btn au-btn-load au-btn--small btnOpenPanelSearch"
data-title="Buscar Usuario"
data-buscar="{!! (!empty($b = request()->get('b')) ? http_build_query(['b'=>$b]) : '') !!}">Buscar</a>

@if(find_string_in_array('_PERFIL', session("permisos")))
<a href="#" class="au-btn au-btn-load au-btn--small btnOpenPanelPerfilList"
data-title="Lista Perfiles">Lista Perfiles</a>
@endif

@if(in_array("C_USUARIO", session("permisos")))
<a href="#" class="au-btn au-btn-icon au-btn--blue au-btn--small btnOpenPanelForm"
data-id=""
data-title="Agregar Usuario">
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
<th class="d-none d-lg-block" style="text-align: left"><input id="optall" type="checkbox"></th>

<th data-priority="1">Código</th>
<th data-priority="2">Usuario</th>
<th data-priority="2">Nombre</th>
<th data-priority="2">Apellidos</th>
<th data-priority="2" style="max-width:180px;">Perfiles</th>

<th data-priority="8" style="max-width:90px;">Fecha Creación</th>
<th data-priority="9" style="max-width:160px;">Creado por</th>
<th data-priority="4" style="max-width:90px;">Status</th>
<th data-priority="3" style="max-width:100px; text-align: right;">Opciones</th>
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

  var cUrlController = "/sistema/usuarios";

  var $dtBase = $content.find('.tblBase').DataTable({
    responsive: true,
    language: { "url":"/static/sistema/libs/DataTables/Spanish.json", },
    searching: false,
    processing: true,
    serverSide: true,
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
    ajax: {
      url: cUrlController + "/paginate/" + "?" + "{!! (!empty($b = request()->get('b')) ? http_build_query(['b'=>$b]) : '') !!}",
      type: 'get',
      error: function(xhr, error, code){
        if(xhr.status == 203)
          window.location.reload(true);
        else
          alert(error);
      }
    },
    order: [],
    columns: [
      { "data": "opt", "orderable":false, "className":"d-none d-md-revert" },
      
      { "data": "codigo", "orderable":false, },
      { "data": "user", "orderable":false, },
      { "data": "nombre", "orderable":false, },
      { "data": "apellidos", "orderable":false, },
      { "data": "perfiles", "orderable":false, },

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
      href: "/sistema/usuarios/search/" + "?" + _buscar,
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

    if(document.getElementById("pp0")) return false;

    var _id    = $(this).data("id");
    var _title = $(this).data("title");

    var idp0 = $.crmPanel({
      href: "/sistema/usuarios/form/" + "?" + $.param({"id":_id}),
      title: _title,
      onLoad: function()
      {
        var $pp0 = $("#"+idp0);

        $pp0.find(".btnSubmit").off("click").on("click", function(evt){
          evt.preventDefault();

          $form = $pp0.find("form");

          if(componente_formulario.ValidarForm($form))
          {
            $.crmPanel.showLoader(idp0);

            var qs = $form.serialize();

            $.post("/sistema/usuarios/save/", qs, function(response){
              if(response.success)
              {
                $.crmPanel.close(idp0);

                $dtBase.ajax.reload( null, false );

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

    if(document.getElementById("pp0")) return false;

    var _id    = $(this).data("id") || 0;
    var _title = $(this).data("title") || "";

    var idp0 = $.crmPanel({
      href: "/sistema/usuarios/" + _id + "/",
      title: _title,
      onLoad: function(){

      },
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
        var xhr = $.post('/sistema/usuarios/'+ $btn.data("id") +'/action/'+ $btn.data("action") +'/', { _token: window.laravel.token }, null, 'json');

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

  $content.on("click", ".btnOpenPanelPerfilList", function(evt){
    evt.preventDefault();

    if(document.getElementById("pp0")) return false;

    var _title = $(this).data("title");

    var idp0 = $.crmPanel({
      href: "/sistema/perfiles/openlist/" + "?" + $.param({}),
      title: _title,
      onLoad: function()
      {
        var $pp0 = $("#"+idp0);

        var $dtPerfiles = $pp0.find('.tblBase').DataTable({
          responsive: true,
          "language": {
          "url": "/static/sistema/libs/DataTables/Spanish.json",
          },
          searching: false,
          order: [],
          processing: true,
          serverSide: true,
          "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
          ajax: "/sistema/perfiles/paginate/" + "?" + "",
          columns: [
            { "data": "opt", "orderable": false, },
            { "data": "nombre", "orderable": false, },
            { "data": "status", "orderable": false, },
            { "data": "opciones", "orderable": false, },
          ],
          "initComplete": function(settings, json){
            $(this).closest(".dataTables_wrapper").on('page.dt', function(){
              $pp0.find(".scrollable").animate({scrollTop: $pp0.find(".scrollable").offset().top - 120}, 'slow');
            });
          },
        });

        $pp0.on("click",".btnOpenPanelForm", function(evt){
          evt.preventDefault();

          if(document.getElementById("pp1")) return false;

          var _id    = $(this).data("id");
          var _title = $(this).data("title");

          var idp1 = $.crmPanel({
            href: "/sistema/perfiles/form/" + "?" + $.param({"id":_id}),
            title: _title,
            onLoad: function()
            {
              var $pp1 = $("#"+idp1);

              $pp1.find(".btnSubmit").off("click").on("click", function(evt){
                evt.preventDefault();

                $form = $pp1.find("form");

                if(componente_formulario.ValidarForm($form))
                {
                  _params = $form.serialize();

                  $.crmPanel.showLoader(idp1);

                  $.post("/sistema/perfiles/save/", _params, function(response){
                    if(response.success)
                    {
                      $dtPerfiles.ajax.reload( null, false );

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

        $pp0.on("click", ".btnAction", function(evt){
          evt.preventDefault();

          $btn = $(this);

          swal({
            text: $btn.data("title"),
            type: "warning",
            showCancelButton: true,
          }).then((result) => {
            if(result.value)
            {
              var xhr = $.post('/sistema/perfiles/'+ $btn.data("id") + '/action/' + $btn.data("action") + '/', { _token: window.laravel.token }, null, 'json');

              xhr.done(function(response){
                if(response.success)
                {
                  toastr.success(response.message);

                  $dtPerfiles.ajax.reload( null, false );
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
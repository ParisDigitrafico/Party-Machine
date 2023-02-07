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
data-title="Buscar Cliente"
data-buscar="{!! (!empty($b = request()->get('b')) ? http_build_query(['b'=>$b]) : '') !!}">Buscar</a>

@if(in_array("C_CLIENTE", session("permisos")))
<a href="#" class="au-btn au-btn-icon au-btn--blue au-btn--small btnOpenPanelForm"
data-id=""
data-title="Agregar Cliente">
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
<th data-priority="1">Código</th>
<th data-priority="1">Usuario</th>
<th data-priority="1">Nombre</th>
<th data-priority="1">Apellidos</th>
<th data-priority="1">Empresa</th>
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

  var cUrlController = "/sistema/clientes"; 

  var $dtBase = $content.find('.tblBase').DataTable({
    responsive: true,
    "language": { "url": "/static/sistema/libs/DataTables/Spanish.json", },
    "searching": false,
    "order": [],
    "processing": true,
    "serverSide": true,
    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
    "ajax": "/sistema/clientes/paginate/" + "?" + "{!! (!empty($b = request()->get('b')) ? http_build_query(['b'=>$b]) : '') !!}",
    "columns": [
      { "data": "opt", "orderable":false, "className":"d-none d-md-revert" },

      { "data": "codigo", "orderable":false, },
      { "data": "user", "orderable":false, },
      { "data": "nombre", "orderable":false, },
      { "data": "apellidos", "orderable":false, },
      { "data": "empresa", "orderable":false, },

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

    $btn = $(this);

    var _title  = $btn.data("title");
    var _buscar = $btn.data("buscar");

    var idp0 = $.crmPanel({
      href: "/sistema/clientes/search/" + "?" + _buscar,
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

    var _id    = $(this).data("id");
    var _title = $(this).data("title");

    var idp0 = $.crmPanel({
      href: "/sistema/clientes/form/" + "?" + $.param({"id":_id}),
      title: _title,
      onLoad: function()
      {
        var $pp0 = $("#"+idp0);

        componente_cliente.create_row_contact_table($pp0.find(".tblTelefono"));
        componente_cliente.create_row_email_table($pp0.find(".tblCorreo"));
        componente_cliente.create_row_symptom_table($pp0.find(".tblSintoma"));
        componente_cliente.create_row_family_table($pp0.find(".tblFamiliar"));

        $pp0.find(".btnSubmit").off("click").on("click", function(evt){
          evt.preventDefault();

          $form = $pp0.find("form");

          if(componente_formulario.ValidarForm($form))
          {
            qs = $form.serialize();

            $.crmPanel.showLoader(idp0);

            $.post("/sistema/clientes/save/", qs, function(response){
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

        $pp0.on("click", ".btnForm", function(evt){
          evt.preventDefault();

          if(document.getElementById("pp1")) return false;

          $table = $(this).closest(".area_dynamic_table").find("table");

          if($table.hasClass("tblTelefono"))
          {
            $tr = $(this).closest("tr");

            var opts = {
              "id" : $tr.data("id") || "",
              "clave" : $tr.data("clave"),
              "descripcion" : $tr.data("descripcion"),
              "status" : $tr.data("status"),
            };

            var idp1 = $.crmPanel({
              href: "/sistema/clientes/contacto/telefono/form/" + "?" + $.param(opts),
              title: $(this).data("title"),
              onLoad: function()
              {
                var $pp1 = $("#"+idp1);

                $pp1.find(".btnSubmit").off("click").on("click", function(evt){
                  evt.preventDefault();

                  $form = $(this).closest("form");

                  obj = $form.serializeObject();

                  $tr = $table.find("tbody > tr.item_" + obj.Dato["id"]);

                  if($tr.length > 0)
                  {
                    $tr.data("clave", obj.Dato["clave"]);
                    $tr.data("descripcion", obj.Dato["descripcion"]);
                  }
                  else
                  {
                    var $tr = $('<tr/>');

                    $tr.addClass("item_" + obj.Dato["id"]);

                    $tr.attr("data-id", obj.Dato["id"]);
                    $tr.attr("data-clave", obj.Dato["clave"]);
                    $tr.attr("data-descripcion", obj.Dato["descripcion"]);

                    $table.find("tbody").append($tr);
                  }

                  componente_cliente.create_row_contact_table($table)

                  $.crmPanel.close(idp1);
                });
              },
            });

          }

          if($table.hasClass("tblCorreo"))
          {
            $tr = $(this).closest("tr");

            var opts = {
              "id" : $tr.data("id") || "",
              "clave" : $tr.data("clave"),
              "descripcion" : $tr.data("descripcion"),
              "status" : $tr.data("status"),
            };

            var idp1 = $.crmPanel({
              href: "/sistema/clientes/contacto/correo/form/" + "?" + $.param(opts),
              title: $(this).data("title"),
              onLoad: function()
              {
                var $pp1 = $("#"+idp1);

                $pp1.find(".btnSubmit").off("click").on("click", function(evt){
                  evt.preventDefault();

                  $form = $(this).closest("form");

                  obj = $form.serializeObject();

                  $tr = $table.find("tbody > tr.item_" + obj.Dato["id"]);

                  if($tr.length > 0)
                  {
                    $tr.data("clave", obj.Dato["clave"]);
                    $tr.data("descripcion", obj.Dato["descripcion"]);
                  }
                  else
                  {
                    var $tr = $('<tr/>');

                    $tr.addClass("item_" + obj.Dato["id"]);

                    $tr.attr("data-id", obj.Dato["id"]);
                    $tr.attr("data-clave", obj.Dato["clave"]);
                    $tr.attr("data-descripcion", obj.Dato["descripcion"]);

                    $table.find("tbody").append($tr);
                  }

                  componente_cliente.create_row_email_table($table)

                  $.crmPanel.close(idp1);
                });
              },
            });
          }

          if($table.hasClass("tblSintoma"))
          {
            $tr = $(this).closest("tr");

            var opts = {
              "id" : $tr.data("id") || "",
              "clave" : $tr.data("clave"),
              "descripcion" : $tr.data("descripcion"),
              "status" : $tr.data("status"),
            };

            var idp1 = $.crmPanel({
              href: "/sistema/clientes/sintoma/form/" + "?" + $.param(opts),
              title: $(this).data("title"),
              onLoad: function()
              {
                var $pp1 = $("#"+idp1);

                $pp1.find(".btnSubmit").off("click").on("click", function(evt){
                  evt.preventDefault();

                  $form = $(this).closest("form");

                  obj = $form.serializeObject();

                  $tr = $table.find("tbody > tr.item_" + obj.Dato["id"]);

                  if($tr.length > 0)
                  {
                    $tr.data("clave", obj.Dato["clave"]);
                    $tr.data("descripcion", obj.Dato["descripcion"]);
                  }
                  else
                  {
                    var $tr = $('<tr/>');

                    $tr.addClass("item_" + obj.Dato["id"]);

                    $tr.attr("data-id", obj.Dato["id"]);
                    $tr.attr("data-clave", obj.Dato["clave"]);
                    $tr.attr("data-descripcion", obj.Dato["descripcion"]);

                    $table.find("tbody").append($tr);
                  }

                  componente_cliente.create_row_symptom_table($table)

                  $.crmPanel.close(idp1);
                });
              },
            });
          }

          if($table.hasClass("tblFamiliar"))
          {
            $tr = $(this).closest("tr");

            var opts = {
              "id" : $tr.data("id"),
              "usuario_id" : $pp0.find("#id").val(),
              "idpariente" : $tr.find(".idpariente").val(),
              "idparentesco" : $tr.find(".idparentesco").val(),
            };

            var idp1 = $.crmPanel({
              href: "/sistema/clientes/familiar/form/" + "?" + $.param(opts),
              title: $(this).data("title"),
              onLoad: function()
              {
                var $pp1 = $("#"+idp1);

                $pp1.find(".btnSubmit").off("click").on("click", function(evt){
                  evt.preventDefault();

                  $form = $(this).closest("form");

                  obj = $form.serializeObject();

                  $tr = $table.find("tbody > tr.item_" + $form.find("#id").val());

                  if($tr.length > 0)
                  {
                    $tr.data("clave", $form.find("#idpariente option").filter(':selected').data("nombre"));
                    $tr.data("descripcion", $form.find("#idparentesco option").filter(':selected').data("nombre"));
                    $tr.data("idpariente", obj.Dato["idpariente"]);
                    $tr.data("idparentesco", obj.Dato["idparentesco"]);
                  }
                  else
                  {
                    var $tr = $('<tr/>');

                    $tr.addClass("item_" + obj.Dato["id"]);

                    $tr.attr("data-id", obj.Dato["id"]);
                    $tr.attr("data-clave", $form.find("#idpariente option").filter(':selected').data("nombre"));
                    $tr.attr("data-descripcion", $form.find("#idparentesco option").filter(':selected').data("nombre"));
                    $tr.attr("data-idpariente", obj.Dato["idpariente"]);
                    $tr.attr("data-idparentesco", obj.Dato["idparentesco"]);

                    $table.find("tbody").append($tr);
                  }

                  componente_cliente.create_row_family_table($table)

                  $.crmPanel.close(idp1);
                });
              },
            });
          }


        });

        $pp0.on("click", ".btnQuit", function(evt){
          evt.preventDefault();

          $tr = $(this).closest("tr");

          $tr.remove();
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
      href: "/sistema/clientes/" + _id + "/",
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
        var xhr = $.post('/sistema/clientes/'+ $btn.data("id") +'/action/'+ $btn.data("action") +'/', { _token: window.laravel.token }, null, 'json');

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
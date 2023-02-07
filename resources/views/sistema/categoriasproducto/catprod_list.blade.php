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

@if(in_array("C_PRODUCTO", session("permisos")))
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

<th data-priority="1">Nombre</th>
<th data-priority="1">Foto</th>
<!--<th data-priority="2">Categoría Base</th>-->

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

  var cUrlController = "/sistema/categoriasproducto";

  var $dtBase = $content.find('.tblBase').DataTable({
    responsive: true,
    "language": { "url": "/static/sistema/libs/DataTables/Spanish.json", },
    "searching": false,
    "order": [],
    "processing": true,
    "serverSide": true,
    /*"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],  */
    "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
    "ajax": cUrlController + "/paginate/" + "?" + "{!! (!empty($b = request()->get('b')) ? http_build_query(['b'=>$b]) : '') !!}",
    "columns": [
      { "data": "opt", "orderable":false, "className":"d-none d-md-revert" },

      { "data": "name", "orderable":false, },
      { "data": "photo", "orderable":false, },

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

    if(document.getElementById("pp0")) return false;

    $btn = $(this);

    var _id     = $btn.data("id");
    var _title  = $btn.data("title");
    var _json64 = $btn.data("json64");

    var idp0 = $.crmPanel({
      href: cUrlController + "/form/?" + $.param({"id":_id,"json64":_json64}),
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

            setTimeout(() => {
              var formData = new FormData($form[0]);

              xhr = $.ajax({
                url: cUrlController + "/save/",
                type: 'post',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                enctype: 'multipart/form-data',
                processData: false,
                dataType: 'json',
              });

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
            }, 199);
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
        xhr = $.post(cUrlController + "/" + $btn.data("id") + "/action/" + $btn.data("action") + "/", { _token: window.laravel.token }, null, 'json');

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

  $content.on("click", ".btnOpenPanelSubCategorias", function(evt){
    evt.preventDefault();

    if(document.getElementById("pp0")) return false;

    $btn = $(this);

    var _idparent = $btn.data("id");
    var _name     = $btn.data("name");
    var _title    = $btn.data("title");

    var idp0 = $.crmPanel({
      href: cUrlController + "/" + _idparent + "/subcategorias/" + "?" + $.param({"name":_name}),
      title: _title,
      onLoad: function()
      {
        var $pp0 = $("#"+idp0);

        var $dtSubcategorias = $pp0.find('.tblBase').DataTable({
          responsive: true,
          "language": { "url": "/static/sistema/libs/DataTables/Spanish.json", },
          searching: false,
          order: [],
          processing: true,
          serverSide: true,
          "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
          ajax: cUrlController + "/paginate/" + "?" + $.param({"idParent":_idparent}),
          columns: [
            { "data": "opt", "orderable": false, },

            { "data": "name", "orderable": false, },
            { "data": "photo", "orderable": false, },

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

          $btn = $(this);

          var _id     = $btn.data("id");
          var _title  = $btn.data("title");
          var _json64 = $btn.data("json64");

          var idp1 = $.crmPanel({
            href: cUrlController + "/form/" + "?" + $.param({"id":_id, "parent": _idparent, "json64":_json64}),
            title: _title,
            onLoad: function()
            {
              var $pp1 = $("#"+idp1);

              $pp1.find(".btnSubmit").off("click").on("click", function(evt){
                evt.preventDefault();

                $form = $pp1.find("form");

                if(componente_formulario.ValidarForm($form))
                {
                  $.crmPanel.showLoader(idp1);

                  setTimeout(() => {
                    var formData = new FormData($form[0]);

                    var xhr = $.ajax({
                      url: cUrlController + "/save/",
                      type: 'post',
                      data: formData,
                      async: false,
                      cache: false,
                      contentType: false,
                      enctype: 'multipart/form-data',
                      processData: false,
                      dataType: 'json',
                    });

                    xhr.done(function(response){
                      if(response.success)
                      {
                        $dtSubcategorias.ajax.reload( null, false );

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
                    });
                  }, 199);
                }
              });
            },
          });
        });

      },
    });
  });

});
</script>
@endpush
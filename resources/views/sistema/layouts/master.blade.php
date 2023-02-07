<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
<!-- meta tags-->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- title Page-->
<title>Sistema</title>

<!--<link rel="icon" href="/favicon.ico" type="image/x-icon">  -->
<link rel="shortcut icon" href="/favicon.ico" />

@include('sistema.partials.styles')

<style type="text/css">
.main-content
{
  min-height: 100vh;
}

table.dataTable thead td, table.dataTable thead th
{
  padding-left: 10px !important;
}

/*body.fade-out{
  -webkit-animation-name: none;
  animation-name: none;
}*/

.select2-container--default .select2-selection--single,
/*.select2-container--default.select2-container--focus .select2-selection--multiple */
.select2-container--default.select2-container .select2-selection--multiple
{
  border-color: rgba(217, 217, 217, 1);
}

.select2-selection__rendered
{
  line-height: 38px!important;
}

.select2-selection--single,
.select2-selection__arrow
{
  height: 38px!important;
}

.select2-selection.select2-selection--multiple .select2-selection__rendered
{
  line-height: 30px !important;
}

.select2-selection.select2-selection--multiple .select2-search
{
  width: 0 !important;
}

.select2-selection.select2-selection--multiple .select2-search::before
{
  display: none !important;
}

.fullbg
{
  background-position: center center;
  background-repeat: no-repeat;
  background-size: cover;
}

.adjustbg
{
  background-position: center !important; background-size:contain !important;
}

.header-mobile .header-mobile__bar {
  padding: 5px 0;
}

header .header-mobile-inner a.logo
{
  background-color: #FFF !important;
}

.brick.small{
  width: 30%;
}

@media (max-width: 992px){
  .brick.small{
    width: 46%;
  }
}

@media (max-width: 576px){
  .brick.small{
    width: 96%;
  }
}

.area_simple
{
  width: 100%; padding:4px; height: calc(100% - 60px); overflow: hidden; overflow-y: scroll;
}

.area_tabs
{
  width: 100%; padding:4px; height: calc(100% - 60px); overflow: hidden;
}

.panel_modal{
  /*box-shadow: -9px 7px 25px -4px #808080;  */
  box-shadow: -5px 33px 19px #000;
  /*box-shadow: -1px 0 0 #C2C2C2; */
}

#secondary{
  border-top: 1px solid #C2C2C2;
  border-left: 1px solid #D9D9D9;
}

#panelTitles{
  z-index: 991;
  /*border-bottom: 2px solid #cccccc;*/
}

.modal-backdrop.show
{
  display: none;
}
</style>

@stack("css")
</head>

<body class="animsition">
<div id="overconn"
style="z-index: 999999; position: fixed; display: none; width: 100%; height: 100%; left: 0px; right: 0px; bottom: 0px;
background-color: rgba(0, 0, 0, 0.6);">
<p id="overlayMessage" style="color: white; font-size: 5em; font-weight: bold; position: absolute; bottom: 0px; margin: 1em;">No hay conexi&oacute;n de internet</p>
</div>

<div class="page-wrapper">
@include("sistema.partials.menu")
<!-- PAGE CONTAINER-->
<div class="page-container">
<!-- HEADER DESKTOP-->
<header class="header-desktop">
<div class="section__content section__content--p30">
<div class="container-fluid">
<div class="header-wrap">
<form class="form-header" action="" method="POST">
<div class="d-none d-lg-block">
<span style="color: #FFFFFF">Sistema Party Machine</span>
</div>
<!--<input class="au-input au-input--xl" type="text" name="search" placeholder="Search for datas &amp; reports..." />
<button class="au-btn--submit" type="submit">
<i class="zmdi zmdi-search"></i>
</button>-->
</form>
<div class="header-button">

<div class="noti-wrap">
@include("sistema.partials.area_notificacion")
</div>

<div class="account-wrap">
@include("sistema.partials.area_usuario")
</div>

</div>
</div>
</div>
</div>
</header>
<!-- HEADER DESKTOP-->

<!-- MAIN CONTENT-->
<div class="main-content">
<div class="section__content section__content--p30">
<div class="container-fluid" style="min-height: 75vh">
@stack("content")
</div>
</div>
@include("sistema.partials.footer")
</div>
<!-- END MAIN CONTENT-->
</div>

</div>

@include('sistema.partials.scripts')

<script type="text/javascript">
window.laravel = {!! json_encode([
        'token' => csrf_token(),
    ]) !!};

$(document).ready(function(){
  var $li_active = $("div.menu-sidebar__content").find("li.active").first();

  if($li_active.length > 0)
  {
    $(".menu-sidebar").find(".active").closest("ul").css({"display":"block"});

    scrollTop = $li_active.offset().top;

    if(scrollTop < 400)
    {
      scrollTop = 0;
    }

    $("div.menu-sidebar__content").scrollTop(scrollTop);
  }

  //overlay.show('No hay conexion de internet.');

  toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-bottom-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut",
  };

  swal.setDefaults({
    confirmButtonColor: "#3085d6",
    confirmButtonText: "Aceptar",
    cancelButtonText: "Cancelar",
  });

  function loadInfo()
  {
    $.get('/sistema/info/', null, null, 'json')
      .done(function(response){
        if(response.success)
        {
          var cht = '';

          cht+='<center><span style="color: #FFFFFF; font-weight: bold">Tipo de cambio<br>';
          cht+='1 USD = '+ response.data.cambio_usd +' MXN</span></center>';

          $(document).find("#divCambioUSD").html(cht);

          // Actualizar notificaciones

          cht = '';

          cht+='<i class="zmdi zmdi-notifications"></i>';

          if(response.data.not_sin_leer > 0)
          {
            cht+='<span class="quantity">'+ response.data.not_sin_leer +'</span>';
          }

          cht+='<div class="notifi-dropdown js-dropdown">';

          cht+='<div class="notifi__footer"><a href="#" onclick="location.href=\'/sistema/notificaciones/\';">Ver todo</a></div></div>';

          $(document).find("#divNotificaciones").html(cht);
        }
      })
      .always(function(){
        <?php if(config("app.env") == "production"): ?>
        setTimeout(loadInfo, 9999);
        <?php endif; ?>
      });
  }

  /*loadInfo();  */

  function checkAppConnection()
  {
    $overconn = $("#overconn");
    $body     = $("body");

    if(!$body.hasClass("fade-out"))
    {
      fetch('/favicon.ico?d='+Date.now())
        .then(response => {
          if(!response.ok)
            throw new Error('Network response was not ok');

          $overconn.hide();
          $body.css({"overflow-y":"auto"});
        })
        .catch(error => {
          $overconn.show();
          $body.css({"overflow-y":"hidden"});
        });
    }

    <?php if(config("app.env") == "production"): ?>
    setTimeout(checkAppConnection, 1999);
    <?php endif; ?>
  }

  /*checkAppConnection(); */
});

function load_select2(cSelector)
{
  cSelector = cSelector || ".select2";

  $elem = $(cSelector);

  $elem.each(function(index, element){
    var $current = $(element);

    var config = {
      minimumResultsForSearch: $current.attr("data-minimumResultsForSearch") ? $current.attr("data-minimumResultsForSearch") : "", // {iNum} or Infinity
      allowClear: $current.attr("data-allowClear") === "true",
      tags: $current.attr("data-tags") === "true",
      matcher: function(params, data){
        params.term = params.term || '';

        if($.trim(params.term) === '')
        {
          return data;
        }

        var words = (params.term).split(" ");

        for(var i = 0; i < words.length; ++i)
        {
          var cTexto   = util.removeAccents(data.text.toLowerCase());
          var cPalabra = util.removeAccents(words[i].toLowerCase());

          if(cTexto.indexOf(cPalabra) == -1)
            return null;
        }

        return data;
      },
    };

    if($current.attr("data-theme"))
    {
      config.theme = $current.attr("data-theme");
    }

    if($current.attr("data-placeholder"))
    {
      config.placeholder = $current.attr("data-placeholder");
    }

    $current.select2(config);
  });
}

</script>

@stack('js')
</body>

</html>
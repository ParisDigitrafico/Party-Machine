<!DOCTYPE html>
<html lang="es">

<head>
<!-- Required meta tags-->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Title Page-->
<title>Login</title>

<!-- Fontfaces CSS-->
<link href="/static/sistema/sys/css/font-face.css" rel="stylesheet" media="all">
<link href="/static/sistema/libs/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
<link href="/static/sistema/libs/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

<!-- Bootstrap CSS-->
<link href="/static/sistema/libs/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

<!-- Vendor CSS-->
<link href="/static/sistema/libs/animsition/dist/css/animsition.min.css" rel="stylesheet" media="all">
<link href="/static/sistema/libs/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
<link href="/static/sistema/libs/wow/animate.css" rel="stylesheet" media="all">
<link href="/static/sistema/libs/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
<link href="/static/sistema/libs/slick/slick.css" rel="stylesheet" media="all">
<link href="/static/sistema/libs/select2/select2.min.css" rel="stylesheet" media="all">
<link href="/static/sistema/libs/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">

<!-- Main CSS-->
<link href="/static/sistema/sys/css/theme.css" rel="stylesheet" media="all">

</head>

<body class="animsition">
<div class="page-wrapper">
<div class="page-content--bge5" style="background: #acacac;">
<div class="container">
<div class="login-wrap">
<div class="login-content">
<div class="login-logo">
<a href="#">
<img src="{{ get_asset('/static/sistema/images/logo.png') }}" style="max-height: 160px">
</a>
</div>
<div class="login-form">
<form id="frmLogin" action="/sistema/home/">

<input type="hidden" id="_token" value="{{ csrf_token() }}" />

<div class="form-group">
<!--<label></label>      -->
<input class="au-input au-input--full" id="txtUser" type="email" placeholder="Correo Electrónico">
</div>
<div class="form-group">
<!--<label>Contraseña</label>   -->
<input class="au-input au-input--full" id="txtPass" type="password" placeholder="Contraseña">
</div>
<div class="login-checkbox">
<label>
<input type="checkbox" id="optRmbr">Recordarme
</label>
<label>
<a href="forgot/" style="color: #FF0033">¿Olvid&oacute; su contrase&ntilde;a?</a>
</label>
</div>
<button class="au-btn au-btn--block au-btn--green m-b-20 btnSubmit" style="background-color: #00172B" type="submit">Aceptar</button>
<!--  <div class="social-login-content">
<div class="social-button">
<button class="au-btn au-btn--block au-btn--blue m-b-20">sign in with facebook</button>
<button class="au-btn au-btn--block au-btn--blue2">sign in with twitter</button>
</div>
</div>-->
</form>
<div class="register-link">
<p>
¿No tienes cuenta?
<a href="#" style="color: #FF0033">Registrate</a>
</p>
</div>
</div>
</div>
</div>
</div>
</div>

</div>

<!-- Jquery JS-->
<script src="/static/sistema/libs/jquery-3.2.1.min.js"></script>
<!-- Bootstrap JS-->
<script src="/static/sistema/libs/bootstrap-4.1/popper.min.js"></script>
<script src="/static/sistema/libs/bootstrap-4.1/bootstrap.min.js"></script>
<!-- Vendor JS       -->
<script src="/static/sistema/libs/slick/slick.min.js"></script>
<script src="/static/sistema/libs/wow/wow.min.js"></script>
<script src="/static/sistema/libs/animsition/dist/js/animsition.min.js"></script>
<script src="/static/sistema/libs/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
<script src="/static/sistema/libs/counter-up/jquery.waypoints.min.js"></script>
<script src="/static/sistema/libs/counter-up/jquery.counterup.min.js"></script>
<script src="/static/sistema/libs/circle-progress/circle-progress.min.js"></script>
<script src="/static/sistema/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="/static/sistema/libs/chartjs/Chart.bundle.min.js"></script>
<script src="/static/sistema/libs/select2/select2.min.js"></script>

<script src="/static/generico/js/crypto-js-3.1.8/crypto-js.js"></script>

<!-- Main JS-->
<script src="/static/sistema/sys/js/main.js"></script>

<script type="text/javascript">
var url = location.href;

if(!(url.indexOf("/sistema/login/") > -1))
{
  location.href='/sistema/login/';
}

$(document).ready(function(){
  $(document).on("click", ".btnSubmit", function(evt){
    evt.preventDefault();

    $btnSubmit = $(this);

    $form = $("#frmLogin");

    $btnSubmit.attr("disabled", true);

    var _cSecretKey = '{{ session("SECRETKEYLOGIN") }}';

    var objAux = {
      "_token": $form.find("#_token").val(),
      "txtUser": CryptoJS.AES.encrypt($form.find("#txtUser").val(), _cSecretKey).toString(),
      "txtPass": CryptoJS.AES.encrypt($form.find("#txtPass").val(), _cSecretKey).toString(),
      "optRmbr": CryptoJS.AES.encrypt(($form.find("#optRmbr").is(':checked')? "1": "0"), _cSecretKey).toString(),
    };

    $.post('/sistema/login/authentication/', objAux, function(response){
      if(response.success)
      {
        $form.submit();
      }
      else
      {
        alert(response.message);
      }
    }, 'json' ).always(function(){
      $btnSubmit.attr("disabled", false);
    });
  });
});
</script>
</body>

</html>
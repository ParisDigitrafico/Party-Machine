<!DOCTYPE html>
<html xml:lang="es" lang="es">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Restablecer Contraseña</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="/static/sistema/libs/bootstrap-4.1/bootstrap.min.css" />

<link rel="stylesheet" href="/static/sistema/sys/css/theme.css" />

</head>

<body>
<div class="page-wrapper">
<div class="page-content--bge5" style="background: #acacac;">
<div class="container">
<div class="login-wrap">
<div class="login-content">
<div class="login-logo">
<h3 style="font-weight: bold">Restablecer Contrase&ntilde;a</h3>

<br>

<div style="text-align: justify">
<span style="font-size: 10pt; text-align:">
*Para restablecer su contrase&ntilde;a ingrese su correo electr&oacute;nico y pulse Aceptar.</span>
</div>
</div>
<div class="login-form">
<form action="javascript:void(0);">

<input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}" />

<div class="form-group">
<input type="email" id="txtCorreo" class="au-input au-input--full" placeholder="Correo Electrónico">
</div>

<div class="areaSubmit">
<button class="au-btn au-btn--block au-btn--green m-b-20 btnSubmit"
  style="background-color: #00172B" type="submit">Aceptar</button>
</div>

<div class="areaInstruccion" style="display: none;">
<div class="form-group areaMessage" style="text-align: justify">
Se ha enviado un mensaje a su correo con las intrucciones para restablecer la contrase&ntilde;a, si no lo encuentra revise en su bandeja de SPAM.
</div>

<div class="form-group areaResend">
<a href="javascript:void(0);" class="btnResend">Reenviar Correo &gt;</a>
</div>
</div>

</form>
</div>
</div>
</div>
</div>
</div>

</div>

<!-- vendor JS -->
<script src="/static/sistema/libs/jquery-3.2.1.min.js"></script>

<script src="/static/sistema/libs/bootstrap-4.1/popper.min.js"></script>
<script src="/static/sistema/libs/bootstrap-4.1/bootstrap.min.js"></script>

<script src="/static/generico/js/crypto-js-3.1.8/crypto-js.js"></script>

<!-- custom JS -->
<!--<script src="/static/js/util_220103.js"></script>   -->

<script type="text/javascript">
$(document).ready(function(){
  $(document).on("click", ".btnSubmit", function(evt){
    evt.preventDefault();

    $btnSubmit = $(this);

    $form = $btnSubmit.closest("form");

    $form.find("#txtCorreo").attr("readonly","readonly");

    $btnSubmit.prop('disabled', true);

    qs = $form.serialize();

    ajx = $.post('/sistema/login/forgot/', qs, null, 'json');

    ajx.done(function(response){
      if(response.success)
      {
        $(".areaSubmit").hide();
        $(".areaInstruccion").show();
      }
      else
      {
        alert(response.message);

        $form.find("#txtCorreo").removeAttr("readonly");
        $btnSubmit.prop('disabled', false);
      }
    });

    ajx.fail(function(jqXHR, textStatus){
      if(textStatus != "success")
      {
        $form.find("#txtCorreo").removeAttr("readonly");
        $btnSubmit.prop('disabled', false);
      }
    });
  });

  $(document).on("click", ".btnResend", function(evt){
    evt.preventDefault();

    $btn = $(this);

    $form = $btn.closest("form");

    var qs = $form.serialize();

    var extra_params = {
      isResend:true,
    };

    var ajx = $.post('/sistema/login/forgot/', qs + '&' + $.param(extra_params), null, 'json');

    ajx.done(function(response){
      if(response.success)
      {
        $(".areaMessage").html(response.message);
        $(".areaResend").hide();
      }
    });
  });
});
</script>
</body>
</html>
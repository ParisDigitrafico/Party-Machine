<!DOCTYPE html>
<html xml:lang="es" lang="es">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Restablecer Contrase&ntilde;a</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- plugins CSS -->
<link rel="stylesheet" href="/static/generico/js/bootstrap-3.3.7/dist/css/bootstrap.min.css" />

<style type="text/css">
body{background:#9E9E9E}
.panel-style{padding-top:70px}
</style>
</head>
<body>
<div class="container">
<div class="col-md-4 col-md-offset-4 panel-style">
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title">
<strong>
Restablecer Contrase&ntilde;a
</strong>
</h3>
</div>
<div class="panel-body">
<form id="frmLogin" action="javascript:void(0);">

<input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}" />

<div>
<?= $message ?>
</div><br />

</form>
</div>
</div>
</div>
</div>

<!-- plugins JS-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.8/crypto-js.min.js"></script>

<!-- custom JS-->
<script src="/static/js/util_220103.js"></script>

</body>
</html>
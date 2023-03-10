<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ ($page_title ? $page_title . ' - Party Machine': '') ?: 'Party Machine' }}</title>

{!! $page_metas !!}

@include("website.generico.styles")

</head>

<body style="background-color:grey;">

<div class="wrapper" style="position:relative; max-width:1920px; margin:0 auto; background-color:white;">
@include("website.partials.headers.header")

@stack("main")

@include("website.partials.footers.footer")
</div>

@include("website.generico.scripts")

<script type="text/javascript">
window.laravel = {!! json_encode([
                'token' => csrf_token(),
                ]) !!};

componente_plugins.init();
</script>

@stack("js")
</body>
</html>

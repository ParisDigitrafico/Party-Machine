<body rightmargin="0" leftmargin="0" topmargin="0" bottommargin="0" marginwidth="0" marginheight="0" offset="0"
  style="margin-top:0px; margin-right:0px; margin-bottom:0px; margin-left:0px; padding-top:0px; padding-right:0px; padding-bottom:0px; padding-left:0px; border:0px;">

<style type="text/css">
p, li {
  font-size: 11pt;
}
</style>

<div class="container" style="margin:20px auto; max-width:600px; background-color:#FFF; border: 1px solid #777777; overflow: hidden;">
<div class="row">

<div style="padding: 20px; text-align: center;">
<img src="{{ env('APP_URL') }}/static/sistema/images/logo2.png?{{ config('app.version') }}" width="400" border="0" />
</div>

{%HEADER%}

<div style="padding: 20px; min-height: 200px; margin-top: 0; padding-top: 0;">
{%BODY%}
</div>

<div style="padding:20px; background-color: #09094A;">
{%FOOTER%}
</div>

</div>
</div>
</body>
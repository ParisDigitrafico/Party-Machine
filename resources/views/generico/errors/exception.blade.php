<?php
$user   = Auth::check() ? Auth::user()->name : 'no login'; 
$action = Route::getCurrentRoute()->getActionName() ?? "n/a";
$site   = url()->full();
?>

There was an error in your Laravel App<br />

<hr />
<table border="1" width="100%">
<tr><th style="vertical-align: top;">User:</th><td>{{ $user }}</td></tr>
<tr><th style="vertical-align: top;">Action:</th><td>{{ $action }}</td></tr>
<tr><th style="vertical-align: top;">Site:</th><td>{{ $site }}</td></tr>
<tr><th style="vertical-align: top;">Method:</th><td>{{ $_SERVER["REQUEST_METHOD"] }}</td></tr>
<tr><th style="vertical-align: top;">Request:</th><td>{{ var_dump(Request::all()) }}</td></tr>
<tr><th style="vertical-align: top;">Message:</th><td>{{ $err['message'] }}</td></tr>
<tr><th style="vertical-align: top;">Code:</th><td>{{ $err['code'] }}</td></tr>
<tr><th style="vertical-align: top;">File:</th><td>{{ $err['file'] }}</td></tr>
<tr><th style="vertical-align: top;">Line:</th><td>{{ $err['line'] }}</td></tr>
<tr><th style="vertical-align: top;">Trace:</th><td>{{ $err['trace'] }}</td></tr>
</table>
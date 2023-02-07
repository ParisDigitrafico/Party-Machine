<?php
/*$objModulo       = new sys_seccion();
$objCuentaModulo = new sys_cuenta_seccion();*/
$arrModulo = array();

/*$Modulo = array();

$Modulo["clave"]    = "";
$Modulo["idpadre"]  = "0";
$Modulo["nombre"]   = "Dashboard";
$Modulo["es_menu"]  = 1;
$Modulo["url_menu"] = "/sistema/dashboard/";
$Modulo["icon"]     = "fa-tachometer-alt";

$arrModulo[] = $Modulo;*/

/*$Modulo["clave"]    = "";
$Modulo["class"]    = "noticlass";
$Modulo["idpadre"]  = "0";
$Modulo["nombre"]   = "Notificaciones";
$Modulo["es_menu"]  = 1;
$Modulo["url_menu"] = "/sistema/notificaciones/";
$Modulo["icon"]     = "fa-tachometer-alt";

$arrModulo[] = $Modulo;*/

/*$Modulo = array();

$Modulo["clave"]    = "";
$Modulo["idpadre"]  = "0";
$Modulo["nombre"]   = "Notificaciones";
$Modulo["es_menu"]  = 1;
$Modulo["url_menu"] = "/sistema/notificaciones/";
$Modulo["icon"]     = "fa-bell";

$arrModulo[] = $Modulo;*/

$arrAux = (array)session("modulos");

foreach($arrAux as $Modulo)
{
  if($Modulo["es_menu"])
  {
    /*if($Modulo["clave"] == "M_ESCANEAR")
    {
      $objZona = new \App\Models\Zona();

      $objZona->Consultar("", "", "", "", "", " AND status=1 AND length(char(deleted_at)) = 0 ");

      $arrAux = array();

      while($objZona->Recorrer())
      {
        $SubModulo = array();

        $SubModulo["nombre"]   = $objZona->nombre;
        $SubModulo["url_menu"] = "/sistema/escanear/" . $objZona->id . "/";
        $SubModulo["es_menu"]  = 1;

        $arrAux[] = $SubModulo;
      }

      $Modulo["SubModulos"] = $arrAux;

    }
*/
    $arrModulo[] = $Modulo;
  }
}
?>

<!-- HEADER MOBILE-->
<header class="header-mobile d-block d-lg-none">

<div class="header-mobile__bar">
<div class="container-fluid">
<div class="header-mobile-inner">
<a class="logo" href="/sistema/">
<img src="{{ get_asset('/static/sistema/images/logo.png') }}" style="max-height: 76px !important;" /><br>
</a>
<button class="hamburger hamburger--slider" type="button">
<span class="hamburger-box">
<span class="hamburger-inner"></span>
</span>
</button>
</div>
</div>
</div>

<nav class="navbar-mobile">
<div class="container-fluid">
<ul class="navbar-mobile__list list-unstyled">
<? foreach($arrModulo as $Modulo): ?>

  <? if(!empty($Modulo["SubModulos"])): ?>
  <li class="has-sub">
  <a class="js-arrow open" href="#">
  <i class="<?= $Modulo["icon"] ?>"></i><?= $Modulo["nombre"] ?>&nbsp;<i class="fas fa-caret-down"></i></a>
    <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
      <? foreach($Modulo["SubModulos"] as $SubModulo): ?>
        <li class="<?= ( (after(get_host(), str_finish(url()->current(), '/')) == $SubModulo["url_menu"]) ? "active" : "") ?>">
        <a href="<?= $SubModulo["url_menu"] ?>"><?= $SubModulo["nombre"] ?></a>
        </li>
      <? endforeach; ?>
    </ul>
  </li>
  <? elseif($Modulo["es_menu"]): ?>
  <li class="<?= ( (after(get_host(), str_finish(url()->current(), '/')) == $Modulo["url_menu"] ) ? "active" : "") ?>" data-id="<?= $Modulo["idseccion"] ?>">
  <a href="<?= $Modulo["url_menu"] ?>">
  <i class="<?= $Modulo["icon"] ?>"></i><?= $Modulo["nombre"] ?></a>
  </li>
  <? endif; ?>

<? endforeach; ?>
</ul>
</div>
</nav>
</header>
<!-- END HEADER MOBILE-->

<!-- MENU SIDEBAR-->
<aside class="menu-sidebar d-none d-lg-block">
<div class="logo">
<a href="/sistema/" style="margin: 0 auto;">
<img src="{{ get_asset('/static/sistema/images/logo.png') }}" style="max-height: 60px !important;" />
</a>
</div>

<div class="menu-sidebar__content js-scrollbar1">
<nav class="navbar-sidebar" style="padding-top:20px;">
<ul class="list-unstyled navbar__list">

<? foreach($arrModulo as $Modulo): ?>

  <? if(!empty($Modulo["SubModulos"])): ?>
  <li class="has-sub">
    <a class="js-arrow open" href="#">
    <i class="<?= $Modulo["icon"] ?>"></i><?= $Modulo["nombre"] ?>&nbsp;<i class="fas fa-caret-down"></i></a>
    <ul class="list-unstyled navbar__sub-list js-sub-list">
      <? foreach($Modulo["SubModulos"] as $SubModulo): ?>
        <li data-url="{{ url()->current() }}" class="<?= ( (after(get_host(), str_finish(url()->current(), '/')) == $SubModulo["url_menu"] ) ? "active" : "") ?>">
        <a href="<?= $SubModulo["url_menu"] ?>"><?= $SubModulo["nombre"] ?></a>
        </li>
      <? endforeach; ?>
    </ul>
  </li>
  <? else: ?>
  <li class="<?= ( (after(get_host(), str_finish(url()->current(), '/')) == $Modulo["url_menu"] ) ? "active" : "") ?>" data-id="<?= $Modulo["idseccion"] ?>">
  <a href="<?= $Modulo["url_menu"] ?>">
  <i class="<?= $Modulo["icon"] ?>"></i><?= $Modulo["nombre"] ?></a>
  </li>
  <? endif; ?>

<? endforeach; ?>
</ul>
</nav>

@if(session("es_cliente") != true)
<div id="divCambioUSD" style="padding: 10px;"></div>
@endif

</div>
</aside>
<!-- END MENU SIDEBAR-->
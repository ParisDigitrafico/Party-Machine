<?php
namespace App\Helpers;

class SitioWeb
{
  static function ObtenerEnlaceCreador()
  {
    return '<a href="#" target="_blank">Powered by Myself.inc</span></b></a>';
  }

  static function CrearMenuCategoriaProducto($arrDato=array(), $bEsSub=false, $iSel = "")
  {
    $c ='';
    $ul_attrs = $bEsSub == true ? '' : 'class="navgoco categoria"';
    $c = "<ul $ul_attrs >";

    if($bEsSub == false)
    {
      $curl = "/" . app()->getLocale() . "/productos/";

      $li_attrs = (intval($iSel) > 0) ? 'class=""' : 'class="active"';

      $c.= "<li $li_attrs>";
      $c.= "<a href='".$curl."' data-location='".$curl."'>Todo</a>";
      $c.= "</li>";
    }

    foreach((array)$arrDato as $id => $attrs)
    {
      $sub = null;

      if(!empty($attrs['childs']))
      {
        /*array_sort_by_column($attrs['childs'], "nombre", SORT_ASC); */

        $sub = self::CrearMenuCategoriaProducto($attrs['childs'], true, $iSel);
      }

      $li_attrs = "";
      $a_attrs  = "";

      $li_attrs = (!empty($attrs['id']) && $attrs['id'] == $iSel) ? 'class="active"' : 'class=""';

      $attrs['nombre'] = trim($attrs["nombre".ucfirst(app()->getLocale())]) ?: $attrs["nombre"];

      $curl = "/" . app()->getLocale() . "/productos/buscar/categoria/" . slugify(limit_words($attrs['nombre'],4,3))."-".$attrs['id'] . "/";

      $c.= "<li $li_attrs>";
      $c.= "<a href='".$curl."' data-location='".$curl."' data-opt='".$attrs['id']."' $a_attrs>".$attrs['nombre']."</a>$sub";
      $c.= "</li>";
    }

    return $c . "</ul>";


    /*$c.='
    <ul class="navgoco">
    	<li><a href="/es">1. Menu</a>
    		<ul>
    			<li><a href="/es/productos">1.1 Submenu</a></li>
    			<li><a href="/es/productos">1.2 Submenu</a></li>
    			<li><a href="/es/productos">1.3 Submenu</a></li>
    		</ul>
    	</li>
    </ul>
    ';
    return $c;
    */
  }
}
<?php
namespace App\Helpers;

class Normalizador
{
  static function CategoriaProducto($Dato)
  {
    $cImageHost = get_const("FBAZAR_IMAGES_HOST");
    
    $Dato["urlPhoto"]   = !empty($Dato["urlPhoto"]) ? $cImageHost . $Dato["urlPhoto"] : "/static/generico/images/404.jpg";
    $Dato["urlPhotoTn"] = !empty($Dato["urlPhotoTn"]) ? $cImageHost . $Dato["urlPhotoTn"] : $Dato["urlPhoto"];

    return $Dato;
  }

  static function CategoriasProducto($arrDato=array())
  {
    $response = array();

    foreach($arrDato as $Dato)
    {
      $response[] = self::CategoriaProducto($Dato);
    }

    return $response;
  }
}
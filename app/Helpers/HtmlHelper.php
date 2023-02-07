<?php
namespace App\Helpers;

class HtmlHelper
{
  static function tagStatus($Dato)
  {
    $cht = "";

    if(is_array($Dato) && !empty($Dato))
    {
      $cText = "";

      if(isset($Dato["status"]))
      {
        $cText = $Dato["status"] == 1 ? "Activo":"Inactivo";
      }

      if(isset($Dato["deleted_at"]))
      {
        $cText = !empty($Dato["deleted_at"]) ? "Eliminado":$cText;
      }

      if(!empty($cText))
      {
        if($cText == "Activo")
        {
          $cht.='<span class="status--process">'.$cText.'</span>';
        }
        else
        {
          $cht.='<span class="status--denied">'.$cText.'</span>';
        }
      }
    }

    return $cht;
  }

  static function GetStatus($bStatus, $cText="")
  {
    $cht = "";

    if(boolval($bStatus))
    {
      $cText = $cText != "" ? $cText : "Activo";

      $cht.='<span class="status--process">'.$cText.'</span>';
    }
    else
    {
      $cText = $cText != "" ? $cText : "Inactivo";

      $cht.='<span class="status--denied">'.$cText.'</span>';
    }

    return $cht;
  }
}
<?php
if(!function_exists('get_version'))
{
  function get_version()
  {
    return config("app.version");
  }
}

if(!function_exists('get_host'))
{
  function get_host()
  {
    return ((@$_SERVER["HTTPS"] == "on") ? "https://" : "http://") . $_SERVER["HTTP_HOST"];
  }
}

if(!function_exists('get_current_url'))
{
  function get_current_url($trim_query_string = false)
  {
    $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https": "http";

    // Here append the common URL characters.
    $link.= "://";

    // Append the host(domain name, ip) to the URL.
    $link.= $_SERVER['HTTP_HOST'];

    // Append the requested resource location to the URL
    $link.= $_SERVER['REQUEST_URI'];

    if($trim_query_string)
    {
      $url = explode( '?', $link );

      $link = $url[0];
    }

    return $link;
  }
}

if(!function_exists('get_locale_val'))
{
  function get_locale_val($cExpr) # cExpr = en
  {
    $response = "/";

    $cExpr = trim(strtolower($cExpr));

    $arrLocale = array_keys(config("app.languages"));

    app()->setLocale(reset($arrLocale));

    if(in_array($cExpr, $arrLocale))
    {
      $response = $cExpr;

      app()->setLocale($response);
    }

    return $response;
  }
}

if(!function_exists('get_locale_url'))
{
  function get_locale_url($cUrl)
  {
    $response = "";

    $cUrl = trim($cUrl);

    if(!empty($cUrl))
    {
      if(stripos($cUrl, "http") !== false)
      {
        $response = $cUrl;
      }
      else
      {
        $cUrl = (substr($cUrl, 0, 1) === "/") ? $cUrl : "/" . $cUrl;

        $response = "/" . strtolower(app()->getLocale()) . $cUrl;
      }
    }

    return $response;
  }
}

if(!function_exists('get_const'))
{
  function get_const($key="")
  {
    $response = "";

    if(defined($key))
    {
      $response = constant($key);
    }
    elseif(!is_null(env($key)))
    {
      $response = env($key);
    }

    return $response;
  }
}

if(!function_exists('get_player'))
{
  function get_player($curl="")
  {
    $c = "";

    $curl = rtrim($curl, "/");

    if(str_icontains("youtube.com/watch?v=", $curl))
    {
      $c = 'https://www.youtube.com/embed/';
      $c.= after_last('=', $curl);
    }
    elseif(str_icontains("youtu.be", $curl))
    {
      $c = 'https://www.youtube.com/embed/';
      $c.= after_last("/", $curl);
    }
    elseif(str_icontains("vimeo.com", $curl))
    {
      $c = 'https://player.vimeo.com/video/';
      $c.= after_last("/", $curl);
    }
    elseif(str_icontains("matterport.com/show/?m=", $curl))
    {
      $c = 'https://my.matterport.com/show/?m=';
      $c.= after_last("=", $curl);
    }
    else
    {
      $c = $curl;
    }

    return $c;
  }
}

if(!function_exists('get_bool'))
{
  function get_bool($expr="")
  {
    $response = false;

    if(!is_bool($expr))
    {
      if(is_numeric($expr))
      {
        $response = abs($expr) > 0 ? true: false;
      }
      elseif(is_array($expr) or is_object($expr))
      {
        if(!empty($expr))
        {
          $response = true;
        }
      }
      else
      {
        $arrNegativeWords = array("false","no","negative");

        $expr = strtolower(trim(strval($expr)));

        if(!in_array($expr, $arrNegativeWords))
        {
          $response = true;
        }
      }
    }
    else
    {
      $response = $expr;
    }

    return $response;
  }
}

if(!function_exists('queryStringToArray'))
{
  function queryStringToArray($qry)
  {
    $result = array();
    //string must contain at least one = and cannot be in first position
    if(strpos($qry,'=')) {

     if(strpos($qry,'?')!==false) {
       $q = parse_url($qry);
       $qry = $q['query'];
      }
    }else {
            return false;
    }

    foreach (explode('&', $qry) as $couple) {
            list ($key, $val) = explode('=', $couple);
            $result[$key] = $val;
    }

    return empty($result) ? false : $result;
  }
}

if(!function_exists('get_random_string'))
{
  function get_random_string($length = 10)
  {
    return substr(str_shuffle(str_repeat($x='abcdefghkmnprstuvwyz23456789', ceil($length/strlen($x)) )),1,$length);
  }
}

if(!function_exists('get_asset'))
{
  function get_asset($curl="", $isRemoteAsset=false)
  {
    $response = "";

    $curl = trim($curl);

    if(!empty($curl))
    {
      if(stripos($curl, "http") !== false)
      {
        $response = $curl;
      }
      else
      {
        $curl = (substr($curl, 0, 1) === "/") ? $curl : "/" . $curl;

        if(get_bool($isRemoteAsset) && !empty(env("URL_ASSETS_BROKER")))
        {
          $response = env("URL_ASSETS_BROKER") . $curl;
        }
        elseif(!empty(env("URL_ASSETS")))
        {
          $response = env("URL_ASSETS") . $curl;
        }
        else
        {
          $response = asset($curl);
        }
      }
    }

    return $response . '?' . get_version();
  }
}

if(!function_exists('get_date'))
{
  function get_date($expr="")
  {
    $response = "";

    if(!in_array($expr, array("0000-00-00","0000-00-00 00:00:00")))
    {
      $response = $expr;
    }

    return $response;
  }
}

if(!function_exists('get_numeric'))
{
  function get_numeric($expr="")
  {
    $response = 0;

    $response = (double)filter_var($expr, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    return $response;
  }
}

if(!function_exists('get_vars'))
{
  function get_vars($arrVar=array())
  {
    $response = array();

    if(is_array($arrVar) && !empty($arrVar))
    {
      $imax = count($arrVar);

      $ictrl = 0;

      for($i=0; $i<$imax; ++$i)
      {
        ++$ictrl;

        if(!empty($arrVar[$i]))
        {
          if($ictrl%2==0)
          {
            $response[$key] = $arrVar[$i];
          }
          else
          {
            $key = $arrVar[$i];
            $response[$key] = "";
          }
        }
      }
    }

    return $response;
  }
}

if(!function_exists('strrevpos'))
{
  function strrevpos($instr="", $needle="")
  {
    $rev_pos = strpos (strrev($instr), strrev($needle));
    if ($rev_pos===false) return false;
    else return strlen($instr) - $rev_pos - strlen($needle);
  }
}

if(!function_exists('after'))
{
  function after($word="", $inthat="")
  {
    if (!is_bool(strpos($inthat, $word)))
      return substr($inthat, (strpos($inthat,$word)+strlen($word)));
  }
}

if(!function_exists('after_last'))
{
  function after_last($word="", $inthat="")
  {
    if (!is_bool(strrevpos($inthat, $word)))
    return substr($inthat, strrevpos($inthat, $word)+strlen($word));
  }
}

if(!function_exists('before'))
{
  function before($word="", $inthat="")
  {
    $response = $inthat;

    $arrAux = explode($word, $inthat);

    if(!empty($arrAux))
    {
      $response = $arrAux[0];
    }

    return $response;
  }
}

if(!function_exists('before_last'))
{
  function before_last($word="", $inthat="")
  {
    return substr($inthat, 0, strrevpos($inthat, $word));
  }
}

if(!function_exists('between'))
{
  function between($word="", $that="", $inthat="")
  {
    return before($that, after($word, $inthat));
  }
}

if(!function_exists('between_last'))
{
  function between_last($word="", $that="", $inthat="")
  {
    return after_last($word, before_last($that, $inthat));
  }
}

if(!function_exists('array_search_deep'))
{
  // Function to recursively search for a given value
  function array_search_deep($array, $key="", $value="")
  {
    $results = array();

    if(is_array($array))
    {
      if (isset($array[$key]) && $array[$key] == $value)
          $results[] = $array;

      foreach ($array as $subarray)
          $results = array_merge($results, array_search_deep($subarray, $key, $value));
    }

    return $results;
  }
}

if(!function_exists('array_search_first'))
{
  // Function to recursively search for a given value
  function array_search_first($array, $key="", $value="")
  {
    $response = array();

    if(is_array($array) && !empty($array))
    {
      $arrAux = array_search_deep($array, $key, $value);

      if(!empty($arrAux))
      {
        $response = $arrAux[0];
      }
    }

    return $response;
  }
}

if(!function_exists('clean_input'))
{
  function clean_input($input)
  {
    $input = strip_tags($input);
    $input = stripslashes($input);

    return $input;
  }
}

if(!function_exists('clean_sql_words'))
{
  function clean_sql_words($input)
  {
    $arrSqlReservedWord = array(
      'INSERT','UPDATE','DELETE','CREATE',
      'ALTER','INTO','VALUES','BEGIN',
      'COMMIT','DROP','EXECUTE','AUTHORIZATION',
      'CONNECTION','TABLE','TRUNCATE','IDENTIFIED',
    );

    return str_ireplace($arrSqlReservedWord, '', $input);
  }
}

if(!function_exists('sanitize'))
{
  function sanitize($input)
  {
    if(is_array($input))
    {
      foreach($input as $key => $val)
      {
        $output[$key] = sanitize($val);
      }
    }
    else
    {
      if(is_string($input))
      {
        $input = trim($input);
        $input = clean_input($input);
        $input = clean_sql_words($input);
      }

      $output = $input;
    }

    return $output;
  }
}

if(!function_exists('str_containss'))
{
  function str_containss($cSearch, $cString)
  {
    return strpos($cString, $cSearch) !== false;
  }
}

if(!function_exists('str_icontains'))
{
  function str_icontains($cSearch, $cString)
  {
    return strpos(strtolower($cString), strtolower($cSearch)) !== false;
  }
}

if(!function_exists('remove_extra_whitespace'))
{
  function remove_extra_whitespace($cString='')
  {
    $cString = trim($cString);
    $cString = preg_replace('!\s+!', ' ', $cString);

    return $cString;
  }
}

if(!function_exists('validate_selection'))
{
  function validate_selection($Value1, $Value2)
  {
    if(is_array($Value1))
    {
      if(in_array($Value2, $Value1))
        return "selected";
    }
    else
    {
    	if($Value1 == $Value2)
      	return "selected";
    }
  }
}

if(!function_exists('validate_checked'))
{
  function validate_checked($Value1, $Value2)
  {
    if(is_array($Value1))
    {
      if(in_array($Value2, $Value1))
        return "checked";
    }
    else
    {
    	if($Value1 == $Value2)
      	return "checked";
    }
  }
}

if(!function_exists('remove_accents'))
{
  function remove_accents($string)
  {
    if(!preg_match('/[\x80-\xff]/', $string))
      return $string;

    $chars = array(
      // Decompositions for Latin-1 Supplement
      chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
      chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
      chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
      chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
      chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
      chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
      chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
      chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
      chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
      chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
      chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
      chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
      chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
      chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
      chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
      chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
      chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
      chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
      chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
      chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
      chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
      chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
      chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
      chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
      chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
      chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
      chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
      chr(195).chr(191) => 'y',
      // Decompositions for Latin Extended-A
      chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
      chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
      chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
      chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
      chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
      chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
      chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
      chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
      chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
      chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
      chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
      chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
      chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
      chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
      chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
      chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
      chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
      chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
      chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
      chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
      chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
      chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
      chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
      chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
      chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
      chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
      chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
      chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
      chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
      chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
      chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
      chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
      chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
      chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
      chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
      chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
      chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
      chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
      chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
      chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
      chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
      chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
      chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
      chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
      chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
      chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
      chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
      chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
      chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
      chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
      chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
      chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
      chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
      chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
      chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
      chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
      chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
      chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
      chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
      chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
      chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
      chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
      chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
      chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
    );

    $string = strtr($string, $chars);

    return $string;
  }
}

if(!function_exists('slugify'))
{
  function slugify($cString)
  {
    $cString = preg_replace('/[^A-Za-z0-9]+/', ' ', remove_accents(strtolower($cString)));
    $cString = str_replace(' ', '-', remove_extra_whitespace($cString));

    return $cString;
  }
}

if(!function_exists('limit_words'))
{
  function limit_words($cString, $iLimit=12, $iMinLetters=3)
  {
    $arrWord = array();

    $cString = strip_tags($cString);
    $cString = remove_extra_whitespace($cString);

    $arrAux = explode(' ', $cString);

    foreach($arrAux as $cWord)
    {
      if(strlen($cWord) >= $iMinLetters)
      {
        $arrWord[] = $cWord;
      }
    }

    return implode(' ', array_slice($arrWord, 0, $iLimit));
  }
}

if(!function_exists('find_string_in_array'))
{
  function find_string_in_array($keyword, $arr)
  {
    foreach($arr as $index => $string)
    {
      if(str_containss($keyword, $string))
      {
        return true;
      }
    }

    return false;
  }
}

if(!function_exists('is_email'))
{
  function is_email($cEmail="")
  {
    $response = (false !== filter_var($cEmail, FILTER_VALIDATE_EMAIL));

    if($response)
    {
      $arrAux = explode('@', $cEmail);

      if(isset($arrAux[0]) && isset($arrAux[1]))
      {
        $response = checkdnsrr($arrAux[1], 'MX');
      }
    }

    return $response;
  }
}

if(!function_exists('get_minutes_diference'))
{
  function get_minutes_diference($dtInicio, $dtFinal)
  {
    $resultado = 0;

    $t1 = strtotime( $dtFinal );
    $t2 = strtotime( $dtInicio );

    $diff =  $t1 - $t2;

    $resultado = $diff / ( 60 );

    return $resultado;
  }
}

if(!function_exists('get_config_db'))
{
  function get_config_db($cClave)
  {
    $response = "";

    $objConfig = new \App\Models\AccConfiguracion();

    $config = $objConfig->where("clave",$cClave)->first();

    if($config)
    {
      $response = $config->valor;
    }

    return $response;
  }
}

if(!function_exists('mesEspaniol'))
{
  function mesEspaniol($MesNumero)
  {
    switch ($MesNumero)
    {
      case  1: $Mes = "Enero"; break;
      case  2: $Mes = "Febrero"; break;
      case  3: $Mes = "Marzo"; break;
      case  4: $Mes = "Abril"; break;
      case  5: $Mes = "Mayo"; break;
      case  6: $Mes = "Junio"; break;
      case  7: $Mes = "Julio"; break;
      case  8: $Mes = "Agosto"; break;
      case  9: $Mes = "Septiembre"; break;
      case 10: $Mes = "Octubre"; break;
      case 11: $Mes = "Noviembre"; break;
      case 12: $Mes = "Diciembre";  break;
    }
    return $Mes;
  }
}

if(!function_exists('mesEspaniolAbreviado'))
{
  function mesEspaniolAbreviado($MesNumero)
  {
    switch($MesNumero)
    {
      case  1: $Mes = "Ene"; break;
      case  2: $Mes = "Feb"; break;
      case  3: $Mes = "Mar"; break;
      case  4: $Mes = "Abr"; break;
      case  5: $Mes = "May"; break;
      case  6: $Mes = "Jun"; break;
      case  7: $Mes = "Jul"; break;
      case  8: $Mes = "Ago"; break;
      case  9: $Mes = "Sep"; break;
      case 10: $Mes = "Oct"; break;
      case 11: $Mes = "Nov"; break;
      case 12: $Mes = "Dic";  break;
    }
    return $Mes;
  }
}

if(!function_exists('fechaEspaniol'))
{
  function fechaEspaniol($FechaFormatear, $IncluirHora = false, $Abreviado = true)
  {
  	$response = 'S/F';

    $Separador = '/';

  	if(!empty($FechaFormatear) && !str_containss("0000-00-00",$FechaFormatear))
  	{
  		list($Fecha, $Tiempo) = explode(" ", $FechaFormatear);
  		list($Anio, $Mes, $Dia) = explode("-", $Fecha);
  		$Formato[0] = $Dia;

  		//Si es abreviado
  		if ( $Abreviado )
  			$Formato[1] = mesEspaniolAbreviado($Mes);
  		else
  			$Formato[1] = mesEspaniol($Mes);

  		$Formato[2] = $Anio;
  		$response = implode($Separador,$Formato);

    	if($IncluirHora)
    	{
    	  $response.= !empty($Tiempo) ? " " . horaEspaniol($Tiempo) : "";
    	}
  	}

  	return $response;
  }
}

if(!function_exists('horaEspaniol'))
{
  function horaEspaniol($expr)
  {
  	$response = '';

  	if(!empty($expr))
  	{
      if(1)
      {
        $response.= date("g:ia", strtotime($expr));
      }
      else
      {
    		list($hora, $minuto, $segundo) = explode(":", $expr);

    		$response.= "$hora:$minuto"."hrs";
      }
  	}

  	return $response;
  }
}

if(!function_exists('prettyPrintJson'))
{
  function PrettyPrintJson($json_data)
  {
    //Initialize variable for adding space
    $space = 0;

    $flag = false;

    //Using <pre> tag to format alignment and font
    echo "<pre>";

    //loop for iterating the full json data
    for($counter=0; $counter<strlen($json_data); $counter++)
    {
      //Checking ending second and third brackets
      if ( $json_data[$counter] == '}' || $json_data[$counter] == ']' )
      {
        $space--;
        echo "\n";
        echo str_repeat(' ', ($space*2));
      }

      //Checking for double quote(“) and comma (,)
      if ( $json_data[$counter] == '"' && ($json_data[$counter-1] == ',' || $json_data[$counter-2] == ',') )
      {
        echo "\n";
        echo str_repeat(' ', ($space*2));
      }

      if ( $json_data[$counter] == '"' && !$flag )
      {
        if ( $json_data[$counter-1] == ':' || $json_data[$counter-2] == ':' )

        //Add formatting for question and answer
        echo '<span style="color:blue;font-weight:bold">';
        else

        //Add formatting for answer options
        echo '<span style="color:red;">';
      }

      echo $json_data[$counter];
      //Checking conditions for adding closing span tag

      if ( $json_data[$counter] == '"' && $flag )
        echo '</span>';

      if ( $json_data[$counter] == '"' )
        $flag = !$flag;

      //Checking starting second and third brackets
      if ( $json_data[$counter] == '{' || $json_data[$counter] == '[' )
      {
        $space++;
        echo "\n";
        echo str_repeat(' ', ($space*2));
      }
    }

    echo "</pre>";
  }
}

if(!function_exists('number_truncate'))
{
  function number_truncate($number, $precision = 2)
  {
    // Zero causes issues, and no need to truncate
    if ( 0 == (int)$number ) {
        return $number;
    }
    // Are we negative?
    $negative = $number / abs($number);
    // Cast the number to a positive to solve rounding
    $number = abs($number);
    // Calculate precision number for dividing / multiplying
    $precision = pow(10, $precision);
    // Run the math, re-applying the negative value to ensure returns correctly negative / positive
    return floor( $number * $precision ) / $precision * $negative;
  }
}

if(!function_exists('objectToArray'))
{
  function objectToArray($object)
  {
    return @json_decode(json_encode($object), true);
  }
}

if(!function_exists('separateString'))
{
  function separateString($cCadena = "", $iCaracteres = 4, $cGlue = "-")
  {
  //primero saca la longitud de la cadena
    if(!empty($cCadena))
    {
      $arrAux = str_split($cCadena, $iCaracteres);

      $cCadena = implode($cGlue, $arrAux);
    }

    return $cCadena;
  }
}

if(!function_exists('get_menu_website'))
{
  function get_menu_website()
  {
    $response = [];

    $cFilename = storage_path('app/menu_website.json');

    if(is_file($cFilename))
    {
      $cAux = file_get_contents($cFilename, true);

      $response = json_decode($cAux, true);
    }
    else
    {
      $objPagina = new App\Models\WebPagina;

      $paginas = $objPagina->filterStatus(1)->where("es_visible",1)->where("idpadre", 0)->where("es_menu",1)->orderBy("orden","asc")->get();
      
      $arrAux = objectToArray($paginas);

      File::put($cFilename, json_encode($arrAux));

      $response = $arrAux;
    }

    return $response;
  }
}

if(!function_exists('get_banner_db'))
{
  function get_banner_db($cClave="")
  {
    $response = null;

    $objAux = new \App\Models\WebBanner();

    $item = $objAux->where("clave",$cClave)->first();

    if($item)
    {
      $response = $item;
    }

    return $response;
  }
}
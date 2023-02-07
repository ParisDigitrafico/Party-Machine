<?php
namespace App\Helpers;

class MensajeNotificacion
{
  static function EnviarMensaje($Mensaje=array(), $plantilla="basico", $es_auto=true)
  {
    $response = false;

    try
    {
      if(!empty(get_const("MAIL_HOST"))
          && !empty(get_const("MAIL_PORT"))
          && !empty(get_const("MAIL_USERNAME"))
          && !empty(get_const("MAIL_PASSWORD"))
          && 1==1)
      {
        $arrAux = array();

        $arrAux[] = get_const("MAIL_USERNAME");

        $cAux = $arrAux[array_rand($arrAux)];

        $transport = (new \Swift_SmtpTransport(get_const("MAIL_HOST"), get_const("MAIL_PORT"), get_const("MAIL_ENCRYPTION")))
                      ->setUsername($cAux)
                      ->setPassword(get_const("MAIL_PASSWORD"));

        $mailer  = new \Swift_Mailer($transport);
        $message = new \Swift_Message();

        $cAux     = get_const("MAIL_FROM_ADDRESS") ?: $cAux;
        $cFrom    = get_const("MAIL_FROM_NAME") ?: "noreply";
        $cReplyTo = get_const("MAIL_REPLY_ADDRESS") ?: $cAux;

        $message->setCharset('utf-8');

        $message->setFrom([$cAux => $cFrom]);
        $message->setReplyTo($cReplyTo);

        $Mensaje["subject"] = $Mensaje["subject"] ?? "";
        $Mensaje["header"]  = $Mensaje["header"] ?? "";
        $Mensaje["body"]    = $Mensaje["body"] ?? "";
        $Mensaje["footer"]  = $Mensaje["footer"] ?? "";

        $cAux = strip_tags($Mensaje["subject"]) . " - " . date("YmdHis");
        $cAux = remove_extra_whitespace($cAux);

        $message->setSubject($cAux);

        $body = view("generico.mail.".$plantilla)->with(array())->render();

        $body = str_replace("{%HEADER%}", $Mensaje["header"], $body);

        if($es_auto)
        {
          $Mensaje["body"].='<br />
          <div style="text-align:center; font-size:10pt; margin:0 auto; width:90%;"><i>Este mensaje fue generado por un sistema automatizado
          y no es necesario enviar una respuesta.</i></div>';
        }

        $body = str_replace("{%BODY%}", $Mensaje["body"], $body);

        if(empty($Mensaje["footer"]))
        {
          $cEmailSoporte = get_config_db("CORREO_CONTACTO_SOPORTE") ?: "soporte@gmail.com";

          $Mensaje["footer"] = '<div style="text-align: center; color:#FFFFFF;"><a href="'.$cEmailSoporte.'"
          style="color:#FFFFFF">'.$cEmailSoporte.'</a></div>';
        }

        $body = str_replace("{%FOOTER%}", $Mensaje["footer"], $body);

        $message->setBody($body, 'text/html');

        if(!empty($Mensaje["to"]))
        {
          $message->setTo($Mensaje["to"]);
        }

        if(!empty($Mensaje["cc"]))
        {
          $message->setCc($Mensaje["cc"]);
        }

        if(!empty($Mensaje["cco"]))
        {
          $message->setBcc($Mensaje["cco"]);
        }

        $iAux = 0;

        while($response != true && $iAux < 6)
        {
          $response = get_bool($mailer->send($message));

          $iAux = $iAux + 1;

          if($response != true) sleep(6);
        }
      }
    }
    catch(\exception $ex)
    {
      $response = false;
    }

    return $response;
  }

  static function EnviarCorreoInstruccionRestablecer($toAddress="", $cKey="")
  {
    $Mensaje = array();

    $Mensaje["subject"] = "Kantunchi - Restablecer Acceso a Cuenta - " . date("YmdHis");

    $Mensaje["to"] = trim(strtolower($toAddress));

    $Mensaje["body"] = '<p style="text-align: justify">Para poder restablecer su contrase&ntilde;a es necesario ir al siguiente enlace:</p>';

    $cUrl = get_host().'/sistema/login/reset/?k='.$cKey;

    $Mensaje["body"].= '<a href="'.$cUrl.'" target="_blank">'.$cUrl.'</a><br /><br />';

    $Mensaje["body"].= '<p style="text-align: justify">Si no puede acceder con un click, copie y pege el enlace en la barra de direcciones de su navegador web</p>';

    $Mensaje["body"].= '<p style="text-align: justify">Si usted no ha solicitado restablecer su contrase&ntilde;a, por favor ignore este mensaje, ';

    $Mensaje["body"].= 'este enlace caducar&aacute; autom&aacute;ticamente dentro de 24 horas.</p>';

    $Mensaje["cco"] = array(
    /*"macosta@digitrafico.mx",*/
    );

    self::EnviarMensaje($Mensaje);
  }

  static function EnviarCorreoContraseniaRestablecida($toAddress="", $cLogin="", $cPassword="", $cTipoUsuario="")
  {
    $Mensaje = array();

    $Mensaje["subject"] = "Kantunchi - Acceso de Usuario Restablecido por Sistema Automatizado - " . date("YmdHis");

    $Mensaje["body"] = "";

    $Mensaje["body"].= "Los datos de acceso han sido correctamente modificados.<br /><br />";
    $Mensaje["body"].= "Usuario : <b>$cLogin</b><br />";
    $Mensaje["body"].= (!empty($cPassword)) ? "Password : <b>$cPassword</b><br /><br />" : "";
    /*$Mensaje.= (!empty($cTipoUsuario)) ? "Categor&iacute;a : {$cTipoUsuario}<br /><br />" : "";*/

    $Mensaje["body"].= self::MensajeNoContactar();

    $Mensaje["to"] = trim(strtolower($toAddress));

    $Mensaje["cco"] = array(
    /*"macosta@digitrafico.mx",*/
    );

    self::EnviarMensaje($Mensaje);
  }

}
<?php
namespace App\Helpers;

class MensajeNotificacion
{
  static function EnviarMensaje($Mensaje=array(), $plantilla="basico", $es_auto=true)
  {
    $response = false;

    try
    {
      $cMailHost = get_config_db("MAIL_HOST") ?: get_const("MAIL_HOST");
      $cMailPort = get_config_db("MAIL_PORT") ?: get_const("MAIL_PORT");
      $cMailEncr = get_config_db("MAIL_ENCRYPTION") ?: get_const("MAIL_ENCRYPTION");
      $cMailUser = get_config_db("MAIL_USERNAME") ?: get_const("MAIL_USERNAME");
      $cMailPass = get_config_db("MAIL_PASSWORD") ?: get_const("MAIL_PASSWORD");

      $cMailFromAddress    = get_config_db("MAIL_FROM_ADDRESS") ?: get_const("MAIL_FROM_ADDRESS");
      $cMailFromName       = get_config_db("MAIL_FROM_NAME") ?: get_const("MAIL_FROM_NAME");
      $cMailReplyAddress   = get_config_db("MAIL_REPLY_ADDRESS") ?: get_const("MAIL_REPLY_ADDRESS");
      $cMailSupportAddress = get_config_db("MAIL_SUPPORT_ADDRESS") ?: get_const("MAIL_SUPPORT_ADDRESS");

      if(!empty($cMailHost)
          && !empty($cMailPort)
          && !empty($cMailUser)
          && !empty($cMailPass)
          && 1==1)
      {
        $arrAux = array();

        $arrAux[] = $cMailUser;

        $cAux = $arrAux[array_rand($arrAux)];

        $transport = (new \Swift_SmtpTransport($cMailHost, $cMailPort, $cMailEncr))
                      ->setUsername($cAux)
                      ->setPassword($cMailPass);

        $mailer  = new \Swift_Mailer($transport);
        $message = new \Swift_Message();

        $cAux     = $cMailFromAddress ?: $cAux;
        $cFrom    = $cMailFromName ?: "noreply";
        $cReplyTo = $cMailReplyAddress ?: $cAux;

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

        if($es_auto === true)
        {
          $Mensaje["body"].='<br />
          <div style="text-align:center; font-size:10pt; margin:0 auto; width:90%;"><i>Este mensaje fue generado por un sistema automatizado
          y no es necesario enviar una respuesta.</i></div>';
        }

        $body = str_replace("{%BODY%}", $Mensaje["body"], $body);

        if(empty($Mensaje["footer"]))
        {
          $Mensaje["footer"] = '<div style="text-align: center; color:#FFFFFF;"><a href="'.$cMailSupportAddress.'"
          style="color:#FFFFFF">'.$cMailSupportAddress.'</a></div>';
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

 static function EnviarCorreoInstruccionRestablecer($toAddress="", $cKey="", $cTipoUsuario="sistema")
  {
    $Mensaje = array();

    $Mensaje["subject"] = "Party Machine - Restablecer Acceso a Cuenta";

    $Mensaje["to"] = trim(strtolower($toAddress));

    $Mensaje["body"] = '<p style="text-align: justify">Para poder restablecer su contrase&ntilde;a es necesario ir al siguiente enlace:</p>';

    $cUrl = get_host() .'/'. $cTipoUsuario .'/login/reset/'. $cKey .'/';

    $Mensaje["body"].= '<p style="text-align: center">
    <a href="'.$cUrl.'" target="_blank">'.$cUrl.'</a></p>';

    $Mensaje["body"].= '<p style="text-align: justify">Si no puede acceder con un click,
    copie y pege el enlace en la barra de direcciones de su navegador web</p>';

    $Mensaje["body"].= '<p style="text-align: justify">Si usted no ha solicitado restablecer su contrase&ntilde;a,
    por favor ignore este mensaje, ';

    $Mensaje["body"].= 'este enlace caducar&aacute; autom&aacute;ticamente dentro de 24 horas.</p>';

    $Mensaje["cco"] = array(
    /*"macosta@digitrafico.mx",*/
    );

    self::EnviarMensaje($Mensaje);
  }

  static function EnviarCorreoContraseniaRestablecida($toAddress="", $cLogin="", $cPassword="")
  {
    $Mensaje = array();

    $Mensaje["subject"] = "Party Machine - Acceso de Usuario Restablecido por Sistema Automatizado";

    $Mensaje["body"] = "";

    $Mensaje["body"].= "Los datos de acceso han sido correctamente modificados.<br /><br />";
    $Mensaje["body"].= "Usuario : <b>$cLogin</b><br />";
    $Mensaje["body"].= (!empty($cPassword)) ? "Password : <b>$cPassword</b><br /><br />" : "";

    $Mensaje["to"] = trim(strtolower($toAddress));

    $Mensaje["cco"] = array(
    /*"macosta@digitrafico.mx",*/
    );

    self::EnviarMensaje($Mensaje);
  }

}
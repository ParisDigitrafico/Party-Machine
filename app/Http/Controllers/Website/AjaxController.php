<?php
namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use App\Helpers\Securimage;
use App\Helpers\MensajeNotificacion;

class AjaxController extends MController
{
  public function __construct()
  {
    parent::__construct();
  }

  public function call(Request $request, $control, $action)
  {
    $response = array();

    if($request->ajax() || get_const("APP_ENV") == "local")
    {
      if($request->isMethod('post'))
      {
        switch ($control)
        {
          case "mensajes":
          {
            switch($action)
            {
              case "EnviarMensajeContacto":
              {
                $securimage = new Securimage();

                $securimage->namespace = request()->get("captcha_id");

                if(!empty(request()->get("captcha_code"))
                      && $securimage->check(request()->get("captcha_code")) === true
                      && !empty(request()->get("Dato"))
                      && 1==1)
                {
                  $Dato = request()->get("Dato");

                  $Mensaje = array();

                  $sitioweb = str_ireplace(array("https://","http://"),"", url("/"));

                  $Mensaje["subject"] = "Un cliente solicita información desde el portal: <br>". $sitioweb;

                  $Mensaje["header"] = "<h2><center>".$Mensaje["subject"]."</center></h2>";

                  $Mensaje["body"] = "";
                  $Mensaje["body"].= !empty($cAux = $Dato["nombre"]) ? "<p><b>".ucfirst("nombre").":</b>&nbsp;".$cAux."</p>":"";
                  $Mensaje["body"].= !empty($cAux = $Dato["apellidos"]) ? "<p><b>".ucfirst("apellidos").":</b>&nbsp;".$cAux."</p>":"";
                  $Mensaje["body"].= !empty($cAux = $Dato["correo"]) ? "<p><b>".ucfirst("correo").":</b>&nbsp;".$cAux."</p>":"";
                  $Mensaje["body"].= !empty($cAux = $Dato["email"]) ? "<p><b>".ucfirst("email").":</b>&nbsp;".$cAux."</p>":"";
                  $Mensaje["body"].= !empty($cAux = $Dato["telefono"]) ? "<p><b>".ucfirst("telefono").":</b>&nbsp;".$cAux."</p>":"";
                  $Mensaje["body"].= !empty($cAux = $Dato["mensaje"]) ? "<p><b>".ucfirst("mensaje").":</b>&nbsp;".$cAux."</p>":"";
                  $Mensaje["body"].= !empty($cAux = $Dato["servicio"]) ? "<p><b>".ucfirst("servicio").":</b>&nbsp;".$cAux."</p>":"";
                  $Mensaje["body"].= !empty($cAux = $Dato["destino"]) ? "<p><b>".ucfirst("destino").":</b>&nbsp;".$cAux."</p>":"";
                  $Mensaje["body"].= !empty($cAux = $Dato["sucursal"]) ? "<p><b>".ucfirst("sucursal").":</b>&nbsp;".$cAux."</p>":"";

                  $cAux = get_config_db("MAIL_TO_CLIENT");

                  if(is_email($cAux))
                  {
                    $Mensaje["to"] = $cAux;
                  }

                  $cAux = get_config_db("MAIL_TO_SUPPORT");

                  if(is_email($cAux))
                  {
                    $Mensaje["cco"] = $cAux;
                  }

                  /*$objMensaje = new \App\Mensaje;

                  $objMensaje->nombre   = $Dato["nombre"];
                  $objMensaje->correo   = $Dato["correo"];
                  $objMensaje->telefono = $Dato["telefono"];
                  $objMensaje->texto    = $Dato["mensaje"];

                  $bRes = $objMensaje->save();*/

                  /*if($bRes)
                  {*/
                    $bRes = MensajeNotificacion::EnviarMensaje($Mensaje);

                    if($bRes)
                    {
                      $response["success"] = true;
                      $response["message"] = "Mensaje enviado correctamente.";
                    }
                    else
                    {
                      $response["message"] = "Existe un error con el servidor de correos.";
                    }
                  /*}
                  else
                  {
                    $response["message"] = "Existe un error con el guardado del mensaje.";
                  }*/
                }
                else
                {
                  $response["message"] = "El código de seguridad es incorrecto.";
                }

                break;
              }

              case "test":
              {
                $response["success"] = false;
                $response["message"] = "Mensaje enviado correctamente.";

                break;
              }
            }

            break;
          }
        }
      }
    }

    return response()->json($response);
  }

  public function captcha(Request $request)
  {
    $img = new Securimage();

    // You can customize the image by making changes below, some examples are included - remove the "//" to uncomment

    //$img->ttf_file        = './Quiff.ttf';
    //$img->captcha_type    = Securimage::SI_CAPTCHA_MATHEMATIC; // show a simple math problem instead of text
    //$img->case_sensitive  = true;                              // true to use case sensitve codes - not recommended
    //$img->image_height    = 90;                                // height in pixels of the image
    //$img->image_width     = $img->image_height * M_E;          // a good formula for image size based on the height
    //$img->perturbation    = .75;                               // 1.0 = high distortion, higher numbers = more distortion
    //$img->image_bg_color  = new Securimage_Color("#0099CC");   // image background color
    //$img->text_color      = new Securimage_Color("#EAEAEA");   // captcha text color
    //$img->num_lines       = 8;                                 // how many lines to draw over the image
    //$img->line_color      = new Securimage_Color("#0000CC");   // color of lines over the image
    //$img->image_type      = SI_IMAGE_JPEG;                     // render as a jpeg image
    //$img->signature_color = new Securimage_Color(rand(0, 64),
    //                                             rand(64, 128),
    //                                             rand(128, 255));  // random signature color
    $img->charset = 'abcdefghkmnprstuvwyz3456789';

    // see securimage.php for more options that can be set

    // set namespace if supplied to script via HTTP GET
    $cAux = $request->get('namespace');

    if (!empty($cAux)) $img->setNamespace($cAux);

    $img->perturbation = 0.25;

    $img->show();
  }

}
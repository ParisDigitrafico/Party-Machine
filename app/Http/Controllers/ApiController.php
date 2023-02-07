<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use App\Helpers\ResizeImage;
use App\Helpers\MensajeNotificacion;

use App\Models\AccLog;

use App\Models\GenArchivo;

class ApiController extends Controller
{
  private $data;
  private $bearer;

  public function __construct(Request $request)
  {
    /*$this->middleware('guest'); */
    $this->bearer = $request->bearerToken();

    $this->data = array();

    $this->data[] = array("id"=>"1","name"=>"mike");
    $this->data[] = array("id"=>"2","name"=>"robert");
    $this->data[] = array("id"=>"3","name"=>"mary");
  }

  public function index()
  {
    $response = array();

    $response["data"] = $this->data;

    return response()->json($response);
  }

  public function detail(Request $request, $id)
  {
    $response = array();

    foreach($this->data as $row)
    {
      if($row["id"] == $id)
      {
        $response["data"] = $row;
      }
    }

    return response()->json($response);
  }

  public function create(Request $request)
  {
    $response = array();

    $response["success"] = true;
    $response["message"] = "created";
    $response["bearer"]  = $this->bearer;

    return response()->json($response, 201);
  }

  public function uploader(Request $request)
  {
    $response = array();

    $iCode = 200;

    /*if(!empty(session("idusuario")))
    {*/
    if(isset($_FILES['file']))
    {
      $objArchivo     = new GenArchivo();
      $objResizeImage = new ResizeImage();

      $cNombre = before_last(".", $_FILES['file']['name']);

      $ext = after_last(".", $_FILES['file']['name']);
      $ext = strtolower($ext);

      if(!empty($cNombre) && !empty($ext))
      {
        sleep(1); # detenemos 1 seg el cargado para evitar que se dupliquen nombres.

        $cDir = "/uploads/temp";

        $temp_dir = public_path() . $cDir;

        if(!is_dir($temp_dir)) mkdir($temp_dir, 0755, true);

        $datetime    = date("YmdHis");
        $cName       = $datetime;
        $cNameRez    = $datetime . '.' . "rz";
        $cNameNew    = $cName . '.'. $ext;
        $cNameNewRez = $cNameRez . '.'. $ext;
        $cNameTn     = $cName . '.tn.'. $ext;

        $cUrlNew = $cDir . "/" . $cNameNew;
        $cUrlRez = $cDir . "/" . $cNameNewRez;
        $cUrlTn  = $cDir . "/" . $cNameTn;

        $path_new = public_path() . $cUrlNew;
        $path_rez = public_path() . $cUrlRez;
        $path_tn  = public_path() . $cUrlTn;

        $file = $request->file('file');

        $file->move($temp_dir, $cNameNew);

        if(is_file($path_new))
        {
          $Dato = array();

          $Dato["updated_at"] = now();
          $Dato["deleted_at"] = $Dato["updated_at"];
          $Dato["nombre"]     = $cNombre;
          $Dato["url"]        = $cUrlNew;

          if(in_array($ext, array("jpg","jpeg","png","gif","webp","tiff","tif","bmp")))
          {
            $Dato["es_foto"] = 1;
          }

          if(in_array($ext, array("jpg","jpeg","png","gif")))
          {
            if($objResizeImage->setImage($path_new))
            {
              $objResizeImage->resizeTo(1920, 1920);
              $objResizeImage->saveImage($path_rez, 86);

              $objResizeImage->resizeTo(600, 600);
              $objResizeImage->saveImage($path_tn, 86);

              if(filesize($path_rez) < filesize($path_new))
              {
                $Dato["url"] = $cUrlRez;
                @unlink($path_new);
              }
              else
              {
                @unlink($path_rez);
              }

              $Dato["url_tn"] = $cUrlTn;
            }
          }

          if($objArchivo->insert($Dato))
          {
            $response["success"] = true;

            $archivo = $objArchivo->latest()->first();

            $Dato["id"] = $archivo->id;

            if($request->has("keep"))
            {
              $archivo->deleted_at = null;

              $archivo->save();
            }

            $iCode = 201;

            $response["data"] = $Dato;
          }
        }
      }
    }
    /*} */

    GenArchivo::LimpiarEliminados();

    return response()->json($response, $iCode);
  }

  public function getPaises(Request $request)
  {
    $response = array();

    $objPais = new Pais();

    usleep(9999);

    $objPais->Consultar("","","","",""," status=1 AND length(char(deleted_at)) = 0 ","nombre","ASC");

    $response["success"] = true;
    $response["data"]    = $objPais->ObtenerTodo();

    return response()->json($response);
  }

  public function getEstados(Request $request, $idpais)
  {
    $response = array();

    $objEstado = new Estado();

    usleep(9999);

    $objEstado->Consultar("","","","",""," idpais='".$idpais."' AND status=1 AND length(char(deleted_at)) = 0 ","nombre","ASC");

    $response["success"] = true;
    $response["data"]    = $objEstado->ObtenerTodo();

    return response()->json($response);
  }

  public function get_last_updated(Request $request, $table)
  {
    $response = array();

    if(intval(session("usuario_id")) > 0)
    {
      $cSql = "
      SELECT
      tbl.updated_at
      FROM ".$table." tbl
      ORDER BY tbl.updated_at DESC
      LIMIT 1
      ";

      $response["success"] = true;

      $arrDato = objectToArray(DB::Select($cSql));

      if($arrDato)
      {
        $response["data"] = $arrDato[0]["updated_at"];
      }
      else
      {
        $response["data"] = now();
      }
    }

    return response()->json($response);
  }

  public function word(Request $request)
  {
    $template_file_name = public_path() . '/static/sistema/files/words/aguas_magicas.docx';

    $fileName = "results_" . date("YmdHis") . ".docx";

    $folder = public_path() . "/media/results";

    $full_path = $folder . '/' . $fileName;

    try
    {
      if(!is_dir($folder))
      {
        mkdir($folder);
      }

      //Copy the Template file to the Result Directory
      copy($template_file_name, $full_path);

      // add calss Zip Archive
      $zip_val = new \ZipArchive;

      //Docx file is nothing but a zip file. Open this Zip File
      if($zip_val->open($full_path) == true)
      {
        // In the Open XML Wordprocessing format content is stored.
        // In the document.xml file located in the word directory.

        $key_file_name = 'word/document.xml';
        $message = $zip_val->getFromName($key_file_name);

        $timestamp = date('d-M-Y H:i:s');

        // this data Replace the placeholders with actual values
        #$message = preg_replace('/\bRESERVA_CLIENTE\b/', "Manuel Enrique Acosta", $message);
        $message = str_replace('RESERVA_CLIENTE', "ingo@onlinecode", $message);
        $message = str_replace('RESERVA_FECHA_COMPRA', $timestamp, $message);
        $message = str_replace('RESERVA_HOTEL', "BARCELO", $message);
        $message = str_replace('RESERVA_HABITACION', "42", $message);
        $message = str_replace('RESERVA_FECHA_VISITA', $timestamp, $message);
        $message = str_replace('RESERVA_HORA_LLEGADA', $timestamp, $message);
        $message = str_replace('RESERVA_NUMERO_VISITANTES', 3, $message);
        $message = str_replace('RESERVA_OFERTA', 3, $message);
        $message = str_replace('RESERVA_TOTAL', 3, $message);
        $message = str_replace('RESERVA_OBSERVACIONES', "demo demo demo", $message);

        $message = str_replace('EMPLEADO_NOMBRE', "Manuel Enrique Acosta", $message);
        $message = str_replace('EMPLEADO_TELEFONO', "999 999 9999", $message);
        $message = str_replace('EMPLEADO_CORREO', "ingo@onlinecode", $message);


        /*$message = str_replace("client_email_address",  "ingo@onlinecode",  $message);
        $message = str_replace("date_today",            $timestamp,             $message);
        $message = str_replace("client_website",        "www.onlinecode",   $message);
        $message = str_replace("client_mobile_number",  "+1999999999",          $message);*/

        /*exit(var_dump($message)); */

        //Replace the content with the new content created above.
        $zip_val->addFromString($key_file_name, $message);
        $zip_val->close();

        if(is_file($full_path))
        {
          header('Content-Description: File Transfer');
          header("Content-type: application/octet-stream");
          header("Content-disposition: attachment; filename= ".basename($full_path)."");
          readfile($full_path);
        }
      }
    }
    catch(exception $ex)
    {
      $error_message =  "Error creating the Word Document";
      var_dump($ex);
    }
  }

  public function emailsimple(Request $request)
  {
    $response = array();

    $data = $request->get("data");

    $data = json_decode($data, true);

    if(!empty($data["subject"]) && !empty($data["body"]) && !empty($data["email"]))
    {
      $Mensaje = array();

      $Mensaje["subject"] = $data["subject"];

      $Mensaje["body"] = $data["body"];

      if(is_array($data["email"]))
      {
        $Mensaje["to"] = $data["email"];
      }
      else
      {
        $Mensaje["to"] = array(
          trim(strtolower($data["email"])),
        );
      }

      MensajeNotificacion::EnviarMensaje($Mensaje);

      $response["success"] = true;
    }
    else
    {
      $response["message"] = "Algun de los siguientes campos esta vacio, subject, body, email.";
    }

    return response()->json($response);
  }

  public function webhookconekta(Request $request)
  {
    $data = array();

    $body = @file_get_contents('php://input');

    $response = json_decode($body, true);

    $conekta_order = $response['data']['object'];

    if($conekta_order)
    {
      $ckt_order_id  = $conekta_order["id"];

      $charge        = $conekta_order['charges']['data'][0];
      $paid_at       = date("Y-m-d H:i:s", $charge["paid_at"]);
      $PaymentMethod = $charge['payment_method']['type']; # OXXO

      if($response['type'] == 'charge.paid')
      {

        /*$msg = "Tu pago ha sido comprobado.";
        mail("fulanito@conekta.com","Pago confirmado",$msg);*/
      }
    }
  }
}

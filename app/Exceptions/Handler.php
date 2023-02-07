<?php
namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Str;

use App\Helpers\MensajeNotificacion;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
      //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
      'password',
      'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
      if(config("app.env") == "production" && !$this->isHttpException($exception))
      {
        $err = array();

        $err['message'] = $exception->getMessage();
        $err['code']    = $exception->getCode();
        $err['file']    = $exception->getFile();
        $err['line']    = $exception->getLine();
        $err['trace']   = $exception->getTraceAsString();

        $cAux = view("generico.errors.exception",["err"=>$err])->render();

        if(!Str::contains($cAux, ["SumarVisita","cURL error 7","filemtime():","Exceptions->handleError(2, 'unlink"]))
        {
          $message = array();

          $message["subject"] = "error laravel tramitem";
          $message["body"]    = $cAux;

          if(!empty($cAux = get_const("MAIL_ERROR_ADDRESS")) && is_email($cAux))
          {
            $message["to"] = array($cAux);
            MensajeNotificacion::EnviarMensaje($message, "sin_formato");
          }
        }
      }

      parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
      $response = array();

      if(config("app.env") == "production")
      {
        $response["url"] = $request->get("url") ?: "/";

        return response()->view("generico.errors.404", $response);
      }
      else
      {
        return parent::render($request, $exception);
      }
    }

}

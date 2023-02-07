var componente_formulario = (function($, pub)
{
  var o = pub.deepClone(pub);

  function _ActualizarCaptcha($form)
  {
    $captcha = $form.find('img.captcha_img');

    cAux = $captcha.attr("data-src") + "&v=" + Math.random();

    $captcha.attr('src', cAux);
  }

  function _ValidarForm($form)
  {
    var response = false;
    var cError = "";

    $form.find(":input").each(function(index, element)
    {
      $current = $(element);

      if($current.hasClass("required"))
      {
        if($.trim($current.val()).length == 0)
        {
          cMessage = $current.data("msg-required") || "";

          if(cMessage.length > 0)
          {
            cError+= '<div style="text-align: center">'+$current.data("msg-required")+'</div>';
            return true;
          }
        }
      }

      if($current.hasClass("email"))
      {
        if($.trim($current.val()).length > 0 && !util.isValidEmailAddress($current.val()))
        {
          cMessage = $current.data("msg-email") || "";

          if(cMessage.length > 0)
          {
            cError+= '<div style="text-align: center">'+cMessage+'</div>';
            return true;
          }
        }
      }
    });

    if(cError.length > 0)
    {
      swal({
        type: 'error',
        html: cError,
      });

      response = false;
    }
    else
    {
      response = true;
    }

    return response;
  }

  function _ProcesarForm($form)
  {
    var $btnSubmit = $form.find(".btnSubmit");
    var $textArea  = $form.find("textarea").first();

    var cInitMsj   = $textArea.val();
    var cTxtSub    = $form.data("submit") || "Enviar";
    var cTxtSubCar = $form.data("submit-loading") || "Cargando...";
    var cMethod    = $form.data("method") || "get";
    var _token     = $form.data("token") || "";

    var cIdCotizar = $form.attr("data-cotizar-id") || "";

    if(cIdCotizar.length > 0)
    {
      var caux = $form.attr("data-cotizar-texto") || "";

      caux = cInitMsj + caux;

      $textArea.val(caux);
    }

    var qs = $form.serialize();

    var oe = {
      "_token":_token,
    };

    qs+= "&" + $.param(oe);

    $btnSubmit.prop("disabled", true).val(cTxtSubCar).text(cTxtSubCar);

    $textArea.val(cInitMsj);

    $.ajax({
      type: cMethod,
      url: $form.data("ajax"),
      data: qs,
      dataType: "json",
    }).done(function(response){
      if(response.status)
      {
        swal({
          type: "success",
          html: response.message,
        }).then(function(){
          $form.trigger("reset");

          if(cIdCotizar.length > 0)
          {
            $form.attr("data-cotizar-id","");
            setStorage("cotizador-id-"+cIdCotizar,"true",(24*60));
            location.href=$form.attr("data-cotizar-url");
            return false;
          }
        });
      }
      else
      {
        swal({
          type: "error",
          html: response.message,
        });

        $form.find("input.captcha_code").val("");
      }

      $btnSubmit.prop("disabled", false).val(cTxtSub).text(cTxtSub);
      _ActualizarCaptcha($form);
    }).fail( function( jqXHR, textStatus){
      $btnSubmit.prop("disabled", false).val(cTxtSub).text(cTxtSub);
      _ActualizarCaptcha($form);
    });
  }

  o.EnviarForm = function($form)
  {
    if(_ValidarForm($form))
    {
      _ProcesarForm($form);
    }
  }

  o.ValidarForm = function($form)
  {
    return _ValidarForm($form);
  }

return o;
})(jQuery, componente || {});
var componente_formulario = (function($, pub){
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
          cError+= '<div style="text-align: center">'+$current.data("msg-required")+'</div>';
          return true;
        }
      }

      if($current.hasClass("email"))
      {
        if($.trim($current.val()).length > 0 && !isValidEmailAddress($current.val()))
        {
          cError+= '<div style="text-align: center">'+$current.data("msg-email")+'</div>';
          return true;
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

    var cUrlAjax            = $form.data("action") || "";
    var cMethod             = $form.data("method") || "get";
    var cTextoBotonNormal   = $form.data("submit") || "Submit";
    var cTextoBotonCargando = $form.data("submit-loading") || "Loading...";

    if(cUrlAjax.length > 0)
    {
      var qs = $form.serialize();

      if(cMethod != "get")
      {
        var oe = {
          "_token":window.laravel.token,
        };

        qs = qs + '&' + $.param(oe);
      }

      $btnSubmit.val(cTextoBotonCargando).text(cTextoBotonCargando).prop("disabled", true);

      var ajx = $.ajax({
        type: cMethod,
        url: cUrlAjax,
        data: qs,
        dataType: "json",
      });

      ajx.done(function(response, textStatus, jqXHR){
        if(jqXHR.status == 419)
        {
          swal({
            type: "error",
            html: "Error 419 - No Access",
          });
        }
        else
        {
          if(response.success)
          {
            swal({
              type: "success",
              html: ($form.data("msg-success") || response.message || "Form sent successfully."),
            }).then(function(){
              $form.trigger("reset");
            });
          }
          else
          {
            swal({
              type: "error",
              html: ($form.data("msg-error") || response.message || "There was an error trying to send the form."),
            });
          }
        }
      });

      ajx.fail(function(jqXHR, textStatus){
        if(textStatus != "success")
          alert("Error: " + jqXHR.statusText);
      });

      ajx.always(function(){
        $btnSubmit.val(cTextoBotonNormal).text(cTextoBotonNormal).prop("disabled", false);
        _ActualizarCaptcha($form);
      });

    }
    else
    {
      alert("There wasn't url action.");
    }
  }

  o.enviarForm = function($form)
  {
    if(_ValidarForm($form))
    {
      _ProcesarForm($form);
    }
  }

  o.init = function(cClass)
  {
    cClass = cClass || "frmDynamic";

    cSelector = "." + cClass;

    if(document.querySelectorAll(cSelector).length)
    {
      $(cSelector).each(function(index, element){
        $form = $(element);

        $form.on("click", ".btnSubmit", function(evt){
          evt.preventDefault();

          $form = $(this).closest("form");

          o.enviarForm($form);
        });

      });
    }



  }

return o;
})(jQuery, componente || {});
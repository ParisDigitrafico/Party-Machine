var componente_venta = (function($, pub)
{
  var o = pub.deepClone(pub);

  var _privateVariable = "Hello World";

  function _privateMethod()
  {
    // ...
  }

  o.publicVariable = "Foobar";

  o.procesarcarrito = function(cClass)
  {
    cClass = cClass || "area_resumen_carrito";

    $selector = $("." + cClass);

    if($selector.length > 0)
    {
      $selector.on("click", ".btnVerificarFinalizar", function(evt){
        evt.preventDefault();

        $btn = $(this);

        alert("Verificando existencias en sucursales y finaliza proceso.");

        /*iAux = $btn.data("idcliente") || "";

        if(iAux.toString().length > 0)
        {
          window.location.href = $btn.data("url");
        }
        else
        {
          alert("Para continuar es necesario iniciar una sesión. Redirigiendo...");
        }*/
      });
    }

  };

  o.init = function(cClass)
  {
    o.procesarcarrito();
  }

  return o;
})(jQuery, componente || {});
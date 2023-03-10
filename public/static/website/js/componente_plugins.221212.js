var componente_plugins = (function($, pub)
{
  var o = pub.deepClone(pub);

  var _privateVariable = "Hello World";

  function _privateMethod()
  {
    // ...
  }

  function _ConsultarPedidoByCKey(ckeypedido, callback)
  {
    callback = callback || "";

    xhr = $.get("/api/v1/pedidos/"+ ckeypedido, {}, null, 'json');

    xhr.done(function(response){
      if(typeof callback === 'function')
      {
        callback(response);
      }
    });
  }

  function _AgregarProductoCantidadAPedidoByCkey(ckeypedido, producto_id, cantidad, callback)
  {
    callback = callback || "";

    xhr = $.post("/api/v1/pedidos/"+ ckeypedido +"/"+ producto_id +"/"+ cantidad +"/", {}, null, 'json');

    xhr.done(function(response){
      if(typeof callback === 'function')
      {
        callback(response);
      }
      /*$btnCart    = $(".btnCart");
      $span_total = $(".btnCart").find("span.total").first();

      $btnCart.attr("href","/carito/"+ckeypedido);

      $span_total.text(response.data.cantidad);*/
    });
  }

  function _ActualizarProductoCantidadAPedidoByCkey(ckeypedido, codigoProducto, cantidad, callback)
  {
    var xhr = $.ajax({
      type: "put",
      url: "/api/v1/pedidos/"+ ckeypedido +"/"+ codigoProducto +"/"+ cantidad +"/",
      dataType: "json",
    });

    xhr.done(function(response){
      if(typeof callback === 'function')
      {
        callback(response);
      }
    });
  }

  function _QuitarProductoAPedidoByCKey(ckeypedido, producto_id, callback)
  {
    var xhr = $.ajax({
      type: "delete",
      url: "/api/v1/pedidos/"+ ckeypedido +"/"+ producto_id +"/",
      dataType: "json",
    });

    xhr.done(function(response){
      if(typeof callback === 'function')
      {
        callback(response);
      }
    });
  }

  function _AgregarProductoACarrito(producto_id, cantidad, callback)
  {
    var ckeypedido = util.getStorage("ckeypedido");

    callback = callback || "";

    if(ckeypedido)
    {
      _AgregarProductoCantidadAPedidoByCkey(ckeypedido, producto_id, cantidad, callback);
    }
    else
    {
      xhr = $.post('/api/v1/pedidos/', {}, null, 'json');

      xhr.done(function(response){
        util.setStorage("ckeypedido", response.data.ckey, (24*60));

        _AgregarProductoCantidadAPedidoByCkey(response.data.ckey, producto_id, cantidad, callback);
      });
    }
  }

  o.plugin_navgoco = function(cClass)
  {
    cClass = cClass || "navgoco";

    if(document.querySelectorAll("." + cClass).length)
    {
      $elem = $(".navgoco");

      $elem.find("li.active").addClass("open").parents('li').addClass('open');

      $elem.navgoco({
        openClass: 'open',
        save: false,
      });
    }
  };

  o.plugin_slick = function(cClass)
  {
    cClass = cClass || "plugin_slick";

    $("." + cClass).each(function(index, element) {
      $plugin_slick = $(this);

      $plugin = $plugin_slick.slick({
        slidesToShow: parseInt(($plugin_slick.data("slides-show") || 3)),
        slidesToScroll: 1,
        autoplay: $plugin_slick.data("autoplay") == 'true',
        autoplaySpeed: 2000,
      });

    });
  };

  o.plugin_product_card = function(cClass)
  {
    cClass = cClass || "card_producto";

    $(document).on("click", "." + cClass + " .btnAddCarrito", function(evt){
      evt.preventDefault();

      $btnAddCarrito = $(this);

      bLoading = $btnAddCarrito.data("loading") == "1";

      if(!bLoading)
      {
        cOriginal = $btnAddCarrito.html();

        $btnAddCarrito.html('<span>...</span>').data("loading","1");

        iProducto   = $btnAddCarrito.data("producto-id") || "";
        iCantidad = $btnAddCarrito.data("cantidad") || 1;

        if(iProducto.toString().length > 0)
        {
          _AgregarProductoACarrito(iProducto, iCantidad, function(response){
            if(response.success)
            {
              $btnCart    = $(".btnCart").first();
              $span_total = $btnCart.find("span.total").first();

              $btnCart.attr("href","/carrito/"+response.data.ckey);

              $span_total.text(response.data.cantidad);

              $btnAddCarrito.html(cOriginal).data("loading","");
              /*alert("producto agregado correctamente.");*/
            }
            else
            {
              $btnAddCarrito.html(cOriginal).data("loading","");
              alert("Sucedio un error inesperado, favor de consultar con soporte.");
            }
          });
        }

      }

    });
  };

  o.plugin_cart_button = function(cClass)
  {
    cClass = cClass || "btnCart";

    $btnCart   = $("." + cClass).first();
    $span_text = $btnCart.find("span.text").first();

    var ckeypedido = util.getStorage("ckeypedido") || "";

    if(ckeypedido.length == 0)
    {
      ajx = $.post('/api/v1/pedidos/', {}, null, 'json');

      ajx.done(function(response, textStatus, xhr){
        if(xhr.status == 201)
        {
          ckeypedido = response.data.ckey;

          util.setStorage("ckeypedido", ckeypedido, (24*60));

          $btnCart.attr("href","/carrito/"+ckeypedido);
        }
      });
    }
    else
    {
      _ConsultarPedidoByCKey(ckeypedido, function(response){
        $btnCart.attr("href","/carrito/"+ckeypedido);
        $span_text.text(response.data.cantidad);
      });
    }

  };

  o.plugin_area_carrito = function(cClass)
  {
    cClass = cClass || "area_carrito";

    $area_carrito = $("." + cClass).first();

    // $table = $area_carrito.find("table");
    // $tbody = $table.find("tbody");

    // $tbody.find("tr").each(function(index, element){
    //   $tr = $(this);

      $items_card = $area_carrito.find(".items-card");
      
      $items_card.find(".div-item").each(function(index, element){
        $tr = $(this);

      $txtCantidad = $tr.find(".txtCantidad").first();

      $txtCantidad.on("keyup", util.debouncer(function(evt){
        $txt = $(this);

        ckey = $txt.data("ckey");
        codigoProducto = $txt.data("codigo-producto");;
        iCantidad = parseInt($txt.val()) || 0;

        if(iCantidad > 0)
        {
          _ActualizarProductoCantidadAPedidoByCkey(ckey,codigoProducto,iCantidad,function(response){
            if(response.success)
            {
              location.href="/carrito/"+ckey+"/";
            }
          });
        }

      }, 999));

      $btnRemove = $tr.find(".btnRemove");

      $btnRemove.on("click", function(evt){
        evt.preventDefault();

        $btnRemove = $(this);

        ckey = $btnRemove.data("ckey");
        producto_id = $btnRemove.data("producto-id");

        aux = confirm("??Realmente deseas quitar este producto del carrito?");

        if(aux)
        {
          _QuitarProductoAPedidoByCKey(ckey,producto_id,function(response){
            if(response.success)
            {
              location.href="/carrito/"+ckey;
            }
          });
        }
      });

    });

    $(document).on("click", "." + cClass + " .btnSiguiente", function(evt){
      evt.preventDefault();

      $btn = $(this);

      iAux = $btn.data("cliente_id") || "";

      if(iAux.toString().length > 0)
      {
        location.href = $btn.data("url");
      }
      else
      {
        location.href = "/cliente/";
      }
    });
  };

  o.init = function()
  {
    o.plugin_navgoco();
    o.plugin_product_card();
    o.plugin_area_carrito();
    o.plugin_cart_button();
  }

  return o;
})(jQuery, componente || {});
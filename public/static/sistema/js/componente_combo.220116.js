var componente_combo = (function($, pub)
{
  var o = pub.deepClone(pub);

  o.fillCombo = function(options)
  {
    var s = this;

    var defaults = {
      table : '',
      objAndOr: {},
      onBefore: null,
      onDone : null,
      onError : null,
    }

    var options = $.extend({}, defaults, options);

    if(typeof options.onBefore == 'function')
    {
      options.onBefore();
    }

    var xhr = $.get("/jsontable/"+ options.table +"/", options.objAndOr, null, 'json');

    xhr.done(function(response){
      if(typeof options.onDone == 'function')
      {
        options.onDone(response);
      }
    });
  }

return o;
})(jQuery, componente || {});
var componente = (function($)
{
  var o = {};

  o.deepClone = function(obj)
  {
    var _out = new obj.constructor;

    var getType = function (n)
    {
      return Object.prototype.toString.call(n).slice(8, -1);
    }

    for (var _key in obj)
    {
      if (obj.hasOwnProperty(_key))
      {
        _out[_key] = getType(obj[_key]) === 'Object' || getType(obj[_key]) === 'Array' ? o.deepClone(obj[_key]) : obj[_key];
      }
    }

    return _out;
  }

return o;
})(jQuery);
var util = (function($)
{
  var o = {};

  //Trim
  o.trim = function(str)
  {
    return str.replace(/^\s+|\s+$/g, "");
  }

  o.ltrim = function(str)
  {
    return str.replace(/^\s+/, "");
  }

  o.rtrim = function(str)
  {
    return str.replace(/\s+$/, "");
  }

  //Cookies
  o.setCookie = function(cookieName, cookieValue, nDays)
  {
    //componente.setCookie('CookieName','Example',1);
    var today = new Date();
    var expire = new Date();
    if (nDays == null || nDays == 0) nDays = 1;
    expire.setTime(today.getTime() + 3600000 * 24 * nDays);
    document.cookie = cookieName + "=" + escape(cookieValue)
                    + ";expires=" + expire.toGMTString();
  }

  o.getCookie = function(cookieName)
  {
    var theCookie = " " + document.cookie;
    var ind = theCookie.indexOf(" " + cookieName + "=");
    if (ind == -1) ind = theCookie.indexOf(";" + cookieName + "=");
    if (ind == -1 || cookieName == "") return "";
    var ind1 = theCookie.indexOf(";", ind + 1);
    if (ind1 == -1) ind1 = theCookie.length;
    return unescape(theCookie.substring(ind + cookieName.length + 2, ind1));
  },

  o.delCookie = function(cookieName)
  {
    document.cookie = encodeURIComponent(cookieName) + "=null; expires=" + new Date(0).toUTCString();
  }

  //Storage
  o.setStorage = function(key, value, iMinutes)
  {
    var value   = value || "";
    var iExpire = Math.abs(iMinutes) || 60;

    iExpire = iExpire*60*1000;

    var now = Date.now(); //millisecs since epoch time, lets deal only with integer

    var schedule = now + iExpire; // Variable expira en x minutos establecidas

    try
    {
      localStorage.setItem(key, value);
      localStorage.setItem(key + '_expiresIn', schedule);
    }
    catch(ex)
    {
      console.log('setStorage: Error setting key ['+ key + '] in localStorage: ' + JSON.stringify(ex) );
      return false;
    }

    return true;
  }

  o.getStorage = function(key)
  {
    var key = key || "";

    var now = Date.now();

    var expiresIn = localStorage.getItem(key+'_expiresIn');

    if(expiresIn===undefined || expiresIn===null) { expiresIn = 0;}

    if(expiresIn < now)
    {
      o.delStorage(key);
      
      return "";
    }
    else
    {
      try
      {
        return localStorage.getItem(key);
      }
      catch(ex)
      {
        console.log('getStorage: Error reading key ['+ key + '] from localStorage: ' + JSON.stringify(ex) );
        return "";
      }
    }
  }

  o.delStorage = function(key)
  {
    try
    {
      localStorage.removeItem(key);
      localStorage.removeItem(key + '_expiresIn');
    }
    catch(ex)
    {
      console.log('removeStorage: Error removing key ['+ key + '] from localStorage: ' + JSON.stringify(ex) );
      return false;
    }

    return true;
  }

  //Restriccion de caracteres
  o.isEmpty = function(data)
  {
    if(typeof(data) == 'number' || typeof(data) == 'boolean')
    {
      return false;
    }
    if(typeof(data) == 'undefined' || data === null)
    {
      return true;
    }
    if(typeof(data.length) != 'undefined')
    {
      return data.length == 0;
    }
    var count = 0;
    for(var i in data)
    {
      if(data.hasOwnProperty(i))
      {
        count ++;
      }
    }
    return count == 0;
  }

  o.PermitType = function(ctype, className)
  {
    ctype = ctype.toLowerCase();

    $('body').on('keypress keyup blur', '.'+className, function(evt){
      switch(ctype){
        case "int":
          evt = (evt) ? evt : window.event;
          var key = (evt.which) ? evt.which : evt.keyCode;
          return (key <= 13 || (key >= 48 && key <= 57));
        break;
        case "dec":
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode == 8 || charCode == 37) {
            return true;
          } else if (charCode == 46 && $(this).val().indexOf('.') != -1) {
            return false;
          } else if (charCode > 31 && charCode != 46 && (charCode < 48 || charCode > 57)) {
            return false;
          }
          return true;
        break;
      }
    });
  }

  o.setMayus = function(className)
  {
    document.querySelector('.'+className).setAttribute('style', 'text-transform: uppercase');

    $('body').on('keypress keyup blur', '.'+className, function(e){
      var elem = $(this);
      caux = elem.val();
      elem.val(caux.toUpperCase());
    });
  }

  o.setMinus = function(className)
  {
    document.querySelector('.'+className).setAttribute('style', 'text-transform: lowercase');
    $('body').on('change', '.'+className, function(e){
      var elem = $(this);
      caux = elem.val();
      elem.val(caux.toLowerCase());
    });
  }

  o.noPaste = function(className)
  {
    $('.'+className).each(function(){
      $(this).bind('paste', function (e){
        return false;
      });
    });
  }

  o.noSpace = function(className)
  {
    $('body').on('keypress keyup blur', '.'+className, function(evt){
      /*evt = (evt) ? evt : window.event;
      var key = (evt.which) ? evt.which : evt.keyCode;
      if (key == 32){
        return false;
      }
      return true;*/
      var self = $(this);

      ctexto = self.val();

      self.val(ctexto.replace(/\s/g,"%20"));
    });
  }

  //Split & Join
  o.split = function(separator, str)
  {
    var res;

    if(str.indexOf(separator) !== -1)
    {
      res = str.split(separator);
    } else {
      res = '';
    }

    return res;
  }

  o.join = function(glue, array)
  {
    var res;

    if(array.length !== 0) {
      res = array.join(glue);
    } else {
      res = "";
    }

    return res;
  }

  o.splitParams = function(q)
  {
    var vars = [], hash;
    if(q != undefined){
        q = q.split('&');
        for(var i = 0; i < q.length; i++){
            hash = q[i].split('=');
            vars.push(hash[1]);
            vars[hash[0]] = hash[1];
        }
    } else{
      vars = false;
    }
    return vars;
  }

  o.isValidEmailAddress = function(emailAddress)
  {
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
  }

  o.round = function(sVal, nDec)
  {
    nDec = nDec || 2;

    var n = parseFloat(sVal);
    var s = "0.00";

    if(!isNaN(n))
    {
      n = Math.round(n * Math.pow(10, nDec)) / Math.pow(10, nDec);
      s = String(n);
      s += (s.indexOf(".") == -1? ".": "") + String(Math.pow(10, nDec)).substr(1);
      s = s.substr(0, s.indexOf(".") + nDec + 1);
    }

    return s;
  }

  o.truncate = function(sVal, nDec)
  {
    nDec = nDec || 2;

    var numS = sVal.toString(),
        decPos = numS.indexOf('.'),
        substrLength = decPos == -1 ? numS.length : 1 + decPos + nDec,
        trimmedResult = numS.substr(0, substrLength),
        finalResult = isNaN(trimmedResult) ? 0 : trimmedResult;

    return parseFloat(finalResult);
  }

  o.debouncer = function(func, timeout)
  {
    var timeoutID , timeout = timeout || 200;
    return function () {
      var scope = this , args = arguments;
      clearTimeout( timeoutID );
      timeoutID = setTimeout( function () {
          func.apply( scope , Array.prototype.slice.call( args ) );
      } , timeout );
    }
  }

  o.getPercentageOfValue = function(dValue, iPercent)
  {
    var response = 0;

    response = (iPercent / 100) * dValue;

    return response;
  }

  o.displayDecimals = function(sVal, iNumDec)
  {
    var response = "";

    iNumDec = iNumDec || 2;

    response = (Math.round(sVal * 100) / 100).toFixed(iNumDec);

    return response;
  }

  o.removeAccents = function(strAccents)
  {
    var strAccents = strAccents.split('');
    var strAccentsOut = new Array();
    var strAccentsLen = strAccents.length;
    var accents =    "ÀÁÂÃÄÅàáâãäåÒÓÔÕÕÖØòóôõöøÈÉÊËèéêëðÇçÐÌÍÎÏìíîïÙÚÛÜùúûüÑñŠšŸÿýŽž";
    var accentsOut = "AAAAAAaaaaaaOOOOOOOooooooEEEEeeeeeCcDIIIIiiiiUUUUuuuuNnSsYyyZz";

    for(var y = 0; y < strAccentsLen; ++y)
    {
      if(accents.indexOf(strAccents[y]) != -1)
        strAccentsOut[y] = accentsOut.substr(accents.indexOf(strAccents[y]), 1);
      else
        strAccentsOut[y] = strAccents[y];
    }

    strAccentsOut = strAccentsOut.join('');

    return strAccentsOut;
  }

  o.getNumeric = function(expr, decimals)
  {
    expr = expr || 0;
    decimals = decimals || 2;

    expr = String(expr);

    expr = expr.replaceAll(',', '');
    expr = parseFloat(expr);

    response = o.truncate(expr, decimals);

    return response;
  }


return o;
})(jQuery);
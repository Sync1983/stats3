window.ammo = window.ammo || {};
(function(ammo) {
  var 
    load_files = {},
    is_abs_path = new RegExp('^(https?|\\/)');

  ammo.file_versions = ammo.file_versions || {};
  ammo.i18n_dictonary = ammo.i18n_dictonary || {};
  ammo.utils = {};

  ammo.i18n = function(message, translate)
  {
    return ammo.i18n_dictonary[message] ? ammo.i18n_dictonary[message] : (translate ? translate : message);
  };
 
  ammo.isAbsPath = function(url)
  {
    return url.match(is_abs_path);  
  }
  
  ammo.dump = function(obj)
  {
    if(window.JSON && JSON.stringify)
      alert(JSON.stringify(obj));
    else
      alert(obj);
  };

  ammo.one = function(obj, name, func) {
    obj[name] = function() {
      obj[name] = null;
      obj[name] = func();
      return obj[name].apply(null, arguments);
    };
  };

  ammo.addVersion = function(src) {
    return ammo.file_versions[src] || src;
  };
   
  var __load = function(files, callback)
  {
    if(typeof(files) === 'string')
      files = [files];
    for(var i in files)
    {
      src = files[i];
      if(!src || load_files[src])
        continue;
      load_files[src] = true;
      callback(ammo.absPath(ammo.file_versions[src] || src));
    }
  };

  ammo.lazyMethod = function(options) {
    options = options || {};
    ammo.one(options.obj, options.name, function() {
      ammo.ajaxLoader().show();

      __load(options.css_files, function(url) {
        jQuery('<style>').attr('type', 'text/css').attr('media', 'all').html('@import url(\'' + url + '\');').appendTo('head');
      });

      __load(options.js_files, function(url) {
        jQuery.ajax({url: url, cache: true, type: 'GET', async: false, dataType: 'script', error: function() {throw 'Failed load js!';}});
      });

      ammo.ajaxLoader().hide();
      if(options.callback)
        return options.callback();
      if(options.get_object)
        return (function(obj) { return function() {return obj}; })(options.get_object());
      return options.obj[options.name];
    });
  }

  ammo.one(ammo, 'ajaxLoader', function() {
    var ajax_loader = jQuery('<div class="-ammo-ajax-loader" style="display:none;">'+ammo.i18n('-ammo-ajax-loader')+'...</div>').appendTo('body');
    return function() {
      return ajax_loader; 
    };
  });

  ammo.modalWindow = {
    message: function(message) { 
      alert(message); 
    },

    confirm: function(message, callback_yes, callback_no) {
      if(confirm(message))
        if(typeof(callback_yes) == 'function')
          callback_yes();
      else
        if(typeof(callback_no) == 'function')
          callback_no();
    }
  };
  
  ammo.baseErrorAjaxSend = function() {
    ammo.modalWindow.message(ammo.i18n('-ammo-error-load'));
  };

  ammo.absPath = function(url)
  {
    return url;
  };

  ammo.ajax = function(options) 
  {
    options.type = options.type || 'POST';
    options.data = options.data || {};
    options.data.is_ajax = 1;
    options.dataType = options.dataType || 'json'; 
    options.url = ammo.absPath(options.url || '');
    options.error = options.error || function() { ammo.baseErrorAjaxSend();};
    if(typeof(options.async) == 'undefined')
      options.async = false;
    (function(complete) {
      options.complete = function() 
      {
        ammo.ajaxLoader().hide(); 
        if(typeof(complete) == 'function')
          complete();
      }
    })(options.complete);
    (function(beforeSend) {
      options.beforeSend = function() 
      {
        ammo.ajaxLoader().show(); 
        if(typeof(beforeSend) == 'function')
          beforeSend();
      }
    })(options.beforeSend);
    return options;
  };

  ammo.returnOrCallMethod = function(manager)
  {
    return function(method) { 
      if(method)
      {
        if(manager[method])
          return manager[method]();
        return false; 
      }
      return manager; 
    };            
  };

  ammo.parseQuery = function(query)
  {
    var vars = {};
    $.map(query.split('&'), function(str) {
      vars[decodeURIComponent(str.split('=')[0])] = decodeURIComponent(str.split('=')[1]);
    });
    return vars;
  };

})(window.ammo);

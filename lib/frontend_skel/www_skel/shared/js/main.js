window.myjs = window.myjs || {};
(function() {

  var private_vars = {
      rexp: {
        hash: new RegExp('^#'),
        strip_slash: new RegExp('^\\/+'),
        is_abs_path: new RegExp('^(https?|\\/)')
      },
      history_inited: false
    };

  myjs.server_vars = myjs.server_vars || {};
  myjs.vars = myjs.vars || {};

  myjs.ajax = function(options)
  {
    (function(success) {
      options.success= function(data)
      {
        if(data.error)
          return ammo.modalWindow.message(data.error);
        if(data.message)
          ammo.modalWindow.message(data.message);
        if(typeof(success) == 'function')
          success.apply(null, arguments);
        if(data.js_callback)
          eval(data.js_callback.join(';'));
      }
    })(options.success);
    if(options.cache == false)
    {
      if(!options.url.match(private_vars.rexp.is_abs_path))
      {
        options.cache = true; 
        var offset_path = '/' + myjs.server_vars.offset_path;
        if(options.url.substr(0, offset_path.length) == offset_path)
          options.url = options.url.substr(offset_path.length, options.url.length);
        options.url =  '_t/' + (new Date()).getTime() + '/' + options.url;
      }
    }
    return ammo.ajax(options);
  };


  myjs.buildHttpQuery = function(params)
  {
    var 
      query = new Array(),
      i;
    for(i in params)
      query.push(encodeURIComponent(i) + '=' + encodeURIComponent(params[i]));
    return query.join('&');
  };
  
  myjs.clickAjaxLink = function(link)
  {
    myjs.ajaxGoo($(link).attr('href'));
    return false;
  };
  myjs.a = myjs.clickAjaxLink;

  myjs.content = function()
  {
    if(!myjs.vars.content)
      myjs.vars.content = $('#js-ajax-content');
    return myjs.vars.content;
  };

  var showSlotsResponse = function(data)
  {
    if(data.slots)
    {
      myjs.content().html(data.slots.content);
      if(data.slots.js_ready)
        eval(data.slots.js_ready);
    }
    else
      myjs.content().html(data.html);
  };

  myjs.ajaxGoo = function(url, params)
  {
    if(params)
    {
      var items = new Array();
      for(var name in params)
        items.push(encodeURIComponent(name)+'='+encodeURIComponent(params[name]));
      url += '?' + items.join('&');
    };

    if($.browser.msie)
    {
      if(url.substr(0, myjs.server_vars.base_path.length) == myjs.server_vars.base_path)
        url = url.substr(myjs.server_vars.base_path.length);
    };

    if(private_vars.history_inited)
      myjs.history().push('goo:'+url);
    else
      myjs.goo(url);

    return false;
  };
  
  myjs.goo = function(url)
  {
    $.ajax(myjs.ajax({
      async: true,
      url: url,
      type: 'GET',
      cache: false,
      success: showSlotsResponse,
      is_new_page: true
    }));
  }
  
  myjs.rawAbsPath = function(url)
  {
    return window.myjs.server_vars.base_path + url.replace(private_vars.rexp.strip_slash, '');
  };

  // change ammo
  ammo.absPath = function(url)
  {
    return url.match(private_vars.rexp.is_abs_path) ? url : myjs.rawAbsPath(url);
  };

  myjs.ajaxFormLoadContent = function(form)
  {
    form = $(form);
    $(form).ajaxForm(myjs.ajax({
      url: '',
      beforeSubmit: function(data)
      {
        var 
          i,
          params = {};
        for(i in data)
          params[data[i].name] = data[i].value;
        delete params['is_ajax'];
        myjs.ajaxGoo(form.attr('action'), params);
        return false;
      }
    }));
    return form;
  };

  (function() {
    if(!$.historyInit)
      return;

    var manager = {};

    manager.init = function()
    {
      manager.init = function() {};
      $.historyInit(function(hash) {
        if(!hash.length)
          myjs.goo(window.location.pathname + (window.location.search.length ? "?" + window.location.search : ""));
        else
        {
          hash = hash.replace('|', '?');
          if(hash.slice(0, 4) === 'goo:')
            myjs.goo(hash.slice(4));
        }
      });
    };

    manager.push = function(hash)
    {
      $.historyLoad(hash.replace('?', '|'));
    };

    myjs.history = function() { return manager;};
    jQuery(document).ready(function() {
      manager.init();
      private_vars.history_inited = true;
    });
  })(myjs);
})();


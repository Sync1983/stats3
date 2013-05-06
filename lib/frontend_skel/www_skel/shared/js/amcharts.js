(function(myjs) {
  var
    manager = {},
    absPath = ammo.absPath,
    map = {
      line: {base_path: absPath('_/0/shared/lib/amline/'), swf: 'amline.swf'},
      column: {base_path: absPath('_/0/shared/lib/amcolumn/'), swf: 'amcolumn.swf'},
      pie: {base_path: absPath('_/0/shared/lib/ampie/'), swf: 'ampie.swf'}
    },
    flash_params = {'wmode': 'transparent'};

  manager.renderCharts = function(charts_options)
  {
    var 
      id, 
      options;
    if(charts_options)
    {
      for(id in charts_options)
      {
        options = charts_options[id];
        if(options)
        {
          options['id'] = id;
          manager.render(options); 
        }
      }
    }
  };

  manager.render = function(options) {
    var
      vars = {
        'path': map[options.type].base_path,
        'chart_data': options.data,
        'preloader_color': '#999999',
        'chart_id': options.id
      };

    if(options.settings)
      vars.chart_settings = options.settings;
    else
      vars.settings_file = absPath(options.settings_file);

    var xml_data = vars.chart_data;
    if($.browser.msie)
    {
      if(vars.chart_settings)
        vars.chart_settings = encodeURIComponent(vars.chart_settings);
      if(vars.chart_data)
        vars.chart_data = encodeURIComponent(vars.chart_data);
    }

    swfobject.embedSWF(
      map[options.type].base_path + map[options.type].swf,
      options.id,
      options.width || "648",
      options.height || "350",
      "8",
      false,
      vars,
      flash_params
    );
  };
  
  myjs.amcharts = ammo.returnOrCallMethod(manager);
})(myjs);

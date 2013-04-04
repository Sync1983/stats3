var MainUtils = {
  _modal_overlay: false,

  getModalOverlay: function()
  {
    if(!MainUtils._modal_overlay)
      MainUtils._modal_overlay = new MainUtils._modal_overlay_class();
    return MainUtils._modal_overlay;
  },

  html: function(id)
  {
    return document.getElementById(id).innerHTML;
  }
};

MainUtils._modal_overlay_class = function() {
  this.root = jQuery('#modal_overlay');  
  this.content = this.root.find('div.content');
};

MainUtils._modal_overlay_class.prototype = {
  show: function()
  {
    this.root.css('display', 'block');
    return this;
  },

  clean: function()
  {
    this.content.html('');
    return this;
  },

  close: function()
  {
    this.root.css('display', 'none');
    return this;
  },
  
  html: function(html)
  {
    this.content.html(html); 
    return this;
  },

  getContent: function()
  {
    return this.content;  
  }
}

MainUtils.getFormFields = function(form, data)
{
  if(!data)
    data = {};
  form.find('input,select,textarea').each(function() { 
    var el = jQuery(this);
    if(this.tagName.toLowerCase() == 'input' && el.attr('type') == 'checkbox')
      data[el.attr('name')] = 0 + el.attr('checked');
    else
      data[el.attr('name')] = el.val();
  });
  return data;
}

MainUtils.openPopup = function(url, windowWidth, windowHeight, name, view_location)
{
  var centerWidth = (window.screen.width - windowWidth) / 2;
  var centerHeight = (window.screen.height - windowHeight) / 2;

  if(view_location)
    view_location = 1;
  else
    view_location = 0;

  newWindow = window.open(url, name,
                          'resizable=1' +
                          ',scrollbars=0' +
                          ',toolbar=0' + 
                          ',location=' + view_location +  
                          ',width=' + windowWidth +
                          ',height=' + windowHeight +
                          ',left=' + centerWidth +
                          ',top=' + centerHeight);
  if(newWindow)
    newWindow.focus();
  return newWindow;
}

MainUtils.renderTemplate = function(html, data)
{
  var i;
  for(i in data)
  {
    var search = '{' + i + '}';
    do
    {
      html = html.replace(search, data[i]);
    } while(html.indexOf(search) > -1);
  }
  return html;
}

Merman = 
{  
  openConsole: function(cmd, params)
  {
    params.cmd = cmd;
    window_id = '_blank';
    var sparams = new Array();
    for(var i in params)
      sparams.push(i+'='+params[i]);
    var win = MainUtils.openPopup('cmd.php?' + sparams.join('&'), window.screen.width - 100, window.screen.height - 200, 'console_' + window_id, 1);
  },

  init: function() 
  {
    
  },

  displayProjects: function(projects)
  {
    var html = MainUtils.html('tpl_project_item');
    var table = jQuery('#projects_table').find('tbody');
    for(var i in projects)
    {
      var project = projects[i];
      table.append(MainUtils.renderTemplate(html, project));
      var tr = table.find('#p_'  + project.name);
      tr.find('a#wc').attr('href', 'repos/' + project.name);
      tr.find('a#repository').attr('href', 'hg/' + project.name);
      if(project.is_protected)
        tr.addClass('protected'); 
    }
  },

  showCloneForm: function(name)
  {
    var html = MainUtils.renderTemplate(MainUtils.html('tpl_clone_form'), {name: name});
    MainUtils.getModalOverlay().html(html).show();
    MainUtils.getModalOverlay().getContent().find('form').submit(function() {Merman.sendCloneForm(this);});
  },

  sendCloneForm: function(form)
  {
    var params = MainUtils.getFormFields(jQuery(form));
    Merman.openConsole('clone', params);
    MainUtils.getModalOverlay().clean().close();
  },

  showMoveForm: function(name)
  {
    var html = MainUtils.renderTemplate(MainUtils.html('tpl_move_form'), {name: name});
    MainUtils.getModalOverlay().html(html).show();
    MainUtils.getModalOverlay().getContent().find('form').submit(function() {Merman.sendMoveForm(this);});
  },

  sendMoveForm: function(form)
  {
    var params = MainUtils.getFormFields(jQuery(form));
    Merman.openConsole('move', params);
    MainUtils.getModalOverlay().clean().close();
  },

  protect: function(name)
  {
    if(!confirm('Protect the repository "' +name + '" of changes?'))
      return;
    Merman.openConsole('protect', {source: name});
  },

  deleteProject: function(name)
  {
    if('delete' != prompt('Enter "delete" for delete project ' + name, ''))
      return;
    Merman.openConsole('delete', {source: name});
  },

  update: function(name)
  {
    if(!confirm('Update wc for repository "' +name + '"?'))
      return;
    Merman.openConsole('up', {source: name});
  }
};

// actions
Merman.a = {
  clone: function(name)
  {
    Merman.showCloneForm(name);
  },

  move: function(name)
  {
    Merman.showMoveForm(name);
  },

  protect: function(name)
  {
    Merman.protect(name);
  },

  deleteProject: function(name)
  {
    Merman.deleteProject(name);
  }, 

  up: function(name)
  {
    Merman.update(name);
  }
}

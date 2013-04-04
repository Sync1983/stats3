(function(myjs) {
  var manager = {};

  manager.baseEdit = function(opt)
  {
    $.ajax(myjs.ajax({
      url: opt.controller + '/ajax_edit_form',
      data: opt.req,
      success: function(data)
      {
        var dialog = $(data.html).dialog({
          close: function() {$(this).remove();},
          modal: false,
          width: opt.width || 800
        });
        var form = dialog.find('#item_form').eq(0);
        form.ajaxForm(myjs.ajax({
          url: opt.controller + '/ajax_save',
          data: opt.req,
          beforeSubmit: function(formData) {if(opt.before_submit) opt.before_submit(formData, form);},
          success: function(data)
          {
            if(data.slots && data.slots.rows && data.id)
            {
              var row =  $('#-js-item-' + data.id);
              if(row.length)
                row.after(data.slots.rows).remove();
              else if($('#-js-tbody-admin-items').length)
                $('#-js-tbody-admin-items').append(data.slots.rows);
            }
            dialog.dialog('close');  
          }
        }));
      }
    })); 
  };

  manager.confirmDelete = function(callback)
  {
    ammo.modalWindow.confirm(ammo.i18n("Удалить запись?"), callback);
  };

  manager.baseDelete = function(opt)
  {
    manager.confirmDelete(function() {
      $.ajax(myjs.ajax({
        url: opt.controller + "/ajax_delete",
        data: opt.req || {id: opt.id},
        success: function() 
        {
          if(opt.el)
            $(opt.el).remove();
          else
            $("#-js-item-"+opt.id).remove();
        }
      }));
    });
  };

  var base_manager = (function(admin)
  {
    return function(controller) {
      var manager = {};

      manager.clickEdit = function(id) 
      {
        admin.baseEdit({req: {id:id}, controller: controller});
      };

      manager.clickCreate = function()
      {
        admin.baseEdit({req: {is_create:1}, controller: controller});
      };

      manager.clickDelete = function(id) {
        admin.baseDelete({controller: controller, id: id});
      };

      admin[controller] = function() { return manager; };
    };
  })(manager);
  
  base_manager('member');
  base_manager('trash_asset');
  base_manager('levels');
  base_manager('bonus_rate');
  base_manager('seedbed_asset');
  
  // 
  // Settings
  //

  (function(admin) 
  {
    var manager = {};
    
    manager.clickEdit = function() 
    {
      admin.baseEdit({req: {id:1}, controller: 'settings'});
    };

    admin.settings = function() { return manager; };
  })(manager);

  myjs.admin = ammo.returnOrCallMethod(manager);
})(myjs);


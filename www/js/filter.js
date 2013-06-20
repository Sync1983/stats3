function filter_funct(parent) {  
  
  var activeParams = {};
  
  function setupFilter() {
    var body = $('#filter > div.modal-body').eq(0).children('#filter-param').eq(0).children('tbody').eq(0);    
    return;
  }
  
  function constructParams(form){
    var filter = $(form).parent().parent();
    filter = $(filter).children('div.modal-body').eq(0).children('#filter-param').eq(0).children('tbody').eq(0);  
    var result = {};  
    return result;
  }

  /* ======================================= Public ===========================================*/
  
  this.addRule = function(item) {
    var parent = $(item).parent().parent().parent();
    var html = "<tr><td>"+
      "<div class=\"btn-group\">"+
      "  <button name =\"btn_name\" btn_id =\"-1\" class=\"btn dropdown-toggle\" data-toggle=\"dropdown\">Поле</button>"+
      "  <button class=\"btn dropdown-toggle\" data-toggle=\"dropdown\"><span class=\"caret\"></span></button>       "+           
      "  <ul class=\"dropdown-menu\">"+
      "    <li><a href=\"#\" btn_id=\"item_id\"  onclick=\"return window.filter.changeColumn(this);\">ID ассета         </a></li>"+
      "    <li><a href=\"#\" btn_id=\"level\"    onclick=\"return window.filter.changeColumn(this);\">Уровень           </a></li>"+
      "    <li><a href=\"#\" btn_id=\"energy\"   onclick=\"return window.filter.changeColumn(this);\">Энергия           </a></li>"+
      "    <li><a href=\"#\" btn_id=\"real\"     onclick=\"return window.filter.changeColumn(this);\">Реал              </a></li>"+
      "    <li><a href=\"#\" btn_id=\"bonus\"    onclick=\"return window.filter.changeColumn(this);\">Бонус             </a></li>"+
      "    <li><a href=\"#\" btn_id=\"money\"    onclick=\"return window.filter.changeColumn(this);\">Валюта приложения </a></li>"+
      "    <li><a href=\"#\" btn_id=\"referal\"  onclick=\"return window.filter.changeColumn(this);\">Реферал           </a></li>"+
      "    <li><a href=\"#\" btn_id=\"reg_time\" onclick=\"return window.filter.changeColumn(this);\">Дата регистрации  </a></li>"+         
      "  </ul>"+
      "</div>"+
      "<div class=\"btn-group\">  "+
      "  <button name =\"btn_name\" btn_id =\"-1\" class=\"btn dropdown-toggle\" data-toggle=\"dropdown\">Действие</button>"+
      "  <button class=\"btn dropdown-toggle\" data-toggle=\"dropdown\"><span class=\"caret\"></span></button>                  "+
      "  <ul class=\"dropdown-menu\">"+
      "    <li><a href=\"#\" btn_id=\">\"     onclick=\"return window.filter.changeColumn(this);\">> </a></li>"+
      "    <li><a href=\"#\" btn_id=\">=\"    onclick=\"return window.filter.changeColumn(this);\">>=</a></li>"+
      "    <li><a href=\"#\" btn_id=\"<=\"    onclick=\"return window.filter.changeColumn(this);\"><=</a></li>"+
      "    <li><a href=\"#\" btn_id=\"<\"     onclick=\"return window.filter.changeColumn(this);\">< </a></li>"+
      "    <li><a href=\"#\" btn_id=\"!=\"    onclick=\"return window.filter.changeColumn(this);\">!=</a></li>"+
      "    <li><a href=\"#\" btn_id=\"IN\"    onclick=\"return window.filter.changeColumn(this);\">IN</a></li>"+
      "    <li><a href=\"#\" btn_id=\"LIKE\"  onclick=\"return window.filter.changeColumn(this);\">LIKE</a></li>          "+
      "  </ul>"+
      "</div>"+
      "  <input class=\"input-medium\" type=\"text\" value=\"-\" style=\"margin-top: 10px;width: 450px;\"/>"+
      "</td></tr>"+
      "<tr>"+
      "  <td>"+
      "  <input type=\"button\" class=\"btn btn-primary\" value=\"Добавить строку\" onclick=\"return window.filter.addRule(this);\"/>"+
      "  </td>     "+
      "</tr>";
    $(parent).append(html);
    return false;
  };
  
  this.changeColumn = function (item) {    
    var name = $(item).text();
    var id = $(item).attr('btn_id');    
    var button = $(item).parent().parent().parent().children('button[name*="btn_name"]');    
    button.text(name);
    button.attr('btn_id',id);
    return false;
  };
  
  this.getParams = function() {
    return JSON.stringify(activeParams);
  };

  this.show = function() {    
    function onAnswer(data) {      
      data = JSON.parse(data);      
      if(data.html) {
        $('#filter > div.modal-body').html(data.html);        
        $('#filter').modal({show:true});
        return;
      } else
        console.log(data);
    }
    main.ajax('filter','ajax_load_constructor',{project_id:project_id, page_id:main.selectedTabId}, onAnswer);
    return false;
  };

  this.setup = function(data,item) {  
    $(item).children('input').attr('checked','checked');  
    $('#filter-button').text('Фильтр: '+$(item).text());  
    activeParams = data;
    window.main.pageReload();
    return false;
  };

  this.apply = function(form) {
    var params = constructParams(form);
    activeParams = params;  
    window.main.pageReload();
    $(form).parent().parent().modal('hide');
    $('#filter-button').html('<span style="color:red">*&nbsp'+$('#filter-button').text()+'</span>');
    return false;
  };

  this.save = function(form) { 
    var params = constructParams(form);
    var name = $(form).parent().parent().children('div.modal-header').children('input').val();
    if(name=="") {
      $(form).addClass('disabled');
      return false;
    }  
    main.ajax('filter','ajax_save_filter',{project_id:project_id, filter:params, name: name});
    activeParams = params;  
    window.main.pageReload();  
    $(form).parent().parent().modal('hide');
    return false;
  };
  
  return this;
}

window.filter = filter_funct();



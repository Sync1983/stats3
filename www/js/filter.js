function filter_funct(parent) {  
  
  var activeParams = {};
  
  function setupFilter() {    
    var filter = $("#filter").children('div.modal-body').eq(0).children('#filter-param').eq(0).children('tbody').eq(0);    
    for(var i in activeParams) {
      var param = activeParams[i];
      this.addRule(param.item,param.operation,param.value,param.logic);
    }
    var name = $("#filter-button").text();
    name = $.trim(name.replace("Фильтр: ",''));
    if(name!=="Все")
      $("#filter").children('div.modal-header').eq(0).children('input').val(name);
    return;
  }
  
  function constructParams(){    
    var rows = $("#filter").children('div.modal-body').children('table').children('tbody').children('tr');    
    var result = new Array();      
    for(i=0;i<rows.length;i++){
      var row = rows[i];   
      var name  = $(row).children('td').children('div[name*="row_name"]').children('button[name*="btn_name"]').attr('btn_id');
      var oper  = $(row).children('td').children('div[name*="row_op"]').  children('button[name*="btn_name"]').attr('btn_id');
      var value = $(row).children('td').children('input').val();
      var logic = $(row).children('td').children('div[name*="row_and"]'). children('button[name*="btn_name"]').attr('btn_id');      
      var rule = {item:name,operation:oper,value:value};
      if(i+1<rows.length)
        rule['logic'] = logic;
      result.push(rule);
    }
    console.log(result);
    return result;
  }

  function isEmpty(obj) {
    for (var key in obj)
      return false;
    return true;
  }

  /* ======================================= Public ===========================================*/
  
  this.addRule = function(i_name,i_operation,i_value,i_logic) {    
    var item = 
      "<div class=\"btn-group\" name=\"row_name\">"+
      "  <button name =\"btn_name\" btn_id =\"-1\" class=\"btn dropdown-toggle\" data-toggle=\"dropdown\" style=\"width:100px;\">Поле</button>"+
      "  <button class=\"btn dropdown-toggle\" data-toggle=\"dropdown\"><span class=\"caret\"></span></button>"+           
      "  <ul class=\"dropdown-menu\" style=\"margin-left: 100px;\">"+
      "    <li><a href=\"#\" btn_id=\"item_id\"  onclick=\"return window.filter.changeColumn(this);\">ID ассета         </a></li>"+
      "    <li><a href=\"#\" btn_id=\"level\"    onclick=\"return window.filter.changeColumn(this);\">Уровень           </a></li>"+
      "    <li><a href=\"#\" btn_id=\"energy\"   onclick=\"return window.filter.changeColumn(this);\">Энергия           </a></li>"+
      "    <li><a href=\"#\" btn_id=\"real\"     onclick=\"return window.filter.changeColumn(this);\">Реал              </a></li>"+
      "    <li><a href=\"#\" btn_id=\"bonus\"    onclick=\"return window.filter.changeColumn(this);\">Бонус             </a></li>"+
      "    <li><a href=\"#\" btn_id=\"money\"    onclick=\"return window.filter.changeColumn(this);\">Валюта приложения </a></li>"+
      "    <li><a href=\"#\" btn_id=\"referal\"  onclick=\"return window.filter.changeColumn(this);\">Реферал           </a></li>"+
      "    <li><a href=\"#\" btn_id=\"reg_time\" onclick=\"return window.filter.changeColumn(this);\">Дата регистрации  </a></li>"+         
      "  </ul>"+
      "</div>";    
    var operation = 
      "<div class=\"btn-group\" name=\"row_op\">"+
      "  <button name =\"btn_name\" btn_id =\"-1\" class=\"btn dropdown-toggle\" data-toggle=\"dropdown\" style=\"width:80px;\">Действие</button>"+
      "  <button class=\"btn dropdown-toggle\" data-toggle=\"dropdown\"><span class=\"caret\"></span></button>"+
      "  <ul class=\"dropdown-menu\">"+
      "    <li><a href=\"#\" btn_id=\">\"     onclick=\"return window.filter.changeColumn(this);\">> </a></li>"+
      "    <li><a href=\"#\" btn_id=\">=\"    onclick=\"return window.filter.changeColumn(this);\">>=</a></li>"+
      "    <li><a href=\"#\" btn_id=\"<=\"    onclick=\"return window.filter.changeColumn(this);\"><=</a></li>"+
      "    <li><a href=\"#\" btn_id=\"<\"     onclick=\"return window.filter.changeColumn(this);\">< </a></li>"+
      "    <li><a href=\"#\" btn_id=\"!=\"    onclick=\"return window.filter.changeColumn(this);\">!=</a></li>"+
      "    <li><a href=\"#\" btn_id=\"IN\"    onclick=\"return window.filter.changeColumn(this);\">IN</a></li>"+
      "    <li><a href=\"#\" btn_id=\"LIKE\"  onclick=\"return window.filter.changeColumn(this);\">LIKE</a></li>"+
      "  </ul>"+
      "</div>";
    var value = 
      "  <input class=\"input-medium\" type=\"text\" value=\"-\" style=\"margin-top: 10px;width: 380px;\"/>";
    var logic = 
         "<div class=\"btn-group\" name=\"row_and\">"
        +"  <button name =\"btn_name\" btn_id =\"AND\" class=\"btn dropdown-toggle\" data-toggle=\"dropdown\">AND</button>"
        +"  <button class=\"btn dropdown-toggle\" data-toggle=\"dropdown\"><span class=\"caret\"></span></button>"
        +"  <ul class=\"dropdown-menu\">"
        +"    <li><a href=\"AND\" btn_id=\"AND\"  onclick=\"return window.filter.changeColumn(this);\">AND</a></li>"
        +"    <li><a href=\"OR\" btn_id=\"OR\"    onclick=\"return window.filter.changeColumn(this);\">OR</a></li>"
        +"  </ul>"
        +"</div>";    
    var del =   
      "  <button class =\"btn\"><span class=\"icon-remove\" onclick=\"return window.filter.removeRule(this);\"></span></button>";
    var string = 
       "<tr>"
      +"  <td>"
      +   item
      +"  </td>"
      +"  <td>"
      +   operation
      +"  </td>"
      +"  <td>"
      +   value
      +"  </td>"
      +"  <td>"
      +   logic
      +"  </td>"
      +"  <td>"
      +   del
      +"  </td>"
      +"</tr>";
    var res = $(string);
    $("#filter").children('div.modal-body').children('table').append(res);
    var e_name  = $(res).children('td').children('div[name*="row_name"]').children('ul');
    var e_oper  = $(res).children('td').children('div[name*="row_op"]').  children('ul');
    var e_value = $(res).children('td').children('input');
    var e_logic = $(res).children('td').children('div[name*="row_and"]'). children('ul');
    
    function setValue(target,value) {
      var items = $(target).children('li');
      for(var i =0;i<=items.length;i++) {
        var ref = $(items[i]).children('a');
        if(ref.attr("btn_id")===value){
          this.changeColumn(ref);
          break;
        }          
      }
      return;
    }   
  
    if(i_value!==null)
      $(e_value).val(i_value);
    if(i_logic!==null)
      setValue(e_logic,i_logic);
    if(i_name!==null)
      setValue(e_name,i_name);
    if(i_operation!==null)
      setValue(e_oper,i_operation);    
    return false;
  };

  this.removeRule = function (item) {
    var parent = $(item).parent().parent().parent();    
    $(parent).remove();
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
   var add_button = 
    +"<tr>"
    +"  <td colspan=4>"
    +"    <input type=\"button\" class=\"btn btn-primary\" value=\"Добавить строку\" onclick=\"return window.filter.addRule();\"/>"
    +"  </td>"     
    +"</tr>";
   $('#filter > div.modal-body').children('table').html(add_button);
   $('#filter').modal({show:true}); 
   
   if(isEmpty(activeParams))
    this.addRule(1,2,"qwert","OR");
   else
    setupFilter();
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
    };
  
    function onSaved(data) {
      data = JSON.parse(data);
      console.log(data);
      $("#filter-dropdown").html('');
      var html = '';
      for(var i in data) {
        var item = 
           "<li>"
          +"  <a href=\"#\" onclick='return window.filter.setup("+data[i]['data']+",this);'>"
          +"    <input type=\"radio\" name=\"optionsRadios\" id=\""+data[i]['name']+"\" value=\""+data[i]['name']+"\" style=\"margin-right: 10px;margin-top: -2px;\"/>"+data[i]['name']
          +"  </a>"
          +"</li>";
        html += item;
      }
      var fin = 
         "<li>"
        +"  <a href=\"#\" onclick=\"return window.filter.setup({},this);\">"
        +"    <input type=\"radio\" name=\"optionsRadios\" id=\"all\" value=\"all\" style=\"margin-right: 10px;margin-top: -2px;\"/>Все"
        +"  </a>"
        +"</li>"
        +"<li><a href=\"\" role=\"button\" class=\"btn\" data-toggle=\"modal\" onclick=\"return window.filter.show();\">Изменить</a></li>";
      $("#filter-dropdown").html(html+fin);
    };
  
    main.ajax('filter','ajax_save_filter',{project_id:project_id, filter:params, name: name},onSaved);
    activeParams = params;  
    window.main.pageReload();  
    $(form).parent().parent().modal('hide');
    return false;
  };
  
  return this;
}

window.filter = filter_funct();




function main_funct(parent) {
  var main = {};
  
  if(parent.main)
    main = parent.main;
  
  main.selectedTabId = -1;  
  
  function updateChartPosition(event,ui) {
    console.log("Change");
    var items = $('#main-view').children('li.chart');
    var pos = new Array();    
    for(var i = 0;i<items.length;i++) {      
      var id = items[i].getAttribute('id');
      if(id!=="chart_add")
        pos.push(id);
    }
    if(main.selectedTabId===-1) {
       console.log("Undefined page id");
       return false;
     }    
  
    function onSaved(data) {
      console.log(data);
      return;
    };
  
    main.ajax('main_page','ajax_change_positions',{project_id:project_id, page_id:main.selectedTabId,query:pos}, onSaved);
  }

  function loadCharts(charts){    
    for(var i in charts) {
      var chart_item = charts[i];  
      var item = $("#chart_"+chart_item.id+" .chart_graph")[0];      
      var chart = new window.chart.addChart(item,chart_item.counter_id,chart_item.name,chart_item.id,chart_item.data_type);
      chart.ajax_load(chart);      
    }
    $("#main-view").sortable({
     stop: updateChartPosition,
     cancel: ".sortable-disabled",
    });
    $("#main-view").disableSelection();
  }

  function getActiveTabName() {
    return $('#navTab'+main.selectedTabId).text();
  }

  function validateJSON(data) {
    try {
        data = JSON.parse(data);          
      } catch(e) {
        console.log("Error in answer "+e+" in data "+data);
        return null;
      }
    return data;
  }
  
  main.ajax = function(controller,action,data,success) {
    main.showLoader();
    var ajaxRequest = {};
    ajaxRequest.type = "POST";
    ajaxRequest.data = data;
    ajaxRequest.url = controller+'/'+action;
    ajaxRequest.success = function(data) {
      main.hideLoader();
      if(!success)
        return;
      success(data);
    };
    $.ajax(ajaxRequest);
  };
  
  main.showLoader  = function (){    
    $('.ajax-loader').css('display','block');
    return;
  };

  main.hideLoader = function() {
    $('.ajax-loader').css('display','none');
    return;
  };

  main.loginClick = function(item) {    
    var login = $('#login').val();    
    var password = $('#password').val();
    
    main.ajax('login','ajax_login',{login:login,password:password},function(returnData){
      var data = JSON.parse(returnData);      
      if(data.error) {
        $('.errors').css('display','block');
        $('.errors').text('*'+data.error);
        return false;
      } else if(data.redirect) {        
        location.href = window.myjs.server_vars.base_path+data.redirect;
      }
      $('.errors').css('display','none');      
    });
    return false;
  };

  main.selectProject = function(new_project_id) {    
    location.href = window.myjs.server_vars.base_path+'page/?project_id='+new_project_id;
  };

  main.getPeriod = function() {    
    var range = $("#dateRange-txt").val();
    var split = range.split(' - ');
    var bday = new Date(split[0]);
    var eday = new Date(split[1]);    
    var ret_obj = {};
    ret_obj.bday = (bday.getTime()/1000);
    ret_obj.eday = (eday.getTime()/1000);
    return ret_obj;
  };

  main.addTab = function () { 
    var pid;
    var mid;
    var dialog;
    
    function save(data) {
      if(!(data = validateJSON(data)))
        return false;
    
      if(data.error) {
        dialog.children(".error").css('display','block');
        dialog.children(".error").text(data.error);
        return;
      }                      
      dialog.dialog('close');
      main.selectProject(pid);
    }
    
    function onSave() {      
      var save_data = {};                  
      save_data['name'] = $('#name').val();
      save_data['pid'] = pid;
      save_data['mid'] = mid;                  
      main.ajax('main_page','ajax_save_tab',save_data,save);
      return false;
    };
    
    function onAnswer(data) {
      if(!(data = validateJSON(data)))
        return false;
      
      pid = project_id;
      mid = data.mid;
      dialog = $('<div>'+data.html+'<div class="error" style="display:none;color:red"></div></div>')
        .dialog({
          width:  500,               
          resizable:false,
          title: "Добавить вкладку",
          close: function(event, ui) {                    
           dialog.remove();                    
          },buttons: { "Сохранить": onSave },               
          modal: true
      });
    };
      
    main.ajax('main_page','ajax_add_tab',null,onAnswer);
    return false;
  };

  main.pageReload = function() {
    main.loadTab();
    return false;
  };

  main.loadPage = function(page_id) {
    $('ul.nav.nav-tabs>li').each( function (index,item) {
      $(item).attr('class','');
    });    
    main.selectedTabId = page_id;
    //$('ul.nav.nav-tabs>li').eq(0).attr('class','active');
    $('ul.nav.nav-tabs>li>a#navTab'+main.selectedTabId).parent().attr('class','active');    
    main.loadTab();
    return false;
  };

  main.loadTab = function() {
    if(main.selectedTabId===-1) {
      console.log("Undefined tab for loading");
      return;
    }    
    $('.chart').remove();
    
    function onAnswer(data) {      
      if(!(data = validateJSON(data)))
        return false;
      
      if(data.error) {
        alert(data.error);                        
        return false;
      } else if(data.html) {
        $('#chartspace').html(data.html);
        if(data.charts)
          loadCharts(data.charts);
      }                      
      return false;
    }
    
    main.ajax('main_page','ajax_load_tab',{project_id:project_id, page_id:main.selectedTabId}, onAnswer);
    return false;                    
  };

  main.renameTab = function() {
    if(main.selectedTabId===-1) {
      console.log("Undefined tab for loading");
      return false;
    }
  
    var dialog = $( '<div>'+
                      "Переименовать \""+getActiveTabName()+"\"? <br>"+
                      '<input type="text" id="rename_text" style="margin-top: 5px; width:98%;" value="'+main.selectedTabName+'"/>'+
                      '<div class="error" style="display:none;color:red"></div>'+
                    '</div>').dialog({               
                      width:  500,               
                      resizable:false,
                      title: "Переименовать вкладку",
                      close: function(event, ui) {                    
                       dialog.remove();                    
                      },
                      buttons: {
                       "Переименовать": onRename              
                      }
                 });             
               
    function onRename(){      
      var new_name = $('#rename_text').val();
      main.ajax('main_page','ajax_rename_tab',{id:main.selectedTabId,new_name:new_name},onAnswer);
    };
  
    function onAnswer(data) {
      if(!(data = validateJSON(data)))
        return false;
      if(data.error) {
        dialog.children(".error").css('display','block');
        dialog.children(".error").text(data.error);
        return;
      }                      
      dialog.dialog('close');
      main.selectProject(project_id);
    };
    return false;
  };

  main.deleteTab = function() {    
    var dialog;
    
    if(main.selectedTabId===-1) {
      console.log("Undefined tab for loading");
      return false;
    }
  
    function onDeleteAnswer(data) {  
      if(!(data = validateJSON(data)))
        return false;
      
      if(data.error) {
        dialog.children(".error").css('display','block');
        dialog.children(".error").text(data.error);
        return;
      }                      
      dialog.dialog('close');
      main.selectProject(project_id);
    };
  
    function onDelete() {      
      main.ajax('main_page','ajax_delete_tab',{id:main.selectedTabId}, onDeleteAnswer);                
    };
    
    dialog = $('<div>'+"Удалить вкладку \""+getActiveTabName()+"\"?"+'<div class="error" style="display:none;color:red"></div></div>')
             .dialog({               
               width:  500,               
               resizable:false,
               title: "Удалить вкладку?",
               close: function(event, ui) { dialog.remove(); },
               buttons: { "Удалить": onDelete }
             });   
    return false;
  };

  main.deleteChart = function(id) {
    if(main.selectedTabId === -1) {      
      main.selectedTabId = $('.etabs>li>a')[0].getAttribute('id');
      main.selectedTabName = $('.etabs>li>a')[0].text;
    };
  
    var dialog = $('<div>'+"Удалить диаграмму?"+'<div class="error" style="display:none;color:red"></div></div>')
             .dialog({               
               width:  500,               
               resizable:false,
               title: "Удалить вкладку?",
               close: function(event, ui) {                    
                dialog.remove();                    
               },
               buttons: { "Удалить": onDeleteButton }
             });             
           
    function onDeleteAnswerRecive(data) {      
      if(data.error) {
        dialog.children(".error").css('display','block');
        dialog.children(".error").text(data.error);
        return;
      }                      
      dialog.dialog('close');
      main.pageReload();
    }       
  
    function onDeleteButton() {
      main.ajax('main_page','ajax_delete_chart',{chart_id:id},onDeleteAnswerRecive);
    };
  
  };

  main.showToolbox = function(item) {
    var box = $(item).parent().children(".toolbox");
    var state = $(box).css('display');    
    if((state=="none")||(!state))
      $(box).css('display','block');
    else
      $(box).css('display','none');
  };

  return main;
}

window.main = main_funct(window);



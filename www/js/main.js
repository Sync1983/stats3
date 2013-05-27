
function main_funct(parent) {
  var main = {};
  
  if(parent.main)
    main = parent.main;
  
  main.selectedTabId = -1;
  main.selectedTabName = '';
  
  main.ajax = function(controller,action,data,success) {
    main.showLoader();
    var ajaxRequest = {};
    ajaxRequest.type = "POST";
    ajaxRequest.data = data;
    ajaxRequest.url = controller+'/'+action;
    ajaxRequest.success = function(data) {
      main.hideLoader();
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

  main.selectProject = function(dom) {
    var select = $(dom);
    location.href = window.myjs.server_vars.base_path+'?project_id='+select.val();
  };

  main.getPeriod = function() {    
    var range = $("#dateRange-txt").text();
    var split = range.split(' - ');
    var bday = new Date(split[0]);
    var eday = new Date(split[1]);    
    var ret_obj = {};
    ret_obj.bday = bday.getTime()/1000;
    ret_obj.eday = eday.getTime()/1000;
    return ret_obj;
  };

  main.changeTab = function (event, clicked, targetPanel, settings) {    
    var id = clicked.attr('id');    
    if(id === 'add_tab') {
       main.ajax('main_page','ajax_add_tab',null,
        function(data){
          data = JSON.parse(data);  
          var pid = project_id;
          var mid = data.mid;
          var dialog = $('<div>'+data.html+'<div class="error" style="display:none;color:red"></div></div>')
             .dialog({               
               width:  500,               
               resizable:false,
               title: "Добавить вкладку",
               close: function(event, ui) {                    
                dialog.remove();                    
               },
               buttons: {
                "Сохранить": function() {                  
                  var save_data = {};                  
                  save_data['name'] = $('#name').val();
                  save_data['pid'] = pid;
                  save_data['mid'] = mid;                  
                  main.ajax('main_page','ajax_save_tab',save_data,
                    function(data){                      
                      data = JSON.parse(data);
                      if(data.error) {
                        dialog.children(".error").css('display','block');
                        dialog.children(".error").text(data.error);
                        return;
                      }                      
                      dialog.dialog('close');
                      location.href = "?project_id="+pid;;
                    });
                },
               },               
               modal: true
              });         
        });
      return false;
    }    
    main.selectedTabId = clicked[0].getAttribute('id');
    main.selectedTabName = clicked[0].text;
    main.loadTab();
    return true;
  };

  main.pageReload = function() {
    main.loadTab();
    return;
  };

  main.loadTab = function() {
    if(main.selectedTabId===-1) {
      main.selectedTabId = $('.etabs>li>a')[0].getAttribute('id');
      main.selectedTabName = $('.etabs>li>a')[0].text;
    }    
    $('.chart').remove();
    if(main.selectedTabId==='add_tab')
      return;
    main.ajax('main_page','ajax_load_tab',{project_id:project_id, page_id:main.selectedTabId},
                    function(data){                      
                      data = JSON.parse(data);
                      if(data.error) {
                        alert(data.error);                        
                        return;
                      } else if(data.html) {
                        $('#content').html(data.html);
                        if(data.charts)
                          loadCharts(data.charts);
                      }                      
                    });
  };

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
       main.selectedTabId = $('.etabs>li>a')[0].getAttribute('id');
       main.selectedTabName = $('.etabs>li>a')[0].text;
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

  main.renameTabAlert = function(tabs) {
    if(main.selectedTabId === -1) {      
      main.selectedTabId = $('.etabs>li>a')[0].getAttribute('id');
      main.selectedTabName = $('.etabs>li>a')[0].text;
    }
  
    var dialog = $( '<div>'+
                      "Переименовать \""+main.selectedTabName+"\"? <br>"+
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
      data = JSON.parse(data);
      if(data.error) {
        dialog.children(".error").css('display','block');
        dialog.children(".error").text(data.error);
        return;
      }                      
      dialog.dialog('close');
      location.href = location.href;
    };
  };

  main.deleteTabAlert = function(tabs) {
    if(main.selectedTabId === -1) {      
      main.selectedTabId = $('.etabs>li>a')[0].getAttribute('id');
      main.selectedTabName = $('.etabs>li>a')[0].text;
    }    
    var dialog = $('<div>'+"Удалить вкладку \""+main.selectedTabName+"\"?"+'<div class="error" style="display:none;color:red"></div></div>')
             .dialog({               
               width:  500,               
               resizable:false,
               title: "Удалить вкладку?",
               close: function(event, ui) {                    
                dialog.remove();                    
               },
               buttons: {
                "Удалить": function() {                  
                  main.ajax('main_page','ajax_delete_tab',{id:main.selectedTabId},
                    function(data){                      
                      data = JSON.parse(data);
                      if(data.error) {
                        dialog.children(".error").css('display','block');
                        dialog.children(".error").text(data.error);
                        return;
                      }                      
                      dialog.dialog('close');
                      main.pageReload();
                    });
                }
               }                
             });             
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

  return main;
}

window.main = main_funct(window);

$(window).bind('click',function(event) {
  if(event.target.className!=='chart-to-full')
    $('#main-chart').css('display','none');
  if(event.target.className!=='chart-toolbox')
    $(".toolbox").each(function (num,item){    
      $(item).css('display','none');
    });
});



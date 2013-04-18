
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
      console.log(data);
      if(data.error) {
        $('.errors').css('display','block');
        $('.errors').text('*'+data.error);
        return false;
      } else if(data.redirect) {
        console.log('Redirecting');
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

  main.changeTab = function (event, clicked, targetPanel, settings) {    
    var id = clicked.attr('id');
    console.log(clicked);
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
                  console.log($('#name'));
                  save_data['name'] = $('#name').val();
                  save_data['pid'] = pid;
                  save_data['mid'] = mid;
                  console.log(save_data);
                  main.ajax('main_page','ajax_save_tab',save_data,
                    function(data){
                      console.log(data);
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

  main.loadTab = function() {
    if(main.selectedTabId===-1) {
      main.selectedTabId = $('.etabs>li>a')[0].getAttribute('id');
      main.selectedTabName = $('.etabs>li>a')[0].text;
    }
    console.log({selectedTabId:main.selectedTabName});
    $('.chart').remove();
    main.ajax('main_page','ajax_load_tab',{project_id:project_id, page_id:main.selectedTabId},
                    function(data){
                      console.log("Load tabs:"+data);
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

  function loadCharts(charts){
    console.log(charts);    
    for(var i in charts) {
      var chart_item = charts[i];  
      var item = $("#chart_"+chart_item.id+" .chart_graph")[0];      
      var chart = new window.chart.addChart(item,chart_item.counter_id,chart_item.name);
      chart.ajax_load(chart);
      //while(!chart.isLoaded);
    }
  }

  main.renameTabAlert = function(tabs) {
    if(main.selectedTabId === -1) {      
      main.selectedTabId = $('.etabs>li>a')[0].getAttribute('id');
      main.selectedTabName = $('.etabs>li>a')[0].text;
    }
    var dialog = $('<div>'+"Удалить вкладку \""+main.selectedTabName+"\"?"+'<div class="error" style="display:none;color:red"></div></div>')
             .dialog({               
               width:  500,               
               resizable:false,
               title: "Переименовать вкладку",
               close: function(event, ui) {                    
                dialog.remove();                    
               },
               buttons: {
                "Удалить": function() {                  
                  main.ajax('main_page','ajax_delete_tab',{id:main.selectedTabId},
                    function(data){
                      console.log(data);
                      data = JSON.parse(data);
                      if(data.error) {
                        dialog.children(".error").css('display','block');
                        dialog.children(".error").text(data.error);
                        return;
                      }                      
                      dialog.dialog('close');
                      location.href = "?project_id="+project_id;
                    });
                }
               }                
             });             
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
                      console.log(data);
                      data = JSON.parse(data);
                      if(data.error) {
                        dialog.children(".error").css('display','block');
                        dialog.children(".error").text(data.error);
                        return;
                      }                      
                      dialog.dialog('close');
                      location.href = "?project_id="+project_id;
                    });
                }
               }                
             });             
  };

  return main;
}

window.main = main_funct(window);



/* include main.js */

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
      var chart = new window.chart.addChart(item,chart_item.counter_id,chart_item.name,chart_item.id);
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
    var dialog = $('<div>'+"Переименовать \""+main.selectedTabName+"\"?"+'<div class="error" style="display:none;color:red"></div></div>')
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
$(window).bind('click',function() {$('#main-chart').css('display','none');});



/* include chart.js */

function chart_funct(parent) {
   var chart = {};
   
   if(parent.chart)
     chart = parent.chart;
   
  chart.addChart = function (container,id,chart_name,chart_id) {    
     var options = {
     title: { text: chart_name },    
     chart: { renderTo: container, type: 'areaspline', zoomType: 'none',
              events: { click: onMouseChartClick }
             },
     plotOptions: { area: { marker: { enabled: true, symbol: 'circle', radius: 2, states: { hover: { enabled: true } } } } },
     series: [{}],
     xAxis: {
       title: {text: 'Время'},
       type: 'datetime',
       dateTimeLabelFormats: { day: '%e of %b'},
       maxZoom: 48 * 3600 * 1000,      
       labels: {rotation: -45, style: {fontSize: '10px',fontFamily: 'Verdana, sans-serif'} }
     },
     yAxis: { title: { text: 'Кол-во' } }, 
     tooltip:{
        crosshairs: [true],
        formatter: function(){
           return "<b>"+this.series.name+"</b><br>"+Highcharts.dateFormat("%d-%m-%Y", this.x) + '<br><i>' +
           Highcharts.numberFormat(this.y, 1)+"</i>";
          }
       }
    };
    var chart = new Highcharts.Chart(options);     
    chart.isLoaded = false;
    chart.chart_id = id;
    chart.chart_vid = chart_id;
    chart.ajax_load = chartAjaxLoad;    
    return chart;
 };

 function chartAjaxLoad(chart) {  
  function onLoaded(data) {    
    data = JSON.parse(data);
    if(data.options) {
      var opt = chart.getOptions();
      for(var i in data.options) {
        opt[i] = data.options[i];
      }    
      chart.setOptions(options);
    };
    if(data.series) {      
      for(var i in chart.series)
        chart.series[i].remove();      
      for(var i in data.series)
        chart.addSeries(data.series[i],true);            
    }
    if(data.error) {
      alert("Loading error:"+data.error);
      return;
    }
  }
  var data = window.main.getPeriod();
  data.id = chart.chart_id;
  data.vid = chart.chart_vid;
  data.project_id = project_id;
  window.main.ajax('chart','ajax_load_chart',data,onLoaded);
  return;
 }

 function onMouseOver(event){   
   var target = event.currentTarget;
   $(target).css('border-color','lime');
 }
 
 function onMouseOut(event){   
   var target = event.currentTarget;
   $(target).css('border-color','#000');
 }
 
 function onMouseChartClick(event) {   
   var target = event.currentTarget;   
   $('#main-chart').css('display','block');
   options = target.options;
   options.chart.renderTo = "main-chart";
   console.log(event);
   var chart = new Highcharts.Chart(options);
   chart.chart_id = target.chart_id;
   chart.chart_vid = target.chart_vid;
   chartAjaxLoad(chart);
 }
 
 function onAddNewChart() {
   main.ajax('page','ajax_get_presets',{project_id:project_id,page_id:window.main.selectedTabId}, function(data){
     data = JSON.parse(data);
     showAddDialog(data.html,window.main.selectedTabId);
   });
 };
 
 function onMouseClick(event) {
   var target = event.currentTarget;
   var id = target.getAttribute('id');   
   if(id==="chart_add") {
     onAddNewChart();
     return;
   }     
 }
 
 function showAddDialog(html,page_id) {
   var dialog = $('<div id="add-dialog">'+html+'<div class="error" style="display:none;color:red"></div></div>')
             .dialog({               
               width:  600,               
               resizable:false,
               title: "Добавить диаграмму",
               close: function(event, ui) {                    
                dialog.remove();                    
               },
               buttons: {
                "Добавить": function() {                  
                  var selector_id = $("#add-dialog .active-counter-id option:selected").val();                  
                  main.ajax('page','ajax_add_chart',{project_id:project_id,page_id:main.selectedTabId,counter_id:selector_id},
                    function(data){                      
                      data = JSON.parse(data);
                      if(data.error) {
                        dialog.children(".error").css('display','block');
                        dialog.children(".error").text(data.error);
                        return;
                      }                      
                      dialog.dialog('close');
                      window.main.loadTab();
                    });
                },
                "Создать": function() {
                  // TODO Добавить конструктор
                }
               }                
             });        
 };
 
 chart.addEvents = function() {
   $('.chart').each(function(index,elem) {
     $(elem).bind('mouseenter',onMouseOver);     
     $(elem).bind('mouseleave',onMouseOut);
    });
    $("#chart_add").bind('click',onMouseClick);
  };
  
  return chart;
}

window.chart = chart_funct(window);
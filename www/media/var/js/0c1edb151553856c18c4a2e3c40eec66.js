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



/* include chart.js */

 function chart_funct(parent) {
   var chart = {};
   
   if(parent.chart)
     chart = parent.chart;
   
  chart.addChart = function (container,id,chart_name) {
     var options = {
     title: { text: chart_name },    
     chart: { renderTo: container, type: 'areaspline', zoomType: 'none',
              events: { click: function(event){ /*Click on surface*/ } }
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
         formatter: function(){
           return "<b>"+this.series.name+"</b><br>"+Highcharts.dateFormat("%d-%m-%Y", this.x) + '<br><i>' +
           Highcharts.numberFormat(this.y, 1)+"</i>";
          }
       }
    };
    var chart = new Highcharts.Chart(options); 
    chart.isLoaded = false;
    chart.chart_id = id;
    chart.ajax_load = chartAjaxLoad;
    return chart;
 };

 function chartAjaxLoad(chart) {
  console.log(chart);
  function onLoaded(data) {
    data = JSON.parse(data);
    console.log(data);
    if(data.options) {
      var opt = chart.getOptions();
      for(var i in data.options) {
        opt[i] = data.options[i];
      }    
      chart.setOptions(options);
    };
    if(data.series) {
      chart.GetOptions().series = data.series;
    }
    if(data.error) {
      alert("Loading error:"+data.error);
      return;
    }
  }

  window.main.ajax('chart','ajax_load_chart',{id:chart.chart_id,project_id:project_id},onLoaded);
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
 
 function onAddNewChart() {
   main.ajax('page','ajax_get_presets',{project_id:project_id,page_id:window.main.selectedTabId}, function(data){
     data = JSON.parse(data);
     showAddDialog(data.html,window.main.selectedTabId);
   });
 };
 
 function onMouseClick(event) {
   var target = event.currentTarget;
   var id = target.getAttribute('id');
   console.log(id);
   if(id==="chart_add") {
     onAddNewChart();
     return;
   }
     
 }
  
  chart.addEvents = function() {
    $('.chart').each(function(index,elem) {
      $(elem).bind('mouseenter',onMouseOver);
      $(elem).bind('mouseleave',onMouseOut);
      $(elem).bind('click',onMouseClick);
    });
  };
  
  return chart;
}

window.chart = chart_funct(window);

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
  function onLoaded(data) {    
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
  var data = window.main.getPeriod();
  data.id = chart.chart_id;
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
   if(id==="chart_add") {
     onAddNewChart();
     return;
   }     
 }
  
 chart.addEvents = function() {
   $('.chart').each(function(index,elem) {
     $(elem).bind('mouseenter',onMouseOver);
     $(elem).bind('click',onMouseClick);
     $(elem).bind('mouseleave',onMouseOut);
    });
  };
  
  return chart;
}

window.chart = chart_funct(window);

function chart_funct(parent) {
   var chart = {};
   
   if(parent.chart)
     chart = parent.chart;
   
  chart.addChart = function (container,id,chart_name,chart_id) {    
     var options = {
     title: { text: chart_name },    
     chart: { renderTo: container, type: 'areaspline', zoomType: 'none',
              events: {  }
             },
     plotOptions: { area: { marker: { enabled: true, symbol: 'circle', radius: 2, states: { hover: { enabled: true } } } } },
     series: [{}],
     xAxis: {
       title: {text: 'Время'},
       type: 'datetime',
       dateTimeLabelFormats: { day: '%e of %b'},
       //maxZoom: 48 * 3600 * 1000,      
       labels: {rotation: -45, style: {fontSize: '10px',fontFamily: 'Verdana, sans-serif'} }
     },
     yAxis: { title: { text: 'Кол-во' } }, 
     tooltip:{
        crosshairs: [true],
        formatter: function(){
           return "<b>"+this.series.name+"</b><br>"+Highcharts.dateFormat("%d-%m-%Y", this.x) + '<br><i>' +
           Highcharts.numberFormat(this.y, 3)+"</i>";
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
 
 function mergeRecursive(obj1, obj2) {
  for (var p in obj2) {    
    if (obj2[p].constructor===Object )
      obj1[p] = mergeRecursive(obj1[p], obj2[p]);
    else
      obj1[p] = obj2[p];      
  }
  return obj1;
}

 function chartAjaxLoad(chart) {  
  function onLoaded(data) {    
    data = JSON.parse(data);
    var options = chart.options;
    if(data.xAxis) {      
      chart.xAxis[0].update(mergeRecursive(chart.xAxis[0].options,data.xAxis));      
      chart.tooltip.options.formatter=function(){
           return "<b>"+this.series.name+"</b><br> Позиция: <b>"+ this.x + '</b><br><i>' +
           Highcharts.numberFormat(this.y, 1)+"</i>";
          };
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
    $("#chart_add").bind('click',onMouseClick);    
  };
  
  chart.onFull = function (vid,counter_id) {            
    $('#main-chart').css('display','block');
    //container,id,chart_name,chart_id
    console.log(counter_id,vid);
    var chart = this.addChart("main-chart",counter_id,'',vid);    
    chartAjaxLoad(chart); 
  };

  chart.changeView = function (type,vid) {
    function onViewChanged() {
      var charts = Highcharts.charts;
      charts.forEach(function (item) {        
        if((item.chart_vid)&&(item.chart_vid===vid)) {
          for(var i in item.series)
            item.series[i].remove();
          item.ajax_load(item);          
        }
      });                  
    }  
    main.ajax('chart','ajax_change_view_chart',{vid:vid,type:type}, onViewChanged);
  };

  chart.exportCSV = function (vid) {
    
    function showDialog(chartName,csvText) {
      var dialog = $( '<div>'+
                      "\""+chartName+"\"<br>"+
                      '<textarea id="exportArea" readonly>'+csvText+'</textarea>'+                      
                    '</div>').dialog({
                      width:800,
                      height:500,
                      resizable:true,
                      title: "Экспорт CSV",
                      close: function(event, ui) {                    
                       dialog.remove();                    
                      },                      
                 });     
    };
  
    function constructString(chart) {      
      var categories = chart.xAxis[0].categories;      
      var series = chart.series; 
      var lines = series.length;
      var cols = new Array();      
      var result = new Array();      
      for(var index in series) {
        var seria = series[index];
        var name = seria.name;
        for(var point in seria.data) {
          var x = seria.data[point].x;
          if(!categories)
            x = seria.data[point].x;
          else
            x = seria.data[point].name;
          var y = seria.data[point].y;
          console.log(name,x,y);          
          if(!result[x])
            result[x] = new Array();
          if(cols.indexOf(name)==-1)
            cols.push(name);
          result[x][name] = y;
        }      
      }
      console.log(result);      
       var str = new String();
      //Header
      str = "Row";
      for(var index in cols)
        str += ","+cols[index];      
      //Data
      for(var index in result){
        if(categories)
          str += "\r\n"+index;
        else {
          var date = new Date(index*1);          
          str += "\r\n"+date.getUTCDate()+"-"+(date.getUTCMonth()+1)+"-"+date.getFullYear();          
        }        
        for(var point in cols)
          if(result[index][cols[point]])
            str += ","+result[index][cols[point]];
          else
            str += ",0";
      }
      console.log(str);      
      return str;
    }
    
    var charts = Highcharts.charts;
    charts.forEach(function (item) {        
      if(item.hasOwnProperty('chart_vid')&&(item.chart_vid===vid)) {
        var exprt = constructString(item);
        showDialog(item.options.title.text, exprt);
      }    
    });
  };
  
  return chart;
}

window.chart = chart_funct(window);
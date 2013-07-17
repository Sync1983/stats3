
function chart_funct(parent) {
   var chart = {};
   
   if(parent.chart)
     chart = parent.chart;
   
  chart.addChart = function (container,id,chart_name,chart_id,data_type) {    
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
       showEmpty: false,
       dateTimeLabelFormats: { day: '%e of %b'},
       //maxZoom: 48 * 3600 * 1000,      
       labels: {rotation: -45, align:'right', style: {fontSize: '10px',fontFamily: 'Verdana, sans-serif'} }
     },
     yAxis: { title: { text: 'Кол-во' } }, 
     tooltip:{
        crosshairs: [true],
        shared: true,
        /*formatter: function(){           
           var text_tip = "<b>"+Highcharts.dateFormat("%d-%m-%Y", this.x+86400000) + "</b>";
           var lines = new Array();
           for(var i in this.points) {
             var y = this.points[i].y;                          
             var text = "<br>"+this.points[i].series.name+": <i>";             
            if(y%1!==0)
              text += Highcharts.numberFormat(y, 3);
            else
              text += y;
            text += "</i> ";
            if((this.points[i].userOptions)&&(this.points[i].series.userOptions.units))
              text += this.points[i].series.userOptions.units;
            lines[parseInt(y*1000)] = text;
           }
           lines = lines.reverse();           
           for(var i in lines)
             text_tip +=lines[i];
           return text_tip;
          }*/
       }
    };
    var chart = new Highcharts.Chart(options);     
    chart.isLoaded = false;
    chart.chart_id = id;
    chart.chart_vid = chart_id;
    chart.data_type = data_type;
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
    try{
      data = JSON.parse(data);      
    } catch (e) {
      console.log("Error "+e+" in answer "+data);
      return;
    }
    var options = chart.options;
    if(data.xAxis) {      
      chart.xAxis[0].update(mergeRecursive(chart.xAxis[0].options,data.xAxis));      
      chart.tooltip.options.formatter=function(){
        var text = "Позиция: <b>"+ this.x + '</b><br>';
        for(var i in this.points) {
         var append = "";
         if((this.points[i].userOptions)&&(this.points[i].series.userOptions.units))
          append = this.points[i].series.userOptions.units;
         text += "<br>"+this.points[i].series.name+": <i>";
         var y = this.points[i].y;
         if(y%1!==0)
           text += Highcharts.numberFormat(this.points[i].y, 1);
         else
           text += this.points[i].y;
         text += "</i> "+append;
        }
        return text;
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
  var data = window.timeController.getPeriod();
  data.id = chart.chart_id;
  data.vid = chart.chart_vid;
  data.project_id = project_id;
  data.data_type = chart.data_type;
  data.filter = window.filter.getParams();
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
 
 
 function showAddPreset() {
   
   function onPageLoad(data) {
     data = JSON.parse(data);
     if(data.error) 
       return;
     html = data.html;     
     var dialog1 = $('<div id="preset-dialog">'+html+'<div class="error" style="display:none;color:red"></div></div>')
      .dialog({               
        width:  "80%",        
        height: 600,
        resizable:true,
        title: "Формулы",
        close: function(event, ui) {                    
                dialog1.remove();                    
               },
      });
      $('#radio').button();
      $('#radio>button').click(function () {
        if(this.id=="radio1") {
          $('#preset-div').css("display","block");
          $('#logger-div').css("display","none");
        }
        else if(this.id=="radio2") {
          $('#preset-div').css("display","none");
          $('#logger-div').css("display","block");
        }        
      });      
      var tbl1 = $('#preset-table').dataTable({
        "iDisplayLength": 50,
      });
      var tbl2 = $('#logger-table').dataTable({
        "iDisplayLength": 50,
      });      
      // Insert editable for preset table
      tbl1.$('td[fixed!="fixed"]').editable( '/preset/ajax_change_row/', {
        "callback": function( sValue, y ) {
            if(!sValue)
              return;            
            tbl1.fnUpdate( sValue, aPos[0], aPos[1] );
        },
        "submitdata": function ( value, settings ) {
          if (confirm("Изменить поле "+this.getAttribute('name')+"?"))
            return {
                "table" : "preset", 
                "pid": project_id,
                "row_id": this.getAttribute('row'),
                "name": this.getAttribute('name'),
            };
          else
            return false;
        },
        "height": "100%",
        "width": "100%"
       });
     // Insert editable for logger table
     tbl2.$('td[fixed!="fixed"]').editable( '/preset/ajax_change_row/', {
        "callback": function( sValue, y ) {
            if(!sValue)
              return;
            var aPos = tbl2.fnGetPosition( this );
            tbl2.fnUpdate( sValue, aPos[0], aPos[1] );
        },
        "submitdata": function ( value, settings ) {   
            if (confirm("Изменить поле "+this.getAttribute('name')+"?"))
              return {
                  "table" : "logger", 
                  "pid": project_id,
                  "row_id": this.getAttribute('row'),
                  "name": this.getAttribute('name')
              };
            else
              return false;
        },
        "height": "100%",
        "width": "100%"
       });      
   }
   
   main.ajax('preset','ajax_get_page',{project_id:project_id},onPageLoad);
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
                  var data = 0;
                  var selector_id = $("#add-dialog #standart_selector option:selected").val();
                  var logger_selector_id = $("#add-dialog #logger_selector option:selected").val();
                  console.log("Selector: "+selector_id+" Logger selector: "+logger_selector_id);
                  if(selector_id=='-') {
                    data = 1;
                    if(logger_selector_id=='-')
                      return;
                    selector_id = logger_selector_id;
                  }
                  main.ajax('page','ajax_add_chart',{project_id:project_id,page_id:main.selectedTabId,counter_id:selector_id,data:data},
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
                "Создать": function () {                  
                    dialog.dialog('close');
                    showAddPreset();
                    },
               }                
             });        
 };
 
 chart.addEvents = function() {
    $("#chart_add").bind('click',onMouseClick);    
  };

 chart.addChartClick = function () {
    onAddNewChart();
    return false;
  }
  
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

  chart.addPresetLine = function() {
    var type = 0;
    
    function onAnswer(data) {
      data = JSON.parse(data);
      var table = $('#preset-table').dataTable();
      if(data.type=="logger")
        table = $('#logger-table').dataTable();
      console.log(data.item);
      var added = table.fnAddData( data.item, true );
      for(i in added) {
        var node = table.fnGetNodes(i);        
        console.log(node);
        $(node).children('td').editable( '/preset/ajax_change_row/', {
            "callback": function( sValue, y ) {
                if(!sValue)
                  return;
                var aPos = table.fnGetPosition( this );
                table.fnUpdate( sValue, aPos[0], aPos[1] );
            },
            "submitdata": function ( value, settings ) {   
                if (confirm("Изменить поле "+this.getAttribute('name')+"?"))
                  return {
                      "table" : "logger", 
                      "pid": project_id,
                      "row_id": this.getAttribute('row'),
                      "name": this.getAttribute('name')
                  };
                else
                  return false;
            },
            "height": "100%",
            "width": "100%"
        });
        
      }
      return false;
    }
    
    if ($('#preset-div').css("display")=="block")
      type = "preset";
    else if($('#logger-div').css("display")=="block")
      type = "logger";
    main.ajax('chart','ajax_add_preset_line',{pid:project_id,type:type}, onAnswer);
    return false;
  }

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

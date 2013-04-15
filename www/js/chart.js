
function chart_funct(parent) {
  var chart = {};
  
  if(parent.chart)
    chart = parent.chart;
  
  chart.addChart = function (name,id) {
    
  };

 function onMouseOver(event){   
   var target = event.currentTarget;
   $(target).css('border-color','lime');
 }
 
 function onMouseOut(event){   
   var target = event.currentTarget;
   $(target).css('border-color','#000');
 }
 
 function showAddDialog(html,page_id) {
   var dialog = $('<div>'+html+'<div class="error" style="display:none;color:red"></div></div>')
             .dialog({               
               width:  600,               
               resizable:false,
               title: "Добавить диаграмму",
               close: function(event, ui) {                    
                dialog.remove();                    
               },
               buttons: {
                "Добавить": function() {                  
                  var counter_id = $(dialog).children('#active-counter-id').val();
                  main.ajax('page','ajax_add_chart',{project_id:project_id,page_id:main.selectedTabId,counter_id:counter_id},
                    function(data){
                      console.log(data);
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



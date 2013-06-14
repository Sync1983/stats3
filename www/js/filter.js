function filter_funct(parent) {
  var filter = {};
  var activeParams = {};
  
  filter.getParams = function() {
    return JSON.stringify(activeParams);
  };

  function setupFilter() {
    var body = $('#filter > div.modal-body').eq(0).children('#filter-param').eq(0).children('tbody').eq(0);    
    $(body).children('tbody>tr').each(function(item,elem){      
      var name = $(elem).attr('name');      
      var fields = $(elem).children('td');      
      if(!activeParams[name])
        return;      
      $(fields[1]).children("select").val(activeParams[name]['operation']);      
      $(fields[2]).children('input').val(activeParams[name]['value']);
      $(fields[3]).children('select').val(activeParams[name]['logic']);      
    });
    var period = window.main.getPeriod();
    var bday = new Date(period.bday*1000);
    var eday = new Date(period.eday*1000);
    $("#filter-datepicker").DatePicker(
      { 
        flat: true,
        date: [bday,eday],
        calendars: 3,
        mode: "range",   
        starts: 1,
        onChange: function(formated) {     
          console.log(formated);
          var bd = new Date(formated[0]);
          var ed = new Date(formated[1]);
          console.log(bd,ed);
          $('#filter-date').val(bd.getTime()/1000+' and '+ed.getTime()/1000);
        },
      });

      $("#filter-date").val(bday.getTime()/1000+' and '+eday.getTime()/1000);  
      $("#filter-datepicker").children('div').css('width',"590px");
      $("#filter-datepicker").children('div').css('height',"160px");
      $("#filter-datepicker").children('div').css('background-color',"#000");
      $("#filter-datepicker").children('div').css('margin-left',"-300px");
      $("#filter-datepicker").children('div').css('left',"50%");
    return;
  }

  filter.show = function() {
    console.log("Show filter");    
    function onAnswer(data) {      
      data = JSON.parse(data);
      console.log(data);
      if(data.html) {
        $('#filter > div.modal-body').html(data.html);
        setupFilter();
        $('#filter').modal({show:true});
        return;
      }
    }
    main.ajax('filter','ajax_load_constructor',{project_id:project_id, page_id:main.selectedTabId}, onAnswer);
    return false;
  };

function constructParams(form){
  var filter = $(form).parent().parent();
  filter = $(filter).children('div.modal-body').eq(0).children('#filter-param').eq(0).children('tbody').eq(0);  
  var result = {};
  $(filter).children('tbody>tr').each(function(item,elem){
    var name = $(elem).attr('name');
    var fields = $(elem).children('td');
    var op = $(fields[1]).children('select').val();
    var value = $(fields[2]).children('input').val();
    var logic = $(fields[3]).children('select').val();
    result[name] = {operation:op,value:value,logic:logic};    
  });
  return result;
}

filter.setup = function(data,item) {  
  $(item).children('input').attr('checked','checked');
  activeParams = data;
  window.main.pageReload();
  return false;
};

filter.apply = function(form) {
  var params = constructParams(form);
  activeParams = params;  
  window.main.pageReload();
  $(form).parent().parent().modal('hide');
  return false;
};

filter.save = function(form) { 
  var params = constructParams(form);
  var name = $(form).parent().parent().children('div.modal-header').children('input').val();
  if(name=="") {
    $(form).addClass('disabled');
    return false;
  }  
  main.ajax('filter','ajax_save_filter',{project_id:project_id, filter:params, name: name});
  activeParams = params;  
  window.main.pageReload();
  $(form).parent().parent().modal('hide');
  return false;
};
  
  return filter;
}

window.filter = filter_funct(window);



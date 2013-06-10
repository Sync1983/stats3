function filter_funct(parent) {
  var filter = {};
  var activeParams = {};
  
  filter.getParams = function() {
    return JSON.stringify(activeParams);
  };

  filter.show = function() {
    console.log("Show filter");
    function onAnswer(data) {
      data = JSON.parse(data);
      if(data.html) {
        $('#filter > div.modal-body').html(data.html);
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

filter.apply = function(form) {
  var params = constructParams(form);
  activeParams = params;
  console.log(params);
  window.main.pageReload();
  return false;
};

filter.save = function(form) {  
  filter.apply(form);  
  return false;
};
  
  return filter;
}

window.filter = filter_funct(window);



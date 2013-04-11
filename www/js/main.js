
function main_funct(parent) {
  var main = {};
  
  if(parent.main)
    main = parent.main;
  
  main.ajax = function(controller,action,data,success) {
    var ajaxRequest = {};
    ajaxRequest.type = "POST";
    ajaxRequest.data = data;
    ajaxRequest.url = controller+'/'+action;
    ajaxRequest.success = success;    
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
                      data = JSON.parse(data);
                      if(data.error) {
                        dialog.children(".error").css('display','block');
                        dialog.children(".error").text(data.error);
                        return;
                      }                      
                      dialog.dialog('close');
                      //location.href = location.href;
                    });
                },
               },               
               modal: true
              });         
        });
      return false;
    }      
    return false;
  };

  return main;
}

window.main = main_funct(window);



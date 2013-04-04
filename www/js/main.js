
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
        document.location.href = '/'+data.redirect;
      }
      $('.errors').css('display','none');
      //$('#'+item).bPopup().close();
    });
    return false;
  };

  $().ajaxSend(main.showLoader);
  $().ajaxComplete(main.hideLoader);

  return main;
}

window.main = main_funct(window);



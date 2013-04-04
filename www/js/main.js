
function main_funct(parent) {
  
  parent.showLoader = function {
    $('-ammo-ajax-loader').attr('display','block');
  }

  function hideLoader() {
    $('-ammo-ajax-loader').attr('display','none');
  }

}

main_funct(window.main);



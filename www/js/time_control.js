var timeController = function() {
  this.stop_time  = (new Date()).getTime()/1000;
  this.start_time = (new Date((this.stop_time-30*86400)*1000)).getTime()/1000;
  
  function selectLastWeek() {
    var date_stop   = new Date();
    var date_start  = new Date(date_stop.getTime()-6*86400000);    
    this.start_time = (date_start.getTime()+3*3600)/1000;
    this.stop_time  = (date_stop.getTime()+3*3600)/1000;
  };

  function selectLastMounth() {    
    var date_stop   = new Date();
    var date_start  = new Date(date_stop.getTime()-30*86400000);    
    this.start_time = (date_start.getTime()+3*3600)/1000;
    this.stop_time  = (date_stop.getTime()+3*3600)/1000;
  };

  function selectAllTime() {    
    var date_stop   = new Date();
    this.start_time = 0;
    this.stop_time  = date_stop.getTime()/1000;
  };

  function showControl() {    
    $('.time-text').css('display','block');
  };

  function updateText () {    
    var start = $.datepicker.formatDate('dd-mm-yy', new Date((this.start_time)*1000));    
    var stop = $.datepicker.formatDate('dd-mm-yy', new Date(this.stop_time*1000));
    $("#time-button").html("Время: <span style=\"color:white;\">"+start+" : "+stop+"</span><b class=\"caret\"></b>");
  };

  this.changeStart = function (param) {
    this.start_time = (param.getTime()/1000)+9*3600+10;    
    console.log(this.start_time);
  };

  this.changeStop = function (param) {
    this.stop_time  = (param.getTime()/1000)+9*3600+10;    
  };
  
  this.selectPeriod = function (item) {
    var type = $(item).children('input').val();
    $(item).children('input').attr('checked','checked');
    $('.time-text').css('display','none');
    if(type==="time-week")
      selectLastWeek();
    else if(type==="time-mounth")
      selectLastMounth();
    else if(type==="time-2mounth")
      selectAllTime();
    else 
      showControl();
    
    $('#time-filter').dropdown('toggle');    
    return false;
  };

  this.applyPeriod = function () {
    updateText();
    window.main.pageReload();
    return false;
  };

  this.getPeriod = function () {    
    var start = Math.floor(this.start_time);
    var stop = Math.floor(this.stop_time);
    return {bday:start,eday:stop};
  };

  this.updateHeader = function() {
    updateText();
  };

  return this;
};

window.timeController = timeController();



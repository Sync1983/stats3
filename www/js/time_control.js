var timeController = function() {
  this.stop_time  = (new Date()).getTime()/1000;
  this.start_time = (new Date((this.stop_time-30*86400)*1000)).getTime()/1000;
  
  function selectLastWeek() {
    var date_stop   = new Date();
    var date_start  = new Date(date_stop.getTime()-7*86400000);    
    this.start_time = date_start.getTime()/1000;
    this.stop_time  = date_stop.getTime()/1000;
  };

  function selectLastMounth() {    
    var date_stop   = new Date();
    var date_start  = new Date(date_stop.getTime()-30*86400000);    
    this.start_time = date_start.getTime()/1000;
    this.stop_time  = date_stop.getTime()/1000;
  };

  function selectAllTime() {    
    var date_stop   = new Date();
    this.start_time = 0;
    this.stop_time  = date_stop.getTime()/1000;
  };

  function showControl() {
    
  };

  function updateText () {
    var date = new Date(this.start_time*1000);
    var start = date.getDate()+"-"+(date.getMonth()+1)+"-"+date.getFullYear();
    date = new Date(this.stop_time*1000);
    var stop = date.getDate()+"-"+(date.getMonth()+1)+"-"+date.getFullYear();
    $("#time-button").html("Время: <span style=\"color:white;\">"+start+" : "+stop+"</span><b class=\"caret\"></b>");
  };
  
  this.selectPeriod = function (item) {
    var type = $(item).children('input').val();
    $(item).children('input').attr('checked','checked');
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
    var start = this.start_time/86400;
    var stop = this.stop_time/86400;
    return {bday:(Math.floor(start)*86400),eday:(Math.floor(stop)*86400)};
  };

  return this;
};

window.timeController = timeController();



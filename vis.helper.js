var view =
{
  start:0,
  end:0,
  fixinterval:false,
  graphDisplayOffsetStart:0,
  graphDisplayOffsetEnd:0,

  'zoomout':function ()
  {
    var time_window = this.end - this.start;
    var middle = this.start + time_window / 2;
    time_window = time_window * 2;
    this.start = middle - (time_window/2);
    this.end = middle + (time_window/2);
    this.calc_interval();
  },

  'zoomin':function ()
  {
    var time_window = this.end - this.start;
    var middle = this.start + time_window / 2;
    time_window = time_window * 0.5;
    this.start = middle - (time_window/2);
    this.end = middle + (time_window/2);
    this.calc_interval();
  },

  pan(direction, requestType)
  {
    var shiftsize;
    if (direction!=="left" && direction!=="right") {return(false);}

    if (requestType==="monthly") {
      var start=new Date(this.start);
      var end=new Date(this.end);
      var numMonths = end.getMonth() - start.getMonth() + (12 * (end.getFullYear() - start.getFullYear()));
      shiftsize = Math.round(numMonths*0.2);

      if (shiftsize===0) { shiftsize=1; }
      if (direction==="left") { shiftsize=-shiftsize; }

      var y = start.getFullYear(), m = start.getMonth();
      this.start = new Date(y, m + shiftsize, 1).getTime();
      y = end.getFullYear(), m = end.getMonth();
      this.end = new Date(y, m + shiftsize, 1).getTime();
      return;
    }

    var numIntervals = (this.end - this.start) / ( this.interval * 1000);
    var shiftInervals = Math.round(numIntervals * 0.2);
    shiftsize=(shiftInervals===0 ? 1 : shiftInervals) * this.interval * 1000;
    if (direction==="left") { shiftsize=-shiftsize; }

    this.start += shiftsize;
    this.end += shiftsize;
    this.calc_interval();
  },

  'timewindow':function(time)
  {
    this.start = ((new Date()).getTime())-(3600000*24*time);	//Get start time
    this.end = (new Date()).getTime();	//Get end time
    this.calc_interval();
  },
  
  'calc_interval':function()
  {
    var npoints = 600;
    var interval = Math.round(((this.end - this.start)*0.001)/npoints);
    this.graphDisplayOffsetStart = 0;
    this.graphDisplayOffsetEnd = 0;

    if (!this.fixinterval) {
      var outinterval = 5;
      if (interval>10) outinterval = 10;
      if (interval>15) outinterval = 15;
      if (interval>20) outinterval = 20;
      if (interval>30) outinterval = 30;
      if (interval>60) outinterval = 60;
      if (interval>120) outinterval = 120;
      if (interval>180) outinterval = 180;
      if (interval>300) outinterval = 300;
      if (interval>600) outinterval = 600;
      if (interval>900) outinterval = 900;
      if (interval>1200) outinterval = 1200;
      if (interval>1800) outinterval = 1800;
      if (interval>3600*1) outinterval = 3600*1;
      if (interval>3600*2) outinterval = 3600*2;
      if (interval>3600*3) outinterval = 3600*3;
      if (interval>3600*4) outinterval = 3600*4;
      if (interval>3600*5) outinterval = 3600*5;
      if (interval>3600*6) outinterval = 3600*6;
      if (interval>3600*12) outinterval = 3600*12;
      if (interval>3600*24) outinterval = 3600*24;

      this.interval = outinterval;
    }

    var intervalms = this.interval * 1000;

    if (requesttype === "monthly") {
      var startDate = new Date(this.start), y = startDate.getFullYear(), m = startDate.getMonth();
      this.start = new Date(y, m, 1).getTime();

      var endDate = new Date(this.end), y = endDate.getFullYear(), m = endDate.getMonth(), d = endDate.getDate();
      if (d!==1) { this.end = new Date(y, m + 1, 1).getTime(); }
    } else {
      this.start = Math.floor(this.start / intervalms) * intervalms;
      this.end = Math.ceil(this.end / intervalms) * intervalms;

      if (requesttype === "weekly") {
        var endDate = new Date(this.start), d = endDate.getDay();
        if (d!==0) {
          this.graphDisplayOffsetStart = 60*60*24*3*1000;
          this.graphDisplayOffsetEnd = 60*60*24*4*1000;
        }
      }
    }
  }
}

function stats(data)
{
    var sum = 0;
    var i=0;
    var minval = 0;
    var maxval = 0;
    var npoints = 0;
    var npointsnull = 0;
    
    var val = null;
    for (var z in data)
    {
        // var val = data[z][1];                   // 1) only calculated based on present values
        if (data[z][1]!=null) val = data[z][1];    // 2) if value is missing use last value
        if (val!=null) 
        {
            if (i==0) {
                maxval = val;
                minval = val;
            }
            if (val>maxval) maxval = val;
            if (val<minval) minval = val;
            sum += val;
            i++;
        }
        if (data[z][1]==null) npointsnull++;
        
        npoints ++;
    }
    var mean = sum / i;
    sum = 0, i=0;
    for (z in data)
    {
        sum += (data[z][1] - mean) * (data[z][1] - mean);
        i++;
    }
    var stdev = Math.sqrt(sum / i);
    
    return {
        "minval":minval,
        "maxval":maxval,
        "diff":maxval-minval,
        "mean":mean,
        "stdev":stdev,
        "npointsnull":npointsnull,
        "npoints":npoints
    };
};

// http://stackoverflow.com/questions/901115/how-can-i-get-query-string-values/901144#901144
var urlParams;
(window.onpopstate = function () {
    var match,
        pl = /\+/g, // Regex for replacing addition symbol with a space
        search = /([^&=]+)=?([^&]*)/g,
        decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
        query = window.location.search.substring(1);

    urlParams = {};
    while (match = search.exec(query))
       urlParams[decode(match[1])] = decode(match[2]);
})();

function tooltip(x, y, contents, bgColour, position)
{
    var offset = 15; // use higher values for a little spacing between `x,y` and tooltip
    var elem = $('<div id="tooltip">' + contents + '</div>').css({
        position: 'absolute',
        display: 'none',
        'font-weight':'bold',
        border: '1px solid rgb(255, 221, 221)',
        padding: '2px',
        'background-color': bgColour,
        opacity: '0.8'
    }).appendTo("body").fadeIn(200);

    var elemY = y - elem.height() - offset;
    var elemX = x - elem.width()  - offset;
    if (elemY < position.top) { elemY = position.top; }
    if (elemX < position.left) { elemX = position.left; }
    elem.css({
        top: elemY,
        left: elemX
    });
};

function parseTimepickerTime(timestr){
    var tmp = timestr.split(" ");
    if (tmp.length!=2) return false;

    var date = tmp[0].split("/");
    if (date.length!=3) return false;

    var time = tmp[1].split(":");
    if (time.length!=3) return false;

    return new Date(date[2],date[1]-1,date[0],time[0],time[1],time[2],0).getTime() / 1000;
}

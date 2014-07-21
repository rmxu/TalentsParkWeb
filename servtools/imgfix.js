
function fixImage(i,w,h){
  var ow = i.width;
    var oh = i.height;
    var rw = w/ow;
    var rh = h/oh;
    var r = Math.min(rw,rh);
    if (w ==0 && h == 0){
        r = 1;
    }else if (w == 0){
        r = rh<1?rh:1;
    }else if (h == 0){
        r = rw<1?rw:1;
    }
    if (ow!=0 && oh!=0){
    i.width = ow * r;
      i.height = oh * r;
    }else{
      var __method = this, args = $A(arguments);
        window.setTimeout(function() {
          fixImage.apply(__method, args);
        }, 200);
    }
    i.onload = function(){}
}

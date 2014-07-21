    function reinitIframeRemind(){
	    iframe = document.getElementById("remind");
	    try{
	    var bHeight = iframe.contentWindow.document.body.scrollHeight;
	    var dHeight = iframe.contentWindow.document.documentElement.scrollHeight;
	    var height = Math.max(bHeight, dHeight);
	    if(height<25){
	    	 height=0;
	    }
	    iframe.height =  height;
	    }catch (ex){}
    }

    window.setInterval("reinitIframeRemind()", 200);
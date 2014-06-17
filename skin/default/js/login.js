		
function setUserTmpUiid() {
      if(getCookieData("UserIId")!="")
      {  
         document.getElementById("login_email").value=getCookieData("UserIId");
         document.getElementById("tmpiId").checked=true;
      }
}

function getCookieData(label){
  	var labelLen=label.length;
  	var cLen=document.cookie.length;
  	var i=0;
  	var cEnd;
 	while(i<cLen){
 		var j=i+labelLen;
  		if(document.cookie.substring(i,j)==label){
    		cEnd=document.cookie.indexOf(";",j);
    		if(cEnd==-1){
     			cEnd=document.cookie.length;
    		}
    		return document.cookie.substring(j+1,cEnd);
  		}
  		i++
 	}
 	return ""
}

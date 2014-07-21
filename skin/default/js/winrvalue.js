function getDiaValue(obj,obj1,obj2,obj3,url,width,height){
	var myform=document.forms["myform"];
	ret=window.showModalDialog(url,obj,"DialogHeight:"+height+"px;dialogWidth:"+width+"px;status=0;titlebar=false;help:no;scroll:no");
		
  	if(ret==undefined||ret==""){
      	return obj.value;    
  	}else{
		treedatatemp= new Array();
     	treedatatemp=ret.split(",");    	
     	obj1.value=treedatatemp[0];
     	obj2.value=treedatatemp[1];
     	obj3.value=treedatatemp[2];
     	return treedatatemp[1]+" "+treedatatemp[2];
   } 
}
//定义$函数
var $ = function (id) {
	return "string" == typeof id ? document.getElementById(id) : id;
}
function folden(){
	if($('separator')){
		$('separator').onclick = function (){
			document.body.className = (document.body.className == 'folden')?'':'folden';
		}
	}
}

function nTabs(){
	var allElements = document.getElementsByTagName('ul');
	for(i=0;i<allElements.length;i++){
		if(allElements[i].className == 'menu'){
			var childElements = allElements[i].getElementsByTagName('li');
			for(var j=0;j<childElements.length;j++){
				childElements[j].onclick = changeStyle;
			}
		}
	}
}
function changeStyle(){
	var tagList = this.parentNode;
	var tagOptions = tagList.getElementsByTagName("li");
	for(i=0;i<tagOptions.length;i++){
		if(tagOptions[i].className.indexOf('active')>=0){
			tagOptions[i].className = '';
		}
	}
	this.className = 'active';
}

function inputTxt(obj,act){
	var str="输入姓名...";
	if(obj.value==''&&act=='set')
	{
		obj.value=str;
		obj.style.color="#cccccc"
	}
	if(obj.value==str&&act=='clean')
	{
		obj.value='';
		obj.style.color="#666666"
	}
}
function setMenuShow(isShow)
{
	var setMenu = document.getElementById('set_menu');
	var setMenuBridge = document.getElementById('set_menu_bridge');
	if(setMenu)
	{
		if(isShow)
		{
			setMenuBridge.style.display = '';
			setMenu.style.display = '';
		}
		else
		{
			setMenu.style.display = 'none';
			setMenuBridge.style.display = 'none';
		}
	}
}
function apMenuShow(isShow)
{
	var apMenu = document.getElementById('ap_menu');
	if(apMenu)
	{
		if(isShow)
		{
			apMenu.style.display = '';
		}
		else
		{
			apMenu.style.display = 'none';
		}
	}
}
function reinitIframe(){
  	var iframe = $("frame_content");
    try{
		var bHeight = iframe.contentWindow.document.body.scrollHeight;
		var dHeight = iframe.contentWindow.document.documentElement.scrollHeight;
		var height = Math.max(bHeight, dHeight);
		iframe.height =  height +'px';
		if(navigator.appName == "Microsoft Internet Explorer"){
          	 if(navigator.appVersion.match(/6./i)=='6.'){
                  $('mainpart').style.height = height +'px';
          	 }
		}
    }catch (ex){}
}

window.setInterval("reinitIframe()", 200);

window.onload = folden;
window.onload = nTabs;

function addLoadEvent(func){
	var oldonload=window.onload;
	if(typeof window.onload!="function"){window.onload=func;}else{window.onload=function(){oldonload();func();}};
}
addLoadEvent(folden);
addLoadEvent(nTabs);
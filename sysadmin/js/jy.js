//定义$函数
var $ = function (id) {
	return "string" == typeof id ? document.getElementById(id) : id;
}
function folden(){
	if($('separator'))
	{
		$('separator').onclick = function ()
		{
			if(document.body.className == 'folden')
			{
				parent.document.getElementById('BoardTitle').style.width = '200px';
				parent.document.getElementById('frmTitle').style.width = '200px';
				document.body.className = '';
			}
			else
			{
				parent.document.getElementById('BoardTitle').style.width = '60px';
				parent.document.getElementById('frmTitle').style.width = '60px';
				document.body.className = 'folden';
			}
		}
	}
}
function nTabs(){
	var allElements = parent.document.getElementsByTagName('ul');
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
function changeLeftMenu(obj){
	var tagList = obj.parentNode;
	var tagOptions = tagList.getElementsByTagName("li");
	var tagOptionsLen = tagOptions.length;
	for(i=0;i<tagOptionsLen;i++){
		if(tagOptions[i].className.indexOf('active')>=0){
			tagOptions[i].className = '';
		}
	}
	obj.className = 'active';
}
//window.onload = folden;
window.onload = nTabs;

function addLoadEvent(func){
var oldonload=window.onload;
if(typeof window.onload!="function"){window.onload=func;}else{window.onload=function(){oldonload();func();}};
}
addLoadEvent(folden);
addLoadEvent(nTabs);
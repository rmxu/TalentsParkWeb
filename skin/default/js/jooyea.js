
var isIE = (document.all) ? true : false;

function trim(str){
	return str.replace(/(^\s*)|(\s*$)|(　*)/g , "");
}

function getEvent(){
	if(document.all){
		return window.event;
	}
	func=getEvent.caller;
	while(func!=null){
		var arg0=func.arguments[0];
		if(arg0){
			if((arg0.constructor==Event || arg0.constructor ==MouseEvent) || (typeof(arg0)=="object" && arg0.preventDefault && arg0.stopPropagation)){
				return arg0;
			}
		}
		func=func.caller;
	}
	return null;
}

function GET_TOP_URL(){
	return "http://"+location.host+location.pathname+location.search;
}

function Get_mouse_position(){
	var event=getEvent();
	if(navigator.appName=='Microsoft Internet Explorer'){
		return [event.x+document.documentElement.scrollLeft,event.y+document.documentElement.scrollTop];
	}else if(navigator.appName=='Netscape'){
		return [event.pageX,event.pageY];
	}
}

function InitXMLDOM(){
  if(window.ActiveXObject){
		var oXmlDom = new ActiveXObject("Microsoft.XMLDOM");
	}else{
		var oXmlDom = document.implementation.createDocument("","",null);
	}
	return oXmlDom;
}

var $ = function (id) {
	return "string" == typeof id ? document.getElementById(id) : id;
};

function toggle(targetid,type_id,mod_id){
	var target=$(targetid+"_"+type_id+"_"+mod_id);
	if(target.style.display==""){
		$("openreply_"+type_id+"_"+mod_id).innerHTML = "回复";
		target.style.display="none";
	}else{
		$("openreply_"+type_id+"_"+mod_id).innerHTML = "收起回复";
		target.style.display="";
	}
}
var settimeouti = '';
var togglestatu = true;
function toggle2(targetid){
	var target=$(targetid+'_2');
	var target2=$(targetid+'_1');
	var input2=$(targetid+'_input');

	if (target.style.display=="none"){
		target.style.display="";
		target2.style.display="none";
		input2.focus();
	} else {
		if(input2.value=='') {
			togglestatu = true;
			settimeouti = setTimeout("toggleshow_1('"+targetid+"')",500);
		}
	}
}

function toggleshow_1(targetid) {
	if(togglestatu) {
		var target=$(targetid+'_2');
		var target2=$(targetid+'_1');
		var input2=$(targetid+'_input');
		target.style.display="none";
		target2.style.display="";
		$('face_list_menu').style.display = 'none';
	}
}

function showim() {
	togglestatu = false;
	// 显示表情操作。。。
}

//侧边栏
function showDiv(){
	document.body.className = '';
}

function hiddenDiv(){
	document.body.className = 'folden';
}

//feed按钮
function feed_menu(feedid, show) {
	var obj = $('a_feed_menu_'+feedid);
	if(obj) {
		if(show) {
			obj.style.display='block';
		} else {
			obj.style.display='none';
		}
	}
}

//feed菜单
function ajaxmenu(obj,ctrlid,uid,type_id) {
	var div = $(ctrlid + '_menu');
	var parentnode = $('append_parent');
	var divs = parentnode.childNodes;
	var t = obj.offsetTop;
    var l = obj.offsetLeft;
	var height=obj.offsetHeight;
    var width=obj.offsetWidth;
    while(obj=obj.offsetParent) {
        t+=obj.offsetTop;
        l+=obj.offsetLeft;
    }
	for(i=0;i<divs.length;i++){
		if(divs[i].nodeType == 1){
			parentnode.childNodes[i].style.display = 'none';
		}
	}
	if(!div) {
		div = document.createElement('div');
		div.id = ctrlid + '_menu';
		div.className = 'poplayer';
		div.style.top = (t + 22) + 'px';
		div.style.left = (l - 198) + 'px';
		div.style.zIndex = 100;
		div.innerHTML = "<h1>屏蔽动态</h1><div class=\'popupmenu_inner\' id=\'content_hidden\'><p>在下次浏览时不再显示此类动态</p><p class=\'btn_line\'><input type=\'radio\' style=\'vertical-align:middle\'' checked=\'\' name=\'hidden_type\' value="+uid+" /><label> 仅屏蔽该好友信息</label><br/><input type=\'radio\' style=\'vertical-align:middle\' value="+type_id+" name=\'hidden_type\' /><label> 屏蔽该类别信息</label><br/><button class=\'button\' value=\'true\' type=\'button\' onclick=hidden_affair();>确定</button><button id="+ctrlid+"_del class=\'button\' value=\'true\' type=\'button\' onclick=hidden_affair();>取消</button></p></div>";
		$('append_parent').appendChild(div);
	}
	div.style.display = '';
	var del_bt = $(ctrlid+'_del');
	if(del_bt){
		del_bt.onclick = function(){div.style.display = 'none';}
	}
}
//屏蔽动态
function hidden_affair_callback(content){
	if(content=='success'){
		parent.Dialog.alert('设置成功');
		window.location.reload();
	}else{
		parent.Dialog.alert(content);
	}
}
function hidden_affair(){
obj=document.getElementsByName("hidden_type");
if(obj[0].checked){
	var hidden_value=obj[0].value;
	var t_hidden=0;
}else{
	var hidden_value=obj[1].value;
	var t_hidden=1;
}
var hidden_affair=new Ajax();
hidden_affair.getInfo("do.php","GET","app","act=pr_affair&type="+t_hidden+"&hidden_value="+hidden_value+"&is_del=0",function(c){hidden_affair_callback(c);}); 
}

//search
function showBox(id){
	for(i=0;i<3;i++){
		if(i!=id){
			$('showssBox_'+i).style.display = 'none';
		}
		$('showssBox_'+id).style.display = ($('showssBox_'+id).style.display == 'none')?'block':'none';
	}
}
//setArea
function setArea(){
	if($('q_city').value == ''){
		if($('q_province').value == ''){
			$('area').innerHTML = '不限';
		}else{
			$('area').innerHTML = $('q_province').value;
		}
	}else if($('q_city').value == '不限'){
		$('area').innerHTML = $('q_province').value;
	}else{
		$('area').innerHTML = $('q_city').value;
	}
	$('showssBox_0').style.display = 'none';
}
//setAge
function setAge(){
	$('age_begin').value = $('select_age_b').value;
	$('age_end').value = $('select_age_e').value;
	if($('age_begin').value == $('age_end').value){
		$('age').innerHTML = $('age_begin').value;
	}else{

		$('age').innerHTML = $('age_begin').value + '-' + $('age_end').value;
	}
	$('showssBox_1').style.display = 'none';
}
//setgender
function setGender(obj){
	$('gender').innerHTML = obj.innerHTML;
	$('showssBox_2').style.display = 'none';
}
//showFace
function showFace(obj,name,target){
	$(name).style.display = 'none';
	var t=obj.offsetTop;
    var l=obj.offsetLeft;
	var height=obj.offsetHeight;
    var width=obj.offsetWidth;
    while(obj=obj.offsetParent) {
        t+=obj.offsetTop;
        l+=obj.offsetLeft;
    }
	if(target == 'mood_txt' || target == 'mood'){
		$(name).style.top = (t+41) + 'px';
		$(name).style.left = l + 'px';
		$(target).focus();
		togglestatu = true;
	}
	else{
		if(target == 'msgboard')
		{
			$(name).style.top = (t-5) + 'px';
			$(name).style.left = l + 'px';
			$(target).focus();
			togglestatu = true;
		}
		else
		{
			$(name).style.top = (t+26) + 'px';
			$(name).style.left = (l-216) + 'px';
			$(target).focus();
			togglestatu = false;
	    }
	}
	$(name).style.display = '';
	$(name).innerHTML = "<div class=\'emItem\' onmouseover=addClass(this,\'emAct\') onmouseout=removeClass(this,\'emAct\') onclick=insertFace(\'"+name+"\',1,\'"+target+"\') title=\'微笑\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',2,\'"+target+"\') title=\'呲牙笑\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',3,\'"+target+"\') title=\'偷笑\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',4,\'"+target+"\') title=\'吐舌\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',5,\'"+target+"\') title=\'色迷迷\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',6,\'"+target+"\') title=\'害羞\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',7,\'"+target+"\') title=\'耍酷\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',8,\'"+target+"\') title=\'晕\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',9,\'"+target+"\') title=\'疑惑\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',10,\'"+target+"\') title=\'-_-|||\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',11,\'"+target+"\') title=\':(\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',12,\'"+target+"\') title=\'不满\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',13,\'"+target+"\') title=\'吃惊\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',14,\'"+target+"\') title=\'不明白\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',15,\'"+target+"\') title=\'生气\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',16,\'"+target+"\') title=\'暴怒\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',17,\'"+target+"\') title=\'睡觉\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',18,\'"+target+"\') title=\'闭嘴\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',19,\'"+target+"\') title=\'衰\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',20,\'"+target+"\') title=\'猪头\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',21,\'"+target+"\') title=\'红心\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',22,\'"+target+"\') title=\'心碎\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',23,\'"+target+"\') title=\'鲜花\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',24,\'"+target+"\') title=\'花谢\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',25,\'"+target+"\') title=\'晚安\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',26,\'"+target+"\') title=\'拍砖\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',27,\'"+target+"\') title=\'钱\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',28,\'"+target+"\') title=\'便便\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',29,\'"+target+"\') title=\'呕吐\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',30,\'"+target+"\') title=\'扁你\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',31,\'"+target+"\') title=\'好\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',32,\'"+target+"\') title=\'鼓掌\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',33,\'"+target+"\') title=\'支持你\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',34,\'"+target+"\') title=\'鄙视你\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',35,\'"+target+"\') title=\'服了\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',36,\'"+target+"\') title=\'胜利\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',37,\'"+target+"\') title=\'ok\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',38,\'"+target+"\') title=\'握手\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',39,\'"+target+"\') title=\'抱抱\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',40,\'"+target+"\') title=\'抽你\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',41,\'"+target+"\') title=\'亲一个\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',42,\'"+target+"\') title=\'委屈\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',43,\'"+target+"\') title=\'痛哭\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',44,\'"+target+"\') title=\'惊叫\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',45,\'"+target+"\') title=\'抓狂\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',46,\'"+target+"\') title=\'烧香\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',47,\'"+target+"\') title=\'期待\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',48,\'"+target+"\') title=\'喊话\'></div><div class=\'emItem\' onmouseover=addClass(this,'emAct') onmouseout=removeClass(this,'emAct') onclick=insertFace(\'"+name+"\',49,\'"+target+"\') title=\'爽歪歪\'></div>"	;
}
//add、remove Class
var oldclassname = '';
function addClass (obj,newClass){
	oldclassname = obj.className;
	obj.className = obj.className + ' ' +newClass;
}
function removeClass(obj,newclass){
	obj.className = oldclassname;
}
//insertFace
var timeout;
function insertFace (name,id,target){
	
	//当前用户可输入字符小于7时，则不允许插入表情
	if(document.getElementById('msgboard')){
		var ta_num_messageboard = document.getElementById('msgboard').value.length;
		if(ta_num_messageboard>143){
			parent.Dialog.alert("很抱歉，您的留言板剩余可输入字数不足以插入表情了。");
			return;
		}
	}
	if(document.getElementById('mood_txt')){
		var ta_num_status = document.getElementById('mood_txt').value.length;
		if(ta_num_status>143){
			parent.Dialog.alert("很抱歉，您的状态剩余可输入字数不足以插入表情了。");
			return;
		}
	}
	
	togglestatu = false;
	if(target == 'mood_txt'){
		$(name).style.display = '';
		clearTimeout(timeout);
	}else{
		$(name).style.display = 'none';
	}
	$(target).style.display = '';
	$(target).focus();
	var faceText = '[em_'+id+']';
	if($(target) != null) {
		$(target).value += faceText;
	}
}

function show(obj,t_time){
	timeout = setTimeout("hidden_obj('"+obj+"')",t_time);
}
/* 1128*/

function reSetIframeHeight(){
	var iObj=parent.document.getElementById('frame_content');
	iObj.height='100px';
	parent.reinitIframe();
}

//最大字符量限制
function isMaxLen(o){
  var nMaxLen=o.getAttribute? parseInt(o.getAttribute("maxlength")):"";
  if(o.getAttribute && o.value.length>nMaxLen){
  	o.value=o.value.substring(0,nMaxLen)
  }
}

//批量选择
	function select_attach(type_value){
		var mail_array=document.getElementsByName('attach[]');
		var num=mail_array.length;
		is_checked='checked';
		if(type_value==0){
			is_checked='';
		}
		for(array_length=0;array_length<num;array_length++){
				mail_array[array_length].checked=is_checked;
		}
	}

//显示容器内容
	function show_obj(obj){
		$(obj).style.display='';
	}

//隐藏容器内容
	function hidden_obj(obj){
		$(obj).style.display='none';
	}

//操作cookie
function get_cookie(cookie_value){
	var	aCookie=document.cookie.split(";");
	for(var i=0;i < aCookie.length;i++){
		var aCrumb=aCookie[i].split("=");
		if(cookie_value==aCrumb[0])
			return unescape(aCrumb[1]);
	}
	return '';
}

//提取字符串中的js执行
function pick_script(content){
	content.replace(/<script>([^<]+)<\/script>/g,function($whole,$match){eval($match);});
}

//添加编辑器图片
function AddContentImg(ImgName,classId){
	var obj = document.getElementById("CONTENT").previousSibling.children[0];
	obj.innerHTML = obj.innerHTML + "<br><IMG src='./"+ImgName+"' onload='fixImage(this,420,0)' classId="+classId+" /><br>";
}

//清理表单指定数据
function clear_def(obj,str){
	var obj_num=obj.children.length;
	var reg=eval("/"+str+"/g");
	for(i=0;i<obj_num;i++){
		if(obj.children[i].value){
			obj.children[i].value=obj.children[i].value.replace(reg,"");
		}
	}
}

//报错机制拦截
function error_fun(content){
	var exp_str=/error:/;
	if(exp_str.test(content)){
		var return_array=content.split(":");
		var error_str = (return_array[1]=='') ? '操作发生错误' : return_array[1];
		Dialog.alert(error_str);
		return true;
	}
}

//ajax回调函数
function get_com_callback(content,type_id,mod_id){
	if(content&&frame_content.$("show_"+type_id+"_"+mod_id)){
		frame_content.$("show_"+type_id+"_"+mod_id).style.display='';
		var history_com=frame_content.$("show_"+type_id+"_"+mod_id).innerHTML;
		frame_content.$("show_"+type_id+"_"+mod_id).innerHTML=history_com+content;
	}
}

function restore_com_callback(content,type_id,mod_id){
	if(error_fun(content)){
		return false;
	}
	if(frame_content.$('max_'+type_id+'_'+mod_id)){
		var max_num=frame_content.$('max_'+type_id+'_'+mod_id).innerHTML;
		var total_num=frame_content.$('total_'+type_id+'_'+mod_id).innerHTML;
		frame_content.$('max_'+type_id+'_'+mod_id).innerHTML=parseInt(max_num)+1;
		frame_content.$('total_'+type_id+'_'+mod_id).innerHTML=parseInt(total_num)+1;
	}
	frame_content.$('reply_'+type_id+'_'+mod_id+'_input').value='';
	frame_content.$('reply_'+type_id+'_'+mod_id+'_input').onblur;
	var history_com=frame_content.$("show_"+type_id+"_"+mod_id).innerHTML;
	frame_content.$("show_"+type_id+"_"+mod_id).style.display='';
	frame_content.$("show_"+type_id+"_"+mod_id).innerHTML=content+history_com;
	$('restore').value='';
	if(frame_content.$('num_'+type_id+'_'+mod_id)){
		var max_num=frame_content.$('num_'+type_id+'_'+mod_id).innerHTML;
		frame_content.$('num_'+type_id+'_'+mod_id).innerHTML=parseInt(max_num)+1;
	}
}

function del_com_callback(content,type_id,parent_id,com_id){
	if(frame_content.$('max_'+type_id+'_'+parent_id)){
			var max_num=frame_content.$('max_'+type_id+'_'+parent_id).innerHTML;
			var total_num=frame_content.$('total_'+type_id+'_'+parent_id).innerHTML;
		if(max_num==1 && frame_content.$("show_"+type_id+"_"+parent_id)){
			frame_content.$("show_"+type_id+"_"+parent_id).style.display='none';
		}else{
			frame_content.$('max_'+type_id+'_'+parent_id).innerHTML=parseInt(max_num)-1;
			frame_content.$('total_'+type_id+'_'+parent_id).innerHTML=parseInt(total_num)-1;
		}
	}
	if(frame_content.$('num_'+type_id+'_'+parent_id)){
		var max_num=frame_content.$('num_'+type_id+'_'+parent_id).innerHTML;
		frame_content.$('num_'+type_id+'_'+parent_id).innerHTML=parseInt(max_num)-1;
	}
	if(content=="success"){
		frame_content.$("record_"+type_id+"_"+com_id).style.display='none';
	}else{
		Dialog.alert(content);
	}
}

function act_report_callback(content){
	if(content=="true"){
		Dialog.alert('举报成功');
	}else{
		Dialog.alert(content);
	}
}

function act_share_callback(content,share_type){
  if(content=="success"){
  	Dialog.alert("分享成功");
  	if(share_type==5||share_type==6||share_type==7){
  		window.frames['frame_content'].location.reload();
  	}
  }else{
  	Dialog.alert(content);
  }
}

//图片报错机制
function pic_error(pic){
	pic.src='skin/default/jooyea/images/error.gif';
}

function leave_message(user_name,user_id){
	frame_content.$("msgboard").focus(); 
	frame_content.$("msgboard").value="回复"+user_name+":";
	frame_content.$("user_id").value=user_id;
}

//计算字符字节数
function check_code_size(string_data){
	var chars = 0;	//字节数变量
	for (var i=0; i < string_data.length; i++) {
		var charinit = string_data.charCodeAt(i);
		if((charinit >= 0x0001 && charinit <= 0x007e) || (0xff60<=charinit && charinit<=0xff9f)) {
			chars++;	//单字节加1
		}else{
			chars+=2;	//双字节加2
		}
	}
	return chars;
}
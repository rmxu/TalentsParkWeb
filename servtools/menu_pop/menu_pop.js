current_menu_info=new Array();
function menu_pop_show(e,obj,t){
		if(typeof(menu_pop_data)=="object")
		{
			if(!document.getElementById("menu_top_yj"))create_menu_top(menu_pop_data);
			if(document.all)e = event || window.event;
				if(undefined!=obj.getAttributeNode('id'))
				{
					if(current_menu_info.toString()!=obj.id.split(":").toString())
					{
						current_menu_info =obj.id.split(":");
						changeGroup();
					}
				}
				if(!arguments[2]){
					t=2;
				}else{
					if(document.all){
						t=1;
					}else{
						t=0;
					}
				}
			  if(e.button==t){
				   var right_key_div = document.getElementById("menu_top_yj");
				   right_key_div.style.display="block";  
				   right_key_div.style.left= e.clientX + 'px';
				   if(e.clientX+right_key_div.offsetWidth>document.body.offsetWidth)right_key_div.style.left= (e.clientX-right_key_div.offsetWidth) + 'px';
				   right_key_div.style.top = e.clientY + 'px';
				   document.getElementById("menu_top_pop_layer_box").style.display='none';
			  }else{
					return;
			  } 
		}
	}
	function menu_pop_changeBg(obj){
		obj.className = 'hover';
	}
	function menu_pop_removeBg(obj){
		obj.className = '';
	}
	
function menu_pop_show_group(obj,id)
{
	parentNode=document.getElementById("menu_top_pop_layer_box");
	var menu_top_yj=document.getElementById("menu_top_yj");
	var win=document.getElementById('pop_layer_'+id);
	parentNode.style.display='';
	win.className = 'menu_top_pop_layer_2';
	var t=menu_top_yj.offsetTop+obj.offsetTop;
	var l=menu_top_yj.offsetLeft+menu_top_yj.offsetWidth;
	if((l+172)>(document.body.offsetWidth))l=menu_top_yj.offsetLeft-menu_top_yj.offsetWidth+9;
	win.style.top = t + 'px';
	win.style.left = l + 'px';
	var divs = parentNode.childNodes;
	for(var i=0;i<divs.length;i++){
		divs[i].style.display='none';
			if(divs[i].id==win.id){
				divs[i].style.display = '';
			}	
	}
}
function create_menu_top(data)
{
	var tatal_users=document.createElement("DIV");
	tatal_users.display="none";
	var num=0;
	var pop_str='<div class="top"><h4>仅自己<input class="checkbox" name="all"  type="checkbox" value="all" onclick="selectFriend(this)"/></h4><h4>好友可见<input class="checkbox" name="friends"  type="checkbox" onclick="selectFriend(this)"/></h4></div><div class="ct"><ul>';
	for(var i=0;i<data.length;i++)
	{
		pop_str+='<li onmouseover="menu_pop_changeBg(this) ; menu_pop_show_group(this,'+(num++)+')" id="'+data[i][0]+'" onmouseout="menu_pop_removeBg(this)"><div><span><input  class="menu_top_checkbox" name="group" type="checkbox" value="'+data[i][0]+'" onclick="selectUsers(this)"/><label>允许</label></span>'+data[i][1]+'</div></li>';
		var users=document.createElement("div");
		users.id = 'pop_layer_'+i;
		var nodes_str="<ul>";
		for(var j=0;j<data[i][2].length;j++)
		{
			nodes_str+= '<li><input name="'+data[i][0]+'" id="menu_pop_user_'+data[i][2][j][0]+'"  value="'+data[i][2][j][0]+'"  onclick="selectGroup(this)" type="checkbox" /><img src="'+menu_pop_data[i][2][j][2]+'" width="20" height="20" alt="" /><span>'+data[i][2][j][1]+'</span></li>';
		}
		nodes_str+="</ul>";
		users.innerHTML = nodes_str;
		tatal_users.appendChild(users);
	}
	pop_str+='</ul><div><input type="button" class="menu_top_button" value="确定" onclick="expression()"/><input class="menu_top_button" type="button" value="取消" onclick="menu_pop_cancel()"/></div></div><div class="bt"></div>';
	
	if(!document.getElementById("menu_top_yj"))
	{
		var pop_node=document.createElement("DIV");
		pop_node.id="menu_top_yj";
		pop_node.className="menu_top_pop_layer";
		pop_node.style.display="none";
		pop_node.style.position="absolute";
		pop_node.innerHTML=pop_str;
		document.body.appendChild(pop_node);
	}
	//创建一级菜单
	if(!document.getElementById("menu_top_pop_layer_box"))
	{
		var pop_layer_box=document.createElement("DIV");
		pop_layer_box.id="menu_top_pop_layer_box";
		document.body.appendChild(pop_layer_box);
	}
	//创建下一级菜单
	var parentNode=document.getElementById("menu_top_pop_layer_box");
	parentNode.innerHTML=tatal_users.innerHTML;
	parentNode.style.display="none";
}


function menu_pop_cancel()
{
	document.getElementById("menu_top_yj").style.display="none";
	document.getElementById("menu_top_pop_layer_box").style.display="none";
}
//表达式JS
function expression()
{
	c=document.getElementsByTagName("input");
	_all="";
	_group="[,";
	user="{,";
	
	for(i=0;i<c.length;i++)
	{
		if('checkbox'==c[i].type)
		{
			
			if(c[i].name=='all')
			{
				 if(c[i].checked)_all="!all";
			}
			else if(c[i].name=='group')
			{
				 if(c[i].checked)_group+=c[i].value+",";
				 else user+=selectUsersValue(c[i]);
			}
		}
	}
	_group+="]";
	user+="}";
	tem="";
	//
	if(user=='{,}')
	{
		if(_group=="[,]")
		{
			tem=_all;	
		}
		else
		{
			tem=_group;
		}
	}
	else
	{
		tem=user;
		if(_group!="[,]")tem=_group+"|"+tem;
	}
	//传送表达式
	if(document.getElementById('privacy')){
		document.getElementById('privacy').value=tem;
	}else{
		if(tem!=current_menu_info[2]){
			var post_expree=new Ajax();
			post_expree.getInfo("servtools/menu_pop/translate_pri.php","POST","app","t_name="+current_menu_info[0]+"&vid="+current_menu_info[1]+"&tem="+tem,function(c){
				if(c=='success'){
					var re_obj=document.getElementById(current_menu_info[0]+':'+current_menu_info[1]+':'+current_menu_info[2]);
					re_obj.id=current_menu_info[0]+':'+current_menu_info[1]+':'+tem;
				}else{
					alert('设置失败');
				}
			});
		}else{
			
		}
	}
	menu_pop_cancel();
	//结束传送表达式
}
function checkall(a)
{
	if(a.checked)
	{
		c=document.getElementsByTagName("input");
		for(i=0;i<c.length;i++)
		{
			if(c[i].type=='checkbox')c[i].checked=true;
		}
	}
	else
	{
		c=document.getElementsByTagName("input");
		for(i=0;i<c.length;i++)
		{
			if(c[i].type=='checkbox')c[i].checked=false;
		}
	}
}

//选择组
function selectUsers(me)
{
	var c=document.getElementsByName(me.value);
	var flag=true;
	for(var i=0;i<c.length;i++)
	{
		if(c[i].type=='checkbox') c[i].checked=me.checked;
	}
	var groups=document.getElementsByName("group");
	for(var i=0;i<groups.length;i++)
	{
		if(!groups[i].checked) flag=false;
	}
	document.getElementsByName("friends")[0].checked=flag;
	document.getElementsByName("all")[0].checked=false;
}
function selectUsersValue(me)
{

	var users=document.getElementsByName(me.value);
	var tem="";
	for(j=0;j<users.length;j++)
	{
		if(users[j].type=='checkbox' && users[j].checked ) tem+=users[j].value+",";
	}
	return tem;
}

function selectGroup(obj)
{
	var group=document.getElementsByName("group");
	var flag=true;
	var all_flag=false;
	var frined_flag=true;
	var users=document.getElementsByName(obj.name);
	for(i=0;i<users.length;i++)
	{
		if(users[i].type=="checkbox" && !users[i].checked) flag=false; 
		if(users[i].checked)all_flag=false;
	}
	for(i=0;i<group.length;i++)
	{
		if(group[i].type=='checkbox' && group[i].value==obj.name) group[i].checked=flag;
		if(!group[i].checked)frined_flag=false;
	}
	var all=document.getElementsByName("all")[0];
	var friends=document.getElementsByName("friends")[0];
	all.checked=all_flag;
	friends.checked=frined_flag;
}
function changeGroup()
{
	groups_str=translateGroup();
	users_str=translateUser();
	var groups=document.getElementsByName("group");
	var all=document.getElementsByName("all")[0];
	all.checked=false;
	if(current_menu_info.length==3 && current_menu_info[2].indexOf("!all")!=-1)all.checked=true;
	for(var i=0;i<groups.length;i++)
	{
		var users=document.getElementsByName(groups[i].value);
		for(var j=0;j<users.length;j++)
		{
			if(users_str.indexOf((","+users[j].value+","))!=-1) users[j].checked=true;
			else users[j].checked=false;
		}
		if(groups_str.indexOf(","+groups[i].value+",")!=-1)
		{
			groups[i].checked=true;
			selectUsers(groups[i]);
		}
		else groups[i].checked=false;
	}
}
function translateUser()
{
	if(current_menu_info=="") return "";
	if(current_menu_info.length==3)
	{
		var users=current_menu_info[2].replace(/.*{(.*)}.*/i,"$1");
		if(users!=current_menu_info[2])
		{
			return users;
		}
		else return "";
	}
	else return "";
}
function translateGroup()
{
	if(current_menu_info=="") return "";
	if(current_menu_info.length==3)
	{
		var users=current_menu_info[2].replace(/.*\[(.*)\].*/i,"$1");
		if(users!=current_menu_info[2])
		{
			return users;
		}
		else return "";
	}
	else return "";
}
function translateUser()
{
	if(current_menu_info=="") return "";
	if(current_menu_info.length==3)
	{
		var users=current_menu_info[2].replace(/.*{(.*)}.*/i,"$1");
		if(users!=current_menu_info[2])
		{
			return users;
		}
		else return "";
	}
	else return "";
}
function selectFriend(obj)
{
	var flag=false;
	if(obj.name=="all")
	{
		flag=false;
		var group=document.getElementsByName("friends");
		if(group[0].type=='checkbox') group[0].checked=flag;
	}
	else
	{
		flag=obj.checked;
		var all=document.getElementsByName("all");
		if(all[0].type=='checkbox') all[0].checked=false;
	}
	var group=document.getElementsByName("group");
	for(var i=0;i<group.length;i++)
	{
		if(group[i].type=='checkbox') group[i].checked=flag;
		var users=document.getElementsByName(group[i].value);
		for(var j=0;j<users.length;j++)
		{
			if(users[j].type=="checkbox") users[j].checked=flag;
		}
	}
}
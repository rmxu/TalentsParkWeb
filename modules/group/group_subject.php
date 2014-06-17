<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/group/group_subject.html
 * 如果您的模型要进行修改，请修改 models/modules/group/group_subject.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php 
	//必须登录才能浏览该页面
	require("foundation/auser_mustlogin.php");
	require("api/base_support.php");
	
	//限制时间段访问站点
	limit_time($limit_action_time);
	
	//引入模块公共方法文件
	require("foundation/module_album.php");
	require("foundation/module_group.php");
	
	//引入语言包
	$g_langpackage=new grouplp;
	
	//变量区
	$user_id=get_sess_userid();
	$join_group=get_sess_jgroup();
	$group_id=intval(get_argg('group_id'));
	$creat_group=get_sess_cgroup();
	$u_id=intval(get_argg('user_id'));
	$role='';
	$album_id='';
	
	//权限判断
	$role=api_proxy("group_member_by_role",$group_id,$user_id);
	$role=$role[0];
	if(!isset($role)){
		echo "<script type='text/javascript'>alert(\"$g_langpackage->g_no_privilege\");window.history.go(-1);</script>";exit();
	}
	$album_rs = api_proxy("album_self_by_uid","album_id,album_name",$user_id);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<SCRIPT language=JavaScript src="servtools/editor/nicEdit.js"></SCRIPT>
<SCRIPT language=JavaScript src="servtools/imgfix.js"></SCRIPT>
<SCRIPT language=JavaScript src="skin/default/js/jooyea.js"></SCRIPT>
<SCRIPT language=JavaScript src="servtools/ajax_client/ajax.js"></SCRIPT>
<script Language="JavaScript">
bkLib.onDomLoaded(function(){
	nicE=new nicEditor({fullPanel : true}).panelInstance('CONTENT');
});
var oldContent = '';
function CheckForm(){
	var content_inner=$("CONTENT").previousSibling.children[0].innerHTML;
	oldContent = content_inner;
	if(document.myform.LOG_TITLE.value==""){
		parent.Dialog.alert("<?php echo $g_langpackage->g_title;?>");
		document.myform.LOG_TITLE.focus();
		return false;
	}
	if(trim(content_inner)==''){
		parent.Dialog.alert("<?php echo $g_langpackage->g_none_content;?>");
		return false;
	}
}
window.onbeforeunload = function ()
{
	var newContent = document.getElementById("CONTENT").previousSibling.children[0];
	if(newContent && trim(newContent.innerHTML.toString()) == trim(oldContent.toString())){return;}else{return '<?php echo $g_langpackage->g_content_not_saved;?>!';}
}
parent.hiddenDiv();
</script>
</head>

<body id="iframecontent">
    <div class="create_button"><a href="modules.php?app=group_space&group_id=<?php echo $group_id;?>&user_id=<?php echo $user_id;?>"><?php echo $g_langpackage->g_re_space;?></a></div>
    <h2 class="app_group"><?php echo $g_langpackage->g_subject;?></h2>
    <div class="tabs">
        <ul class="menu">
            <li class="active"><a href="javascript:;" hidefocus="true"><?php echo $g_langpackage->g_subject;?></a></li>
        </ul>
    </div>
	<form action="do.php?act=group_send_sub&group_id=<?php echo $group_id;?>&user_id=<?php echo $u_id;?>"  method="post" style="margin-top:10px" name="myform" onSubmit="return CheckForm();">
		<table border="0" class="form_table" cellpadding="2" cellspacing="1" >
			<tr>
				<th nowrap="nowrap"><?php echo $g_langpackage->g_subject;?>：</th>
				<td><input class="med-text" type="text" name="LOG_TITLE" value="" maxlength="30" /></td>
			</tr>
			<tr>
      			<th valign="top"><?php echo $g_langpackage->g_content;?>：</th>
				<td style="line-height:1.5"><textarea name="CONTENT" id="CONTENT" class="textarea" style='width:550px;height:300px;_width:550px;'></textarea></td>
		   </tr>
   		<tr>
	      <th><?php echo $g_langpackage->g_pic;?>：</th>
	      <td>
	      	<div id="POPUP_KE_IMAGE">
	      		<iframe name="KindImageIframe" id="KindImageIframe" width="100%" height=30 align=top allowTransparency="true" scrolling=no src='modules.php?app=upload_form' frameborder=0></iframe>
	      	</div>
		  </td>
		</tr>
		<tr>
			<th></th>
    		<td><?php echo album_name($album_rs,$album_id);?><?php echo $g_langpackage->g_sel_album;?></td>
			<script type='text/javascript'>
				var album_select=document.getElementById("album_name");
				album_select.onchange=list_album_photos;										      
				function list_album_photos(){
					//获取接受返回信息层 
					var album_select_val=document.getElementById("album_name").value;
					var photos_list=document.getElementById("photos_list");
					if(album_select_val==""){
					return false;
					}else{
					photos_list.innerHTML="<?php echo $g_langpackage->g_data_loading;?>...";
					var get_album=new Ajax();//实例化Ajax
					get_album.getInfo("modules.php","get","app","app=user_ico_select&type=blog_photo&album_id="+album_select_val,function(c){photos_list.innerHTML=c;});
					}
				}
			</script>
			</tr>
			<tr>
				<th></th>
				<td><div id='photos_list'></div></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" class="regular-btn" value="<?php echo $g_langpackage->g_button_yes;?>"></td>
			</tr>
		</table>
	</form>
</body>
</html>
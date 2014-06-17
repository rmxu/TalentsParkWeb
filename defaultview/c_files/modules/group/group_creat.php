<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/group/group_creat.html
 * 如果您的模型要进行修改，请修改 models/modules/group/group_creat.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入公共模块
	require("foundation/module_group.php");
	require("api/base_support.php");

	//引入语言包
	$g_langpackage=new grouplp;
	
	//必须登录才能浏览该页面
	require("foundation/auser_mustlogin.php");
	
	//限制时间段访问站点
	limit_time($limit_action_time);	

	//缓存功能区
	$group_sort_rs=api_proxy("group_sort_by_self");
	$group_type=group_sort_list($group_sort_rs,"");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<script language="JavaScript">
function check_form(){
	if(document.getElementById("group_name").value==""||
		document.getElementById("group_resume").value==""||
		document.getElementById("tag").value==""||
		document.getElementById("group_type").value==""){
		parent.Dialog.alert("<?php echo $g_langpackage->g_no_pass;?>");return false;
	}
	if(document.getElementById("group_resume").value.length>=90){
		parent.Dialog.alert("<?php echo $g_langpackage->g_resume_len;?>");
		return false;
	}
}
function textarea_sub(){
	var group_resume = document.getElementById('group_resume').value;
	if(group_resume.length>100){
		parent.Dialog.alert("<?php echo $g_langpackage->g_fill_100_characters;?>");
		return;
	}
}
</script>

<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
</head>
<body id="iframecontent">
    <div class="create_button"><a href="modules.php?app=group_creat"><?php echo $g_langpackage->g_creat;?></a></div>
    <h2 class="app_group"><?php echo $g_langpackage->g_group;?></h2>
    <div class="tabs">
        <ul class="menu">
            <li class="active"><a href="modules.php?app=group" hidefocus="true"><?php echo $g_langpackage->g_mine;?></a></li>
            <li><a href="modules.php?app=group_hot" hidefocus="true"><?php echo $g_langpackage->g_hot;?></a></li>
        </ul>
    </div>
	<table class='form_table'>	
		
		<form action='do.php?act=group_creat' method='post' enctype="multipart/form-data" onsubmit='return check_form()'>
			<input type="hidden" name="group_type_name" id="group_type_name" />
		
		<tr><th><?php echo $g_langpackage->g_name;?>:</th>
			<td><input type='text' class="med-text" name='group_name' id='group_name' autocomplete='off' maxlength="40" /></td>
		</tr>
		<tr><td colspan="2" height="5"></td></tr>
		<tr><th valign="top"><?php echo $g_langpackage->g_intro;?>:</th>
			<td><textarea class="med-textarea" name='group_resume' id='group_resume'></textarea></td>
		</tr>
		<tr><th><?php echo $g_langpackage->g_join_type;?>:</th>
			<td><div class="form_select_div">
				<select class="form_select" name='group_join_type'>
					<option value='0'><?php echo $g_langpackage->g_freedom_join;?></option>
					<option value='1'><?php echo $g_langpackage->g_check_join;?></option>
					<option value='2'><?php echo $g_langpackage->g_refuse_join;?></option>
				</select></div>
			</td>
		</tr>
		
		<tr><th><?php echo $g_langpackage->g_tag;?>:</th>
			<td><input type='text' class="small-text" name='tag' id='tag' autocomplete='off' maxlength="40" /></td>
		</tr>
		
		<tr><th><?php echo $g_langpackage->g_type;?>:</th>
    	<td>
        <?php echo $group_type;?>
			</td>
		</tr>
		
		<tr>
			<th><?php echo $g_langpackage->g_logo;?>:</th>
			<td><input type='file' name='attach[]' id='group_logo' class="med-text" /></td>
		</tr>
		
		<tr>
			<td></td>
			<td><input type='submit' id="UploadButton" value='<?php echo $g_langpackage->g_button_creat;?>' class="regular-btn"  name='action' onclick="textarea_sub()" /></td>
		</tr><tr><td colspan="2" height="15"></td></tr>
	</form>
</table>
</body>
</html>
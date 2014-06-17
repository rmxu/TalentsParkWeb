<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/group/group_info_manager.html
 * 如果您的模型要进行修改，请修改 models/modules/group/group_info_manager.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入语言包
	$g_langpackage=new grouplp;

	//引入公共模块
	require("foundation/module_group.php");
	require("api/base_support.php");

	//变量区
	$role='';
	$user_id=get_sess_userid();
	$group_id=intval(get_argg('group_id'));

	//权限判断
	$role=api_proxy("group_member_by_role",$group_id,$user_id);
	$role=$role[0];
	if(!isset($role)||$role>=2){
		echo "<script type='text/javascript'>alert(\"$g_langpackage->g_no_privilege\");window.history.go(-1);</script>";exit();
	}

	$group_row=api_proxy("group_self_by_gid","*",$group_id);

	//群组加入方式预订
	$free=''; $check=''; $enjoin='';
	if($group_row['group_join_type']==0) $free="selected=selected";
	if($group_row['group_join_type']==1) $check="selected=selected";
	if($group_row['group_join_type']==2) $enjoin="selected=selected";

	$group_sort_rs=api_proxy("group_sort_by_self");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<script language="JavaScript">
	
function change_logo(){
	window.document.getElementById("change_logo").innerHTML="<input type='file' name='attach[]' />";
}
function textarea_sub(){
	var group_resume = document.getElementById('group_resume').value;
	var gonggao = document.getElementById('gonggao').value;
	if(group_resume.length>100){
		parent.Dialog.alert("<?php echo $g_langpackage->g_fill_100_characters;?>");
		return;
	}
	if(gonggao.length>200){
		parent.Dialog.alert("<?php echo $g_langpackage->g_fill_200_characters;?>");
		return;
	}
}
</script>
</head>
<body id="iframecontent">
    <div class="create_button"><a href="modules.php?app=group"><?php echo $g_langpackage->g_return;?></a></div>
    <h2 class="app_group"><?php echo $g_langpackage->g_manage;?></h2>
    <div class="tabs">
        <ul class="menu">
            <li><a href="modules.php?app=group_manager&group_id=<?php echo $group_id;?>" title="<?php echo $g_langpackage->g_info;?>" class="nowbutl"><?php echo $g_langpackage->g_info;?></a></li>
            <li class="active"><a href="modules.php?app=group_info_manager&group_id=<?php echo $group_id;?>" title="<?php echo $g_langpackage->g_info_change;?>"><?php echo $g_langpackage->g_info_change;?></a></li>
            <li><a href="modules.php?app=group_member_manager&group_id=<?php echo $group_id;?>" title="<?php echo $g_langpackage->g_manage_member;?>"><?php echo $g_langpackage->g_manage_member;?></a></li>
            <li><a href="modules.php?app=group_space&group_id=<?php echo $group_id;?>" title="<?php echo $g_langpackage->g_en_space;?>"><?php echo $g_langpackage->g_en_space;?></a></li>
        </ul>
    </div>
		<form action="do.php?act=group_change_group_info&group_id=<?php echo $group_id;?>" method="post" enctype="multipart/form-data">
<table class='form_table'>
	<tr>
		<input type="hidden" name="group_type_name" id="group_type_name" value="<?php echo $group_row['group_type'];?>" />
		<input type="hidden" name="old_group_logo" value=<?php echo $group_row['group_logo'];?> />
		<th><?php echo $g_langpackage->g_name;?>:</th>
		<td><input type='text' class="med-text" name='group_name' size="32" maxlength="15" value=<?php echo $group_row['group_name'];?> autocomplete='off' /></td>
	</tr>
	
	<tr>
		<th height="96"><?php echo $g_langpackage->g_resume;?>:</th>
	  <td><textarea id="group_resume" class="med-textarea" rows='4' cols='29' name='group_resume'><?php echo $group_row['group_resume'];?></textarea></td>
	</tr>
		
	<tr><th height="88"><?php echo $g_langpackage->g_gonggao;?>:</th>
	  <td><textarea id="gonggao" class="med-textarea" rows='4' cols='29' name='gonggao'><?php echo $group_row['affiche'];?></textarea></td>
	</tr>
	
	<tr><th><?php echo $g_langpackage->g_tag;?>:</th>
			<td><input type='text' class="med-text" name='tag' size="32" maxlength="20" value=<?php echo $group_row['tag'];?> autocomplete='off' /></td>
	</tr>
	
	<tr>
		<th><?php echo $g_langpackage->g_join_type;?>:</th>
		<td>
			<select name='group_join_type'>
				<option value='0' <?php echo $free;?>><?php echo $g_langpackage->g_freedom_join;?></option>
				<option value='1' <?php echo $check;?>><?php echo $g_langpackage->g_check_join;?></option>
				<option value='2' <?php echo $enjoin;?>><?php echo $g_langpackage->g_refuse_join;?></option>
			</select>
		</td>
	</tr>
	
	<tr>
		<th><?php echo $g_langpackage->g_type;?>:</th>
		<td>
			<?php echo group_sort_list($group_sort_rs,$group_row['group_type_id']);?>
		</td>
	</tr>
	
	<tr>
	  <th valign="top"><?php echo $g_langpackage->g_logo;?>:</th>
	  <td id='change_logo2'><img onerror="parent.pic_error(this)" src="<?php echo $group_row['group_logo'] ? $group_row['group_logo'] : 'uploadfiles/group_logo/default_group_logo.jpg';?>" width='150px' height='150px' class='mt8' /></td>
    </tr>
	<tr>
		<th>&nbsp;</th>
	  <td id='change_logo'><input type='button'  value=<?php echo $g_langpackage->g_change_logo;?>  onclick='change_logo();' /></td>
	</tr>

	<tr>
		<td></td>
		<td><input type='submit' value='<?php echo $g_langpackage->g_button_yes;?>' class='regular-btn' name='action' onclick="textarea_sub()" />&nbsp;&nbsp;<input type='reset' value='<?php echo $g_langpackage->g_button_re;?>' class='regular-btn' /></td>
	</tr>
	
</table>
		</form>

</body>
</html>
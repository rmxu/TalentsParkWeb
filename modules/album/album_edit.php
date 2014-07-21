<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/album/album_edit.html
 * 如果您的模型要进行修改，请修改 models/modules/album/album_edit.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
//引入语言包
$a_langpackage=new albumlp;

//必须登录才能浏览该页面
require("foundation/auser_mustlogin.php");

//限制时间段访问站点
limit_time($limit_action_time);

//变量取得
$album_id=intval(get_argg('album_id'));

//数据表定义区
$t_album = $tablePreStr."album";

if($album_id){
	require("api/base_support.php");
	$album_row = api_proxy("album_self_by_aid","*",$album_id);
	$album_id=$album_row['album_id'];
	$album_name=$album_row['album_name'];
	$tag=$album_row['tag'];
	$album_info=$album_row['album_info'];
	$album_pri=$album_row['privacy'];
	$act_url="do.php?act=album_upd&album_id=".$album_id;
	$act_str=$a_langpackage->a_b_upd;
	$top_str=$a_langpackage->a_edit;
}else{
	$act_url="do.php?act=album_creat";
	$act_str=$a_langpackage->a_b_crt;
	$album_pri="";
	$album_info="";
	$album_name="";
	$tag="";
	$album_id="";
	$top_str=$a_langpackage->a_creat;
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="servtools/menu_pop/menu_pop.css">
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<script type='text/javascript' src='servtools/ajax_client/ajax.js'></script>
<script type='text/javascript' src='servtools/menu_pop/group_user.php'></script>
<script type='text/javascript' src="skin/default/js/jooyea.js"></script>
<script type='text/javascript' src="servtools/menu_pop/menu_pop.js"></script>
</head>
<script type='text/javascript'>
function check_form(){
	if(($("album_name").value=="")||($("album_information").value=="")){
		parent.Dialog.alert("<?php echo $a_langpackage->a_inp_err;?>");
		return false;
	}
}
</script>
<body id="iframecontent">
<div class="create_button"><a href="modules.php?app=album_edit"><?php echo $a_langpackage->a_creat;?></a></div>
<div class="create_button"><a href="modules.php?app=photo_upload"><?php echo $a_langpackage->a_upload;?></a></div>
<h2 class="app_album"><?php echo $a_langpackage->a_title;?></h2>
<div class="tabs">
	<ul class="menu">
        <li class="active"><a href="modules.php?app=album" hidefocus="true"><?php echo $a_langpackage->a_mine;?></a></li>
        <li><a href="modules.php?app=album_friend" hidefocus="true"><?php echo $a_langpackage->a_friend;?></a></li>
    </ul>
</div>
<form name="form" action="<?php echo $act_url;?>" method="post" onsubmit='return check_form()'>
<table class="form_table">
	<tr>
		<th><?php echo $a_langpackage->a_name;?></th>
		<td><input class="med-text" type="text" name="album_name" id="album_name" value="<?php echo $album_name;?>" /></td>
	</tr>
	<tr>
		<th valign="top"><?php echo $a_langpackage->a_inf;?></th>
		<td><textarea class="med-textarea" name="album_information" id="album_information" cols="30" rows="5"><?php echo $album_info;?></textarea></td>
	</tr>
	<tr>
		<th valign="top"><?php echo $a_langpackage->a_label;?>：</th>
		<td><input class="med-text" type="text" name="tag" id="tag" value="<?php echo $tag;?>" /></td>
	</tr>
	<tr>
		<th><?php echo $a_langpackage->a_secret;?></th>
		<td><input type='hidden' id='privacy' name='privacy' value="<?php echo $album_pri;?>" /><a href='javascript:;' onmouseDown="menu_pop_show(event,this,1);" id="<?php echo $t_album;?>:<?php echo $album_id;?>:<?php echo $album_pri;?>" /><?php echo $a_langpackage->a_privacy_set;?></a></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" class="regular-btn" value="<?php echo $act_str;?>" name='action' /></td>
	</tr>
</table>
</form>
</body>
</html>
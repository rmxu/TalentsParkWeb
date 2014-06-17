<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/users/user_ico.html
 * 如果您的模型要进行修改，请修改 models/modules/users/user_ico.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php 
	//引入模块公共方法文件
	require("foundation/module_album.php");
	require("api/base_support.php");
	
	//语言包引入
	$u_langpackage=new userslp;
	
	//变量获得
	$user_id =get_sess_userid();
	$album_id=intval(get_argg('album_id'));
	
	$album_rs = api_proxy("album_self_by_uid","album_id,album_name",$user_id);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<SCRIPT language=JavaScript src="servtools/ajax_client/ajax.js"></SCRIPT>
<script type='text/javascript'>
function change_ico(){
	if(document.getElementById('advance_ico').style.display==''){
		document.getElementById('advance_ico').style.display='none';
		document.getElementById('advance_ico_tip').style.display='none';
		document.getElementById('normal_ico').style.display='';
		document.getElementById('normal_ico_tip').style.display='';
	}else{
		document.getElementById('advance_ico').style.display='';
		document.getElementById('advance_ico_tip').style.display='';
		document.getElementById('normal_ico').style.display='none';
		document.getElementById('normal_ico_tip').style.display='none';
		
	}
}
</script>
</head>
<body id="iframecontent">
<h2 class="app_user"><?php echo $u_langpackage->u_conf;?></h2>
<div class="tabs">
	<ul class="menu">
        <li><a href="modules.php?app=user_info" title="<?php echo $u_langpackage->u_info;?>"><?php echo $u_langpackage->u_info;?></a></li>
        <li class="active"><a href="modules.php?app=user_ico" title="<?php echo $u_langpackage->u_icon;?>"><?php echo $u_langpackage->u_icon;?></a></li>
        <li><a href="modules.php?app=user_pw_change" title="<?php echo $u_langpackage->u_pw;?>"><?php echo $u_langpackage->u_pw;?></a></li>
        <li><a href="modules.php?app=user_dressup" title="<?php echo $u_langpackage->u_dressup;?>"><?php echo $u_langpackage->u_dressup;?></a></li>
        <li><a href="modules.php?app=user_affair" title="<?php echo $u_langpackage->u_set_affair;?>"><?php echo $u_langpackage->u_set_affair;?></a></li>
    </ul>
</div>
	<div class="rs_head"><?php echo $u_langpackage->u_set_ico;?></div>
	
	<table class='form_table' cellpadding="0" cellspacing="0">
		<tr>
			<th valign="top"><?php echo $u_langpackage->u_ico_now;?>：</th>
			<td><img class="photo_frame" src="<?php echo str_replace('_small','',get_sess_userico());?>"></td>
		</tr>
		<tr>
			<th><?php echo $u_langpackage->u_ico_spc;?>：</th>
			<td><?php echo $u_langpackage->u_ico_siz;?></td>
		</tr>
	</table>
	
	<table class='form_table' id='advance_ico' cellpadding="0" cellspacing="0" style="display:none">
		<div class="rs_head" id='advance_ico_tip' style="display:none">
			<span class="right"><a href='javascript:change_ico()'><?php echo $u_langpackage->u_normal_ico;?></a></span><?php echo $u_langpackage->u_set_advanceway;?>
		</div>
		<tr>
			<td colspan=2>
				<form name="form2" method="post" action="do.php?act=user_ico_upload" enctype="multipart/form-data">
					<input type="hidden" name='type' value='ico'/>
					<input  class='left mr10' style="height:23px;" type="file" name="attach[]"/>
					<input class='small-btn left' type="submit" name="submit" id="UploadButton2" value="<?php echo $u_langpackage->u_upload;?>"  />
				</form>
			</td>
		</tr>
	</table>
	
	<table class='form_table' id="normal_ico"  cellpadding="0" cellspacing="0">
		<div class="rs_head" id='normal_ico_tip'>
			<span class="right"><a href='javascript:change_ico()'><?php echo $u_langpackage->u_advance_ico;?></a></span><?php echo $u_langpackage->u_set_way;?>
		</div>
		<tr>
			<td colspan=2><?php echo $u_langpackage->u_b;?></td>
		</tr>
		<tr>
			<td colspan=2>
				<form name="form1" method="post" action="do.php?act=user_ico_upload" enctype="multipart/form-data">
					<input type="hidden" name='type' value='photo' />
					<input  class='left mr10' style="height:23px;" type="file" name="attach[]" />
					<input class='small-btn left' type="submit" name="submit" id="UploadButton" value="<?php echo $u_langpackage->u_upload;?>"/>
				</form>
			</td>
		</tr>
		<tr>
			<td colspan=2><?php echo $u_langpackage->u_c;?></td>
		</tr>
		<tr>
			<td colspan=2><?php echo $u_langpackage->u_cho_alb;?>:
				<?php album_name($album_rs,$album_id);?>

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
						photos_list.innerHTML="<?php echo $u_langpackage->u_data;?>";
						var get_album=new Ajax();
						get_album.getInfo("modules.php","get","app","app=user_ico_select&album_id="+album_select_val,function(c){photos_list.innerHTML=c;});
						}
					}
					</script>
					<div id='photos_list'></div>
			</td>
		</tr>
	</table>
</body>
</html>
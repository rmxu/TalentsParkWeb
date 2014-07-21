<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/users/user_archives.html
 * 如果您的模型要进行修改，请修改 models/modules/users/user_archives.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入模块公共方法文件 
	require("foundation/module_users.php");
	require("foundation/fcontent_format.php");
	require("api/base_support.php");
	
	//引入语言包
	$u_langpackage=new userslp;
	
	//变量区
	$userid=intval(get_argg('user_id'));
	
	$info_item_init=$u_langpackage->u_set;//info_item_format($info_item_init,$inputTxt)
  $user_info = api_proxy("user_self_by_uid","*",$userid);
  $user_sex_txt=get_user_sex($user_info['user_sex']);
  $user_birthday=brithday_format($user_info['birth_year'],$user_info['birth_month'],$user_info['birth_day']);
  $user_marrstate=get_user_marry($user_info['user_marry']);
  $user_lastlogin_time=$user_info['lastlogin_datetime'];
  
  $holder_name=get_hodler_name($userid);
  
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
</head>

<body>
  <div class="main_right_title">
     <span class="main_title main_title_dot9"></span>
     <span class="main_title_txt"><?php echo str_replace("{holder}",$holder_name,$u_langpackage->u_vis_conf);?></span>
  </div>

	
		<div class='container' style='padding-top:15px;margin-top:0px'>
			<div class='rs_head' style='margin-top:0px'><?php echo $user_info['user_name'];?><?php echo $u_langpackage->u_inf;?></div>
		</div>
		
		<table class='main main_left'>
			<tr>
				<th><?php echo $u_langpackage->u_name;?></th>
				<td><?php echo filt_word($user_info['user_name']);?></td>
			</tr>
			
			<tr>
				<th><?php echo $u_langpackage->u_sex;?></th>
				<td><?php echo info_item_format($info_item_init,$user_sex_txt);?></td>
			</tr>
			
			<tr>
				<th><?php echo $u_langpackage->u_marr;?></th>
				<td><?php echo info_item_format($info_item_init,$user_marrstate);?></td>
			</tr>
			
			<tr>
				<th><?php echo $u_langpackage->u_bird;?></th>
				<td><?php echo info_item_format($info_item_init,$user_birthday);?></td>
			</tr>
			
			<tr>
				<th><?php echo $u_langpackage->u_bld;?></th>
				<td><?php echo info_item_format($info_item_init,get_user_blood($user_info["user_blood"]));?></td>
			</tr>

			<tr>
				<th><?php echo $u_langpackage->u_birc;?></th>
				<td><?php echo info_item_format($info_item_init,$user_info["birth_province"].$user_info["birth_city"]);?></td>
			</tr>
			
			<tr>
				<th><?php echo $u_langpackage->u_res;?></th>
				<td><?php echo info_item_format($info_item_init,$user_info["reside_province"].$user_info["reside_city"]);?></td>
			</tr>
             
			<tr>
				<th>QQ</th>
				<td><?php echo info_item_format($info_item_init,$user_info["user_qq"]);?></td>
			</tr>
             
			<tr>
				<th>EMAIL</th>
				<td><?php echo info_item_format($info_item_init,$user_info["user_email"]);?></td>
			</tr>

		</table>
		
</body>
</html>

<?php
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
  
?>
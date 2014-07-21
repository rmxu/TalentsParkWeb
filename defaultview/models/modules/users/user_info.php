<?php
 //引入模块公共方法文件
 require("foundation/fgrade.php");
 require("foundation/module_users.php");
 require("api/base_support.php");

	//语言包引入
	$u_langpackage=new userslp;

	//变量获得
	$url_uid=intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();
	$show_type=intval(get_argg('single'));
	$is_finish=intval(get_argg('is_finish'));

  //引入模块公共权限过程文件
	$is_self_mode='partLimit';
	$is_login_mode='';
	require("foundation/auser_validate.php");

	$user_row = api_proxy("user_self_by_uid","*",$userid);

	//性别预定义
	$woman_c=$user_row['user_sex'] ? "checked=checked":"";
	$man_c=$user_row['user_sex'] ? "":"checked=checked";
	
	//婚姻预定义
	$sec_c="";
	$mer_c="";
	$n_mer_c="";
	if($user_row['user_marry']==0){
		$sec_c="selected=selected";
	}
	if($user_row['user_marry']==1){
		$mer_c="selected=selected";
	}
	if($user_row['user_marry']==2){
		$n_mer_c="selected=selected";
	}
?>
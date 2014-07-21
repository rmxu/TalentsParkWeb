<?php
	//引入语言包
	$mb_langpackage=new msgboardlp;

	//变量取得
	$url_uid= intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();

	//引入模块公共权限过程文件
	$is_self_mode='partLimit';
	$is_login_mode='';
	require("foundation/auser_validate.php");
	require("foundation/fcontent_format.php");
	require("api/base_support.php");
	$mes_rs=array();
	$mes_rs=api_proxy("msgboard_self_by_uid","*",$userid,'',5);
?>
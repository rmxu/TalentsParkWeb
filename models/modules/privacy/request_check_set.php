<?php
	//引入公共方法
	require("foundation/fcontent_format.php");
	require("foundation/module_users.php");

	//语言包引入
	$pr_langpackage=new privacylp;
	
	//变量获得
	$user_id=get_sess_userid();
	
	//表声明区
	$t_users=$tablePreStr."users";

	$dbo=new dbex;
	
	//读写分离定义函数
	dbtarget('r',$dbServs);
	
	$select_items=array('palsreq_limit');
	$user_privacy=get_user_info_item($dbo,$select_items,$t_users,$user_id);
	
?>

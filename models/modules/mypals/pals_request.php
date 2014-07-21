<?php 
	//必须登录才能浏览该页面
	require("foundation/auser_mustlogin.php");
	
	//引入语言包
	$mp_langpackage=new mypalslp;
	
	//引入公共模块
	require("foundation/module_mypals.php");
		
	//变量区
	$user_id=get_sess_userid();
	
	//数据表定义区
	$t_pals_request=$tablePreStr."pals_request";
	
	$dbo=new dbex;
	dbtarget('r',$dbServs);
	
	$request_rs=array();
	
	$sql="select * from $t_pals_request where user_id='$user_id'";
	$request_rs=$dbo->getRs($sql);
	
	//控制显示
	$isNull=0;
	$isset_data="";
	$none_data="content_none";
	if(empty($request_rs)){
		$isset_data="content_none";
		$none_data="";
		$isNull=1;
	}
	
?>
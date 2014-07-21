<?php
	//引入模块公共方法文件 
	require("foundation/module_users.php");
	require("foundation/fcontent_format.php");
	require("foundation/fpages_bar.php");
	
	$to_user_id=get_sess_userid();
	$hi_rs=array();
	
	//当前页面参数
	$page_num=trim(get_argg('page'));
	
	//引入语言包
	$u_langpackage=new userslp;
	$hi_langpackage=new hilp;
	
	//数据表定义区
	$t_hi = $tablePreStr."hi";
	
	//定义读取操作
	dbtarget('r',$dbServs);
	$dbo=new dbex;
	$dbo->setPages(20,$page_num);//设置分页
	$sql="select * from $t_hi where to_user_id=$to_user_id order by add_time desc";
	$hi_rs=$dbo->getRs($sql);
	$page_total=$dbo->totalPage;//分页总数
	$isNull=0;
	$none_data='content_none';
	if(empty($hi_rs)){
		$none_data='';
		$isNull=1;
	}
?>
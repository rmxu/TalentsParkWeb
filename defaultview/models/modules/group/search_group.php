<?php
	//引入语言包
	$g_langpackage=new grouplp;
	
	//引入模块公共权限过程文件
	require("foundation/fpages_bar.php");
	require("foundation/module_group.php");
	require("api/base_support.php");

	//变量区
	$userid=get_sess_userid();
	$url_uid = get_argg('url_uid');
	$user_join_group=get_sess_jgroup();
	$user_creat_group=get_sess_cgroup();

	//数据表定义
	$t_groups=$tablePreStr."groups";
	$t_group_members=$tablePreStr."group_members";

	//定义读操作
	dbtarget('r',$dbServs);	
	$dbo=new dbex();		
	$cols="1=1";
		
	//按群组名
	if(get_argg('group_name')){
		$search=short_check(get_argg('group_name'));
		$cols.=" and group_name like '%$search%' ";
	}
	
	//按群组标签名
	if(get_argg('tag')){
		$search=short_check(get_argg('tag'));
		$cols.=" and tag like '%$search%' ";
	}
	
	//按群组类型
	if(get_argg('group_type_id')){
		$search=intval(get_argg('group_type_id'));
		$cols.=" and group_type_id='$search' ";
	}
	$page_num=trim(get_argg('page')); 
	
	$condition="$cols and is_pass=1";
	$order_by="order by member_count desc";
	$type="getRs";
	$dbo->setPages(20,$page_num);//设置分页	
	$search=get_db_data($dbo,$t_groups,$condition,$order_by,$type);	
	$page_total=$dbo->totalPage;//分页总数

	//显示控制
	$isset_data="";
	$none_data="content_none";
	$isNull=0;
	if(empty($search)){
		$isNull=1;
		$isset_data="content_none";
		$none_data="";
	}
	
	$g_join_num=$g_langpackage->g_join_num;
?>
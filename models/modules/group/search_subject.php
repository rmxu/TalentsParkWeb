<?php
	//引入公共函数
	require("foundation/module_group.php");
	require("foundation/fpages_bar.php");
	require("api/base_support.php");
	
	//引入语言包
	$g_langpackage=new grouplp;

	//变量区
	$role='';
	$user_id=get_sess_userid();
	$group_id=intval(get_argg('group_id'));
	$url_uid=intval(get_argg('user_id'));
	
	//链接地址变更
	$main_URL="content_none";
	$home_URL="";
	$is_admin=get_sess_admin();
	if($is_admin==''){
		$main_URL="";
		$home_URL="content_none";
	}
	$page_num=trim(get_argg('page')); 
	$key_word = short_check(get_argp('key_word'));
	
	//数据表定义
	$t_users=$tablePreStr."users";
	$t_groups=$tablePreStr."groups";
	$t_group_members=$tablePreStr."group_members";
	$t_group_subject=$tablePreStr."group_subject";
	$t_group_subject_comment=$tablePreStr."group_subject_comment";
	
	//定义读操作
	dbtarget('r',$dbServs);	
	$dbo=new dbex;
	$show_action=0;
	//权限判断
	$role=api_proxy("group_member_by_role",$group_id,$user_id);
	$role=$role[0];
	if(($role==0||$role==1) && isset($role)){
		$show_action=1;
	}
	
	$condition="group_id=$group_id and title like '%$key_word%'";
	$order_by="order by add_time desc";
	$type="getRs";
	$dbo->setPages(20,$page_num);//设置分页	
	$subject_rs=get_db_data($dbo,$t_group_subject,$condition,$order_by,$type);
	$page_total=$dbo->totalPage;//分页总数	
	
	//显示控制
	$isset_data="";
	$none_data="content_none";
	$isNull=0;
	if(empty($subject_rs)){
		$isNull=1;
		$isset_data="content_none";
		$none_data="";
	}
?>
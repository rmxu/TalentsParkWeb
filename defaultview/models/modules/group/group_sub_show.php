<?php
	//引入公共模块
	require("foundation/fpages_bar.php");
	require("foundation/module_group.php");
	require("api/base_support.php");

	//引入语言包
	$g_langpackage=new grouplp;
	$mn_langpackage=new menulp;

	//变量区
	$url_group_id=intval(get_argg('group_id'));
	$subject_id=intval(get_argg('subject_id'));
	$visitor_id=get_sess_userid();
	$visitor_name=get_sess_username();
	$user_id=get_argg('user_id');

	$role='';
	
	//数据表定义
	$t_blog=$tablePreStr."blog";
	
	//权限判断
	if($visitor_id!=''){
		$role=api_proxy("group_member_by_role",$url_group_id,$user_id);
		$role=$role[0];
	}

	$g_join_type=api_proxy("group_self_by_gid","*",$url_group_id);
	$join_type=$g_join_type['group_join_type'];

	//控制评论
		$isNull=0;
		$show_com='';
	if(empty($comment_rs)){
		$isNull=1;
	}

	$subject_row=api_proxy("group_sub_by_sid","*",$subject_id);
	$host_id=$subject_row['user_id'];

	//防止刷新访问量
	if($visitor_id!=get_session('g_'.$subject_id)){
		$dbo=new dbex;
		dbtarget('w',$dbServs);
		$t_group_subject=$tablePreStr.'group_subject';	
		$sql="update $t_group_subject set hits=hits+1 where subject_id=$subject_id";
		$dbo->exeUpdate($sql);
		set_session('g_'.$subject_id,$visitor_id);
	}

	//权限显示控制
	$is_pri=1;
	$show_error="content_none";
	$error_str="";
	$isset_data="";
	$is_admin=get_sess_admin();
	if($is_admin==''){
		if($role=='' && $join_type!=0){
			$is_pri=0;
			$show_error="";
			$isset_data="content_none";
			$error_str=$g_langpackage->g_arrest;
			$show_com="content_none";
		}
	}
	//显示控制
	if(empty($subject_row)){
		$show_error="";
		$isset_data="content_none";
		$error_str=$g_langpackage->g_data_none;
		$show_com="content_none";
	}

?>
<?php
	//引入模块公共权限过程文件
	require("foundation/fpages_bar.php");
	require("foundation/module_mypals.php");

	//引入语言包
	$mp_langpackage=new mypalslp;
	
	$search_name=short_check(get_argg('memName'));
	$is_online=intval(get_argg('online'));
	$q_province=short_check(get_argg('q_province'));
	$q_city=short_check(get_argg('q_city'));
	$s_province=short_check(get_argg('s_province'));
	$s_city=short_check(get_argg('s_city'));
	
	$age=short_check(get_argg('age'));   
	$min_age=short_check(get_argg('min_age'));
	$max_age=short_check(get_argg('max_age'));
	$sex=short_check(get_argg('sex'));
	$type=short_check(get_argg('type'));	
	$memName=short_check(get_argg("memName"));
	$cols=" 1=1 ";
	$user_id=get_sess_userid();
	$user_name=get_sess_username();
	$user_sex=get_sess_usersex();
	$is_login=1;
	$send_script_js="location.href='modules.php?app=msg_creator&2id={uid}&nw=1';";
	$send_join_js="mypals_add({uid});";
	$error_str=$mp_langpackage->mp_no_search;
	$target="frame_content";
	if(empty($user_id)||$type=='index'){
		$is_login=0;
		$send_script_js="goLogin();";
		$send_join_js="goLogin();";
		$error_str=$mp_langpackage->mp_search_none;
		$target="";
	}
	//数据表定义区
	$table='';
	$t_users=$tablePreStr."users";
	$t_mypals=$tablePreStr."pals_mine";
	$t_pals_request=$tablePreStr."pals_request";
	$t_online=$tablePreStr."online";
	$table=$t_users;
	$dbo=new dbex();
	//定义读操作
	dbtarget('r',$dbServs);
	$now_year=date('Y');
	if($memName!=''){
		$cols.=" and user_name like '%$search_name%' ";
	}
	
	if($q_province!=$mp_langpackage->mp_province && $q_province!='' && $q_province!=$mp_langpackage->mp_none_limit){
		$cols.=" and (birth_province like '%$q_province%') ";
	}
	if($s_province!=$mp_langpackage->mp_province && $s_province!='' && $s_province!=$mp_langpackage->mp_none_limit){
		$cols.=" and (reside_province like '%$s_province%') ";
	}
	
	if($q_city!=$mp_langpackage->mp_city && $q_city!='' && $q_city!=$mp_langpackage->mp_none_limit){
		$cols.=" and (birth_city like '%$q_city%') ";
	}
	if($s_city!=$mp_langpackage->mp_city && $s_city!='' && $s_city!=$mp_langpackage->mp_none_limit){
		$cols.=" and (reside_city like '%$s_city%') ";
	}
	
	if($sex!=''){
		$cols.=" and user_sex=$sex ";
	}
	if($age){
		$age=explode('|',$age);
		$cols.=" and $now_year-birth_year BETWEEN $age[0] and $age[1] ";
	}
	if($is_online==1){
		$table=$t_online;
		$cols.=" and hidden = 0 ";
	}
	$page_num=trim(get_argg('page'));
	$sql="select user_id,user_name,user_sex,birth_province,birth_city,reside_province,reside_city,user_ico from $table where $cols ";
	$dbo->setPages(20,$page_num);//设置分页
	$search_rs=$dbo->getRs($sql);
	$page_total=$dbo->totalPage;//分页总数
	//控制显示
		$isset_data="";
		$none_data="content_none";
		$isNull=0;
	if(empty($search_rs)){
		$isset_data="content_none";
		$none_data="";
		$isNull=1;
	}
	?>
<?php
	//引入公共模块
	require("foundation/fpages_bar.php");
	require("foundation/module_users.php");
	
	require("foundation/module_share.php");
	
	//语言包引入
	$s_langpackage=new sharelp;
	$rf_langpackage=new recaffairlp;
	$mn_langpackage=new menulp;
	
	//变量区
	$ses_uid=get_sess_userid();
	
	//当前页面参数
	$page_num=trim(get_argg('page'));
	
	//数据表定义
	$t_share=$tablePreStr."share";
	$t_users=$tablePreStr."users";
	dbtarget('r',$dbServs);
	$dbo=new dbex;
	
	$share_rs=array();
	$pals_id=get_sess_mypals();
	$id_cols="user_id in ($pals_id.0) and is_pass=1 ";
	$order_by="order by add_time desc";
	$type='getRs';
	$dbo->setPages(20,$page_num);//设置分页	
	$share_rs=get_db_data($dbo,$t_share,$id_cols,$order_by,$type);
	$page_total=$dbo->totalPage;//分页总数
	
	//控制数据显示
	$content_data_none="content_none";
	$content_data_set="";
	$isNull=0;
	if(empty($share_rs)){
	$isNull=1;
	$content_data_none="";
	$content_data_set="content_none";
	}
?>
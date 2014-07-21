<?php
	//引入公共模块
	require("foundation/module_users.php");
	require("foundation/module_share.php");
	require("api/base_support.php");

	//语言包引入
	$s_langpackage=new sharelp;
	$rf_langpackage=new recaffairlp;
	$g_langpackage=new grouplp;
	$mn_langpackage=new menulp;

	//变量区
	$url_uid=intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();
	$share_id=intval(get_argg('s_id'));
	$is_admin=get_sess_admin();

	//引入模块公共权限过程文件
	$is_login_mode='';
	$is_self_mode='partLimit';
	require("foundation/auser_validate.php");

	//当前页面参数
	$page_num=intval(get_argg('page'));

	//数据表定义
	$t_share=$tablePreStr."share";
	$t_share_com=$tablePreStr."share_comment";
	$t_users=$tablePreStr."users";

	dbtarget('r',$dbServs);
	$dbo=new dbex;
	$error_str='';
	$share_row='';
	$id_cols="s_id = $share_id";
	$order_by='';
	$type='getRow';
	if($share_id){
		$share_row=get_db_data($dbo,$t_share,$id_cols,$order_by,$type);
		if($share_row['type_id']==6){
			$link_re=$share_row['out_link'];
		}else{
			$link_re=$share_row['movie_link'];
		}
		//操作显示控制
		$action_ctrl='content_none';
		if($share_row['user_id']==$ses_uid){
			$action_ctrl='';
		}
		$share_com_rs=array();
		$type="getRs";
		$dbo->setPages(20,$page_num);//设置分页
		$comment_rs=get_db_data($dbo,$t_share_com,$id_cols,$order_by,$type);
		$page_total=$dbo->totalPage;//分页总数
		
		$isNull=0;
		$show_com='';
		$show_error='content_none';
		$show_out_link='';
	
		//锁定控制
		if($share_row['is_pass']==0 && $is_admin==''){
			$show_error='';
			$error_str=$s_langpackage->s_lock;
		}		
	}

	//控制数据显示
	if(empty($comment_rs)){
		$isNull=1;
		$show_com='content_none';
	}

	if(empty($share_row)){
		$show_error='';
		$error_str=$s_langpackage->s_none;
		$show_com='content_none';
		$show_out_link='content_none';
		$isNull=1;
	}

	$holder_name=get_hodler_name($url_uid);
	?>
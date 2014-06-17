<?php
	//引入语言包
	$gu_langpackage=new guestlp;

	//引入公共模块
	require("foundation/fcontent_format.php");
	require("api/base_support.php");

	//变量取得
	$url_uid= intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();
	$guest_user_name=get_sess_username();
	$guest_user_ico=get_sess_userico();
	$mypals=get_sess_mypals();

	//引入模块公共权限过程文件
	$is_login_mode='';
	$is_self_mode='partLimit';
	require("foundation/auser_validate.php");

	//数据表定义区
	$t_guest = $tablePreStr."guest";
	$t_users = $tablePreStr."users";

	$dbo=new dbex;

	
	//加为好友 打招呼
	$add_friend="mypalsAddInit";
	$send_hi="hi_action";
	if(!$ses_uid){
	  	$add_friend='goLogin';
	  	$send_hi='goLogin';
	}
	
	//读写分离定义方法
	dbtarget('r',$dbServs);
	$guest_rs = api_proxy("guest_self_by_uid","*",$userid,5);
	if($is_self=='N'&&$ses_uid!=''){
		//读写分离定义方法
		dbtarget('w',$dbServs);
		if(empty($guest_rs)&&$ses_uid!=$url_uid){
			$sql = "update $t_users set guest_num=guest_num+1 where user_id=$url_uid";
			$dbo ->exeUpdate($sql);
		}else{
			if($guest_rs[0]['guest_user_id']!=$ses_uid){
				$sql = "update $t_users set guest_num=guest_num+1 where user_id=$url_uid";
				$dbo ->exeUpdate($sql);
			}
		}
		$sql = "delete from $t_guest where guest_user_id=$ses_uid and user_id=$url_uid";
		$dbo ->exeUpdate($sql);
		$sql = "insert into $t_guest (`guest_user_id`,`guest_user_name`,`guest_user_ico`,`user_id`,`add_time`) values($ses_uid,'$guest_user_name','$guest_user_ico',$url_uid,now())";
		$dbo ->exeUpdate($sql);
		if(count($guest_rs)>20){
			$sql = "delete from $t_guest where user_id=$url_uid order by add_time LIMIT 1";
			$dbo ->exeUpdate($sql);
		}
	}
?>
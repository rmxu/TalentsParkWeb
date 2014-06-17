<?php
//引入心情模块公共方法
	require("foundation/module_mood.php");
	require("foundation/module_users.php");
	require("foundation/fgrade.php");
	require("foundation/fdnurl_aget.php");
	require("api/base_support.php");
	require("foundation/auser_mustlogin.php");

//引入语言包
	$mo_langpackage=new moodlp;
	$u_langpackage=new userslp;
	$rf_langpackage=new recaffairlp;
	$ah_langpackage=new arrayhomelp;
	
	$user_id=get_sess_userid();

	//数据表定义区
	$t_mood=$tablePreStr."mood";

	$dbo=new dbex;
	//读写分离定义方法
	dbtarget('r',$dbServs);

	//获得最新的心情
	$last_mood_rs=get_last_mood($dbo,$t_mood,$user_id);
	$last_mood_txt='';
	if(isset($last_mood_rs['mood'])){
		$last_mood_txt=sub_str($last_mood_rs['mood'],35).' [<a href="modules.php?app=mood_more">'.$mo_langpackage->mo_more_label.'</a>]';
	}else{
		$last_mood_txt=$mo_langpackage->mo_null_txt;
	}
		$user_info=api_proxy("user_self_by_uid","guest_num,integral",$user_id);
		$remind_rs=api_proxy("message_get","remind",1,5);//取得空间提醒
		$remind_count=api_proxy("message_get_remind_count");//取得空间提醒数量
?>
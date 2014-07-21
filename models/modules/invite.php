<?php
//引入公共方法
	require("foundation/module_mood.php");

//引入语言包
	$u_langpackage=new userslp;
	$ah_langpackage=new arrayhomelp;

 //变量获得
  $holder_id=intval(get_argg('uid'));//主人id

	//数据表定义区
	$t_users=$tablePreStr."users";
	$t_online=$tablePreStr."online";

	$dbo=new dbex;
	//读写分离定义方法
	dbtarget('r',$dbServs);

	$info_item_init=$u_langpackage->u_unset;
  $user_info=get_user_info($dbo,$t_users,$holder_id);
  $user_sex_txt=get_user_sex($user_info['user_sex']);
  $user_lastlogin_time=$user_info['lastlogin_datetime'];

  if(!$user_info){
  	 echo $u_langpackage->u_not_invite;exit;
  }

	$user_online=get_user_online_state($dbo,$t_online,$holder_id);
  $ol_state_ico="skin/default/jooyea/images/offline.gif";
  $ol_state_label=$u_langpackage->u_not_onl.'('.format_datetime_short($user_lastlogin_time).')';
  if($user_online['hidden']==='0'){
	  $ol_state_ico="skin/default/jooyea/images/online.gif";
	  $ol_state_label=$u_langpackage->u_onl;
  }
  
  //设置发出邀请用户的id
  set_session("InviteFromUid",$user_info['user_id']);
?>
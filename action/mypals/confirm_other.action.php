<?php
//引入语言包
	$m_langpackage=new msglp;
	$mp_langpackage=new mypalslp;
	require("api/base_support.php");

//变量区
  $user_id=get_sess_userid();
  $user_name=get_sess_username();
  $userico=get_sess_userico();
  $my_pals=get_sess_mypals();
  $req_id=intval(get_argg('req_id'));
  $req_user_id=intval(get_argg('req_user_id'));

//数据表定义区
	$t_users=$tablePreStr."users";
	$t_mypals=$tablePreStr."pals_mine";
	$t_pals_request=$tablePreStr."pals_request";
	$dbo=new dbex();
//定义写操作
	dbtarget('w',$dbServs);
	
	//判断好友列表里是否已有该人
	if(api_proxy("pals_self_isset",$user_id,$req_user_id)){	
		$accepted=2;
		$sql="update $t_mypals set accepted=2 where user_id=$user_id and pals_id=$req_user_id";
		$dbo->exeUpdate($sql);
	}
	else{
		$accepted=1;
	}
	$sql="update $t_mypals set accepted=$accepted where pals_id=$user_id and user_id=$req_user_id";
	$dbo->exeUpdate($sql);

	$sql="delete from $t_pals_request where user_id=$user_id and id=$req_id";
	$dbo->exeUpdate($sql);
	
	$title=$user_name.$m_langpackage->m_agr_app;
	$scrip_content=$user_name.$m_langpackage->m_agree_app;
	$is_success=api_proxy('scrip_send',$mp_langpackage->mp_system_sends,$title,$scrip_content,$req_user_id,0);
	if($is_success){
		api_proxy("message_set",$req_user_id,"{num}".$mp_langpackage->mp_a_notice,"modules.php?app=msg_notice",0,1,"remind");
		action_return(2,$mp_langpackage->mp_treatment_success,-1);
	}else{
		action_return(2,$mp_langpackage->mp_treatment_failure,-1);
	}
?>
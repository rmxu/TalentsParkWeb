<?php
require("session_check.php");
require("../api/base_support.php");
	$is_check=check_rights("c02");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//变量区
	if(empty($user_id)){
		$user_id=intval(get_argg('user_id'));
		$type_value=short_check(get_argg('type_value'));
	}
	$dbo = new dbex;
	dbtarget('w',$dbServs);
	
	//表定义区
	$t_users=$tablePreStr."users";
	
	$sql="update $t_users set is_pass='$type_value' where user_id='$user_id'";
	$dbo->exeUpdate($sql);
	
	//发送锁定通知
	if($type_value==0){
		$sql = "select user_name,user_id from $t_users where user_id='$user_id'";
		$notice = $dbo->getRow($sql);
		$title = "您的".$notice['user_name']."帐号已被锁定";
		$scrip_content = "很抱歉，您的帐号".$notice['user_name']."因违反本站协议已被锁定。";
		$is_success = api_proxy('scrip_send',"系统发送",$title,$scrip_content,$notice['user_id'],0);
		if($is_success){
			api_proxy("message_set",$notice['user_id'],"{num}个通知","modules.php?app=msg_notice",0,1,"remind");
		}
	}
?>
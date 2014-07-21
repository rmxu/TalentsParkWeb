<?php
require("session_check.php");
require("../api/base_support.php");
	$is_check=check_rights("c12");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//变量区
	$pid=intval(get_argg('pid'));
	$type_value=intval(get_argg('type_value'));

	$dbo = new dbex;
	dbtarget('w',$dbServs);
	
	//表定义区
	$t_poll=$tablePreStr."poll";
	
	$sql="update $t_poll set is_pass=$type_value where p_id=$pid";
	$dbo->exeUpdate($sql);
	
	//发送锁定通知
	if($type_value==0){
		$sql = "select subject,username,user_id from $t_poll where p_id='$pid'";
		$notice = $dbo->getRow($sql);
		$title = "您的".$notice['subject']."投票已被锁定";
		$scrip_content = $notice['username']."，您的投票".$notice['subject']."因违反本站协议已被锁定，请您尽快修改，否则由管理员对您的信息进行修改和删除等操作所产生的一切后果，将由您自己承担。";
		$is_success = api_proxy('scrip_send',"系统发送",$title,$scrip_content,$notice['user_id'],0);
		if($is_success){
			api_proxy("message_set",$notice['user_id'],"{num}个通知","modules.php?app=msg_notice",0,1,"remind");
		}
	}
?>
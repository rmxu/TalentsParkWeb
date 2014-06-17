<?php
require("session_check.php");
require("../api/base_support.php");
	$is_check=check_rights("c14");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//变量区
	if(empty($group_id)){
		$group_id=intval(get_argg('group_id'));
		$type_value=intval(get_argg('type_value'));
	}
	$dbo = new dbex;
	dbtarget('w',$dbServs);
	
	//表定义区
	$t_group=$tablePreStr."groups";
	
	$sql="update $t_group set is_pass=$type_value where group_id=$group_id";
	$dbo->exeUpdate($sql);
	
	//发送锁定通知
	if($type_value==0){
		$sql = "select group_name,group_creat_name,add_userid from $t_group where group_id='$group_id'";
		$notice = $dbo->getRow($sql);
		$title = "您的".$notice['group_name']."群组已被锁定";
		$scrip_content = $notice['group_creat_name']."，您的群组".$notice['group_name']."因违反本站协议已被锁定，请您尽快修改，否则由管理员对您的信息进行修改和删除等操作所产生的一切后果，将由您自己承担。";
		$is_success = api_proxy('scrip_send',"系统发送",$title,$scrip_content,$notice['add_userid'],0);
		if($is_success){
			api_proxy("message_set",$notice['add_userid'],"{num}个通知","modules.php?app=msg_notice",0,1,"remind");
		}
	}
?>
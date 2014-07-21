<?php
require("session_check.php");
require("../api/base_support.php");
	$is_check=check_rights("c24");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//变量区
	$album_id=intval(get_argg('album_id'));
	$type_value=short_check(get_argg('type_value'));

	$dbo = new dbex;
	dbtarget('w',$dbServs);
	
	//表定义区
	$t_album=$tablePreStr."album";
	$t_photo=$tablePreStr."photo";
	
	$sql="update $t_album set is_pass=$type_value where album_id=$album_id";
	$dbo->exeUpdate($sql);
	
	$sql="update $t_photo set is_pass=$type_value where album_id=$album_id";
	$dbo->exeUpdate($sql);
	
	//发送锁定通知
	if($type_value==0){
		$sql = "select album_info,user_name,user_id from $t_album where album_id='$album_id'";
		$notice = $dbo->getRow($sql);
		$title = "您的".$notice['album_info']."相册已被锁定";
		$scrip_content = $notice['user_name']."，您的相册".$notice['album_info']."因违反本站协议已被锁定，请您尽快修改，否则由管理员对您的信息进行修改和删除等操作所产生的一切后果，将由您自己承担。";
		$is_success = api_proxy('scrip_send',"系统发送",$title,$scrip_content,$notice['user_id'],0);
		if($is_success){
			api_proxy("message_set",$notice['user_id'],"{num}个通知","modules.php?app=msg_notice",0,1,"remind");
		}
	}
?>
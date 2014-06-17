<?php
function del_user($dbo,$delUid){
	global $tablePreStr;
	global $t_langpackage;
	
	//数据表定义区
	$t_users=$tablePreStr."users";
	$t_album=$tablePreStr."album";
	$t_photo=$tablePreStr."photo";
	$t_blog=$tablePreStr."blog";
	$t_blog_sort=$tablePreStr."blog_sort";
	$t_recent_affair=$tablePreStr."recent_affair";
	$t_uploadfile=$tablePreStr."uploadfile";
	$t_data_count=$tablePreStr."data_count";
	$t_uploadfile=$tablePreStr."uploadfile";
	$t_recommend=$tablePreStr."recommend";
	$t_msg_inbox=$tablePreStr."msg_inbox";
	$t_msg_outbox=$tablePreStr."msg_outbox";
	$t_hi=$tablePreStr."hi";
	$t_msgboard=$tablePreStr."msgboard";
	$t_share=$tablePreStr."share";
	$t_pals_mine=$tablePreStr."pals_mine";
	$t_pals_request=$tablePreStr."pals_request";
	$t_pals_sort=$tablePreStr."pals_sort";
	$t_mood=$tablePreStr."mood";
	$show_str='';
	$del_uid='';
	
	//删除测试数据开始...
	if(empty($delUid)){
		$user_email_array=array('test1@jooyea.net','test2@jooyea.net');
		$user_name_array=array('测试帐号1','测试帐号2');
		
		//检测是否有测试数据
		$user_email_str='';
		$user_name_str='';
		foreach($user_email_array as $user_email){
			if($user_email_str==''){
				$user_email_str="'".$user_email."'";
			}else{
				$user_email_str.=",'".$user_email."'";
			}
		}
		foreach($user_name_array as $user_name){
			if($user_name_str==''){
				$user_name_str="'".$user_name."'";
			}else{
				$user_name_str.=",'".$user_name."'";
			}
		}
		$sql="select user_id,user_ico from $t_users where user_email in ($user_email_str) and user_name in ($user_name_str) ";
		$del_data=$dbo->getRs($sql);
		if(!empty($del_data)){
			foreach($del_data as $rs){
				if($del_uid==''){
					$del_uid=$rs['user_id'];
				}else{
					$del_uid.=",".$rs['user_id'];
				}
				if(!preg_match("/skin/",$rs['user_ico'])){
					unlink("../".$rs['user_ico']);
				}
			}
			$show_str.=str_replace("{num}",$dbo->rowCount,$t_langpackage->t_total_del_num);
			$show_str.=$t_langpackage->t_del_start;
		}else{
			echo $t_langpackage->t_none_test;exit;
		}
	}else{
		$del_uid=$delUid;
	}
	
	//用户表
	$sql="delete from $t_users where user_id in ($del_uid)";
	if($dbo->exeUpdate($sql)&&$dbo->affected_rows($sql)>0){
		$sql="ALTER TABLE `$t_users` AUTO_INCREMENT =1";
		$dbo->exeUpdate($sql);
	}else{
		echo '没有此用户';exit;
	}
	
	$$t_hi="打招呼";
	$$t_msgboard="留言板";
	$$t_msg_outbox="收件箱";
	$$t_album="相册";
	$$t_photo="照片";
	$$t_blog="日志";
	$$t_blog_sort="日志分类";
	$$t_recent_affair="新鲜事";
	$$t_uploadfile="上传图片";
	$$t_data_count="统计资料";
	$$t_recommend="推荐信息";
	$$t_share="分享";
	$$t_mood="心情";
	$$t_pals_sort="好友分类";
	$$t_pals_mine="朋友圈";	
	
	$array_table_to=array($t_hi,$t_msgboard,$t_msg_outbox);
	$array_table=array($t_hi,$t_msgboard,$t_msg_outbox,$t_album,$t_photo,$t_blog,$t_blog_sort,$t_recent_affair,$t_uploadfile,$t_data_count,$t_recommend,$t_share,$t_mood,$t_pals_sort,$t_pals_mine);
	foreach($array_table as $key => $table){
		$col=" user_id ";
		if(in_array($table,$array_table_to)){
			$col=" to_user_id ";
		}
		$sql="delete from $table where $col in ($del_uid)";
		if($dbo->exeUpdate($sql)){
			$sql="ALTER TABLE `$table` AUTO_INCREMENT =1";
			$dbo->exeUpdate($sql);
			$show_str.="清理".$$table."成功！<br>";
		}else{
			$show_str.="<font color=red>清理".$$table."失败！</font><br>";
		}
	}
	
	//好友申请
	$sql="delete from $t_pals_request where user_id in ($del_uid) or req_id in ($del_uid)";
	if($dbo->exeUpdate($sql)){
		$sql="ALTER TABLE `$t_pals_request` AUTO_INCREMENT =1";
		$dbo->exeUpdate($sql);
	}
	
	//照片文件
	$sql="select photo_src , photo_thumb_src from $t_photo where user_id in ($del_uid)";
	$photo_array=$dbo->getRs($sql);
	foreach($photo_array as $val){
		unlink("../".$val['photo_src']);
		unlink("../".$val['photo_thumb_src']);
	}
	
	//上传照片
	$sql="select file_src from $t_uploadfile where user_id in ($del_uid)";
	$upload_array=$dbo->getRs($sql);
	foreach($upload_array as $pic){
		unlink("../".$pic['file_src']);
	}
	
	//推荐会员
	$sql="select show_ico from $t_recommend where user_id in ($del_uid)";
	$recom_rs=$dbo->getRs($sql);
	foreach($recom_rs as $value){
		unlink("../".$value['show_ico']);
	}
	return $show_str;
}

?>
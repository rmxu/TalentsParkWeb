<?php
include(dirname(__file__)."/../includes.php");

//消息机制写操作
function message_set($id='',$title,$content='',$type=0,$is_mod=0,$mod='affair'){
	$title=addslashes(short_check($title));
	$content=addslashes($content);
	switch ($mod){
		case "remind":
		$touid=intval($id);
		$link=$content;
		$is_focus=intval($type);
		$type=intval($is_mod);
		return message_set_remind($touid,$title,$link,$type,$is_focus);
		break;
		default:
		$id=intval($id);
		$show_type_id=intval($type);
		$mod_type=intval($is_mod);
		return message_set_affair($id,$title,$content,$show_type_id,$mod_type,$mod);
		break;
	}
}

function message_set_del($type,$rid='',$uid=''){
	$rid=intval($rid);
	switch($type){
		case "remind":
		return message_del_remind($rid,$uid);
		break;
		case "affair":
		return message_del_affair($rid='');
		break;
		default:
		echo 'error';
		break;
	}
}

function message_set_affair($id,$title,$content,$show_type_id,$mod_type,$to_user_id){
	global $tablePreStr;
	$t_recent_affair=$tablePreStr."recent_affair";
	$t_users=$tablePreStr."users";
	$dbo=new dbex;
  dbplugin('w');
  if(intval($to_user_id)){
  	$sql="select user_name,user_ico from $t_users where user_id=$to_user_id";
  	$user_info=$dbo->getRow($sql);
  	$uid=$to_user_id;
  	$userico=$user_info['user_ico'];
  	$uname=$user_info['user_name'];
  }else{
		$uid=get_sess_userid();
		$userico=get_sess_userico();
		$uname=get_sess_username();
	}
	$title=htmlspecialchars_decode($title);
	$content=htmlspecialchars_decode($content);
	$sql="delete from $t_recent_affair where for_content_id=$id and mod_type=$mod_type and user_id=$uid";
	$dbo->exeUpdate($sql);
	$sql=" insert into $t_recent_affair (title,content,user_id,user_name,user_ico,date_time,update_time,type_id,mod_type,for_content_id) values ('$title','$content',$uid,'$uname','$userico',NOW(),NOW(),$show_type_id,$mod_type,$id) ";
	return $dbo->exeUpdate($sql);
}

function message_set_remind($touid,$content,$link,$type,$is_focus){
	$uid=get_sess_userid();
	$userico=get_sess_userico();
	$uname=get_sess_username();
	global $tablePreStr;
	$t_remind=$tablePreStr."remind";
	$dbo=new dbex;
	dbplugin('w');
	$content=htmlspecialchars_decode($content);
	$link=htmlspecialchars_decode($link);
	if($is_focus==0){
		$update_con=" and type_id = $type ";
	}else{
		$update_con=" and link = '$link' ";
	}
	$sql_check=" select id from $t_remind where user_id=$touid $update_con ";
	$is_set=$dbo->getRow($sql_check);
	if(empty($is_set)){
		$sql=" insert into $t_remind (user_id,type_id,date,content,is_focus,from_uid,from_uname,from_uico,link) values ($touid,$type,NOW(),'$content',$is_focus,$uid,'$uname','$userico','$link') ";
	}else{
		$sql=" update $t_remind set count = count+1,date = NOW() where user_id = $touid $update_con ";
	}
	return $dbo->exeUpdate($sql);
}

function message_del_remind($rid,$uid){
	$uid=intval($uid) ? $uid : get_sess_userid();
	$condition=intval($rid) ? " and id=$rid ":" limit 1 ";
	global $tablePreStr;
  $t_remind=$tablePreStr."remind";
	$dbo=new dbex;
  dbplugin('w');
  $sql="delete from $t_remind where user_id=$uid $condition ";
  return $dbo->exeUpdate($sql);
}

function message_del_affair($rid){
	global $tablePreStr;
  $t_recent_affair=$tablePreStr."recent_affair";
	$dbo=new dbex;
  dbplugin('w');
  $uid=get_sess_userid();
  $sql="delete from $t_recent_affair where id=$rid and user_id=$uid";
  return $dbo->exeUpdate($sql);
}
?>
<?php
include(dirname(__file__)."/../includes.php");

function message_get($mod,$is_focus=0,$num=10){
	switch ($mod){
		case "remind":
		$is_focus=intval($is_focus);
		return message_get_remind($is_focus,$num);
		break;
		default:
		echo 'error';
		break;
	}
}

function message_get_remind($is_focus,$num){
	$limit=intval($num) ? " limit $num ":"";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
  $uid=get_sess_userid();
	global $tablePreStr;
	$t_remind=$tablePreStr."remind";
	$sql="select * from $t_remind where user_id=$uid and is_focus=$is_focus order by id desc $limit ";
	$result_rs=$dbo->getRs($sql);
	return $result_rs;
}

function message_get_remind_count($uid=''){
	$uid=intval($uid);
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
  $uid=$uid ? $uid:get_sess_userid();
	global $tablePreStr;
	$t_remind=$tablePreStr."remind";
	$sql="select count(*) from $t_remind where user_id=$uid and is_focus=1";
	return $dbo->getRow($sql);
}

//查看用户新鲜事
function message_get_affair_uid($id,$type='',$num=20){
	$limit=intval($num) ? " limit $num ":"";
	$type_str=filt_num_array($type);
	$id_str=filt_num_array($id);
	$sql_type="";
	if($type_str!=''){
		$sql_type=" and mod_type in ($type_str) ";
	}
	global $tablePreStr;
	$t_recent_affair=$tablePreStr."recent_affair";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
  $sql="select * from $t_recent_affair where user_id in ($id_str) $sql_type order by id desc $limit ";
  $result_rs=$dbo->getALL($sql);
  return $result_rs;
}
?>
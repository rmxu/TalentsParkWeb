<?php
include(dirname(__file__)."/../includes.php");

//投票基础api函数
function poll_read_base($fields="*",$condition="",$get_type="",$num="",$by_col="p_id",$order="desc",$cache="",$cache_key=""){
	global $tablePreStr;
	global $page_num;
	global $page_total;
	global $is_self;
	$is_pass=' is_pass = 1 ';
	$is_admin=get_sess_admin();
	$t_poll=$tablePreStr."poll";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
	$by_col = $by_col ? " $by_col " : " p_id ";
	$order = $order ? $order:"desc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
	$is_pass = ($is_self=='Y' || $is_admin) ? '1' : $is_pass;
  $sql=" select $fields from $t_poll where $is_pass $condition order by $by_col $order ";
	if(empty($result_rs)){
		$dbo->setPages(20,$page_num);
		$result_rs=$dbo->{$get_type}($sql);
		$page_total=$dbo->totalPage;
	}
	return $result_rs;
}

function poll_self_by_pollid($fields="*",$id){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$get_type="";
	if(strpos($id_str,",")){
		$condition=" and p_id in ($id_str) ";
	}else{
		$condition=" and p_id = $id_str ";
		$get_type="getRow";
	}
	return poll_read_base($fields,$condition,$get_type);
}

function poll_self_by_uid($fields="*",$id){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$condition=strpos($id_str,',') ? " and user_id in ($id_str) ":" and user_id=$id_str ";
	return poll_read_base($fields,$condition);
}

function poll_self_by_date($fields="*",$date="",$by_col='voternum',$order="desc",$num=10){
	if($by_col!='voternum'){
		$by_col='comments';
	}
	$num=intval($num);
	$order=filt_order($order);
	$condition=str_replace("{date}","dateline",date_filter($date));
	return poll_read_base($fields,$condition,"",$num,$by_col,$order,1,"poll_".$by_col."_");
}

function poll_self_by_new($fields="*",$order="desc",$num=20){
	$fields=filt_fields($fields);
	return poll_read_base($fields,'','',$num,'dateline',$order,1,"new_");
}

function poll_self_by_hot($fields="*",$order="desc",$num=20){
	$fields=filt_fields($fields);
	return poll_read_base($fields,'','',$num,'voternum',$order,1,"hot_");
}

function poll_self_by_reward($fields="*",$order="desc",$num=20){
	$fields=filt_fields($fields);
	$condition=" and percredit>0 ";
	return poll_read_base($fields,$condition,'',$num,'credit',$order,1,"reward_");
}
?>
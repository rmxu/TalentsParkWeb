<?php
include(dirname(__file__)."/../includes.php");

//分享基础api函数
function share_read_base($fields="*",$condition="",$get_type="",$num="",$by_col="s_id",$order="desc",$cache="",$cache_key=""){
	global $page_num;
	global $page_total;
	global $tablePreStr;
	global $is_self;
	$is_pass=' is_pass = 1 ';
	$is_admin=get_sess_admin();
	$t_share=$tablePreStr."share";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
	$by_col = $by_col ? " $by_col " : " s_id ";
	$order = $order ? $order:"desc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
	$is_pass = ($is_self=='Y' || $is_admin) ? '1' : $is_pass;
  $sql=" select $fields from $t_share where $is_pass $condition order by $by_col $order ";
	if(empty($result_rs)){
  	$dbo->setPages(20,$page_num);
		$result_rs=$dbo->{$get_type}($sql);
		$page_total=$dbo->totalPage;
	}
	return $result_rs;
}

function share_self_by_sid($fields="*",$id){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$get_type="";
	if(strpos($id_str,",")){
		$condition=" and s_id in ($id_str) ";
	}else{
		$condition=" and s_id = $id_str ";
		$get_type="getRow";
	}
	return share_read_base($fields,$condition,$get_type);
}

function share_self_by_uid($fields="*",$id){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$condition=strpos($id_str,',') ? " and user_id in ($id_str) ":" and user_id=$id_str ";
	return share_read_base($fields,$condition);
}

function share_self_by_sort($fields="*",$sort,$num=10){
	$num=intval($num);
	$sort_str=filt_num_array($sort);
	$condition=strpos($sort_str,',') ? " and type_id in ($sort_str) ":" and type_id=$sort_str ";
	return share_read_base($fields,$condition,"",$num);
}
?>
<?php
include(dirname(__file__)."/../includes.php");

//分享基础api函数
function mood_read_base($fields="*",$condition="",$get_type="",$num="",$by_col="mood_id",$order="desc",$cache="",$cache_key=""){
	global $tablePreStr;
	global $page_num;
	global $page_total;
	$t_mood=$tablePreStr."mood";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
	$by_col = $by_col ? " $by_col " : " mood_id ";
	$order = $order ? $order:"desc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
  $sql=" select $fields from $t_mood where $condition order by $by_col $order ";
	if(empty($result_rs)){
		$dbo->setPages(20,$page_num);//设置分页
		$result_rs=$dbo->{$get_type}($sql);
		$page_total=$dbo->totalPage;//分页总数
	}
	return $result_rs;
}

function mood_self_by_mid($fields="*",$id){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$get_type="";
	if(strpos($id_str,",")){
		$condition=" mood_id in ($id_str) ";
	}else{
		$condition=" mood_id = $id_str ";
		$get_type="getRow";
	}
	return mood_read_base($fields,$condition,$get_type);
}

function mood_self_by_uid($fields="*",$id){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$get_type="";
	$condition=strpos($id_str,',') ? " user_id in ($id_str) ":" user_id=$id_str ";
	return mood_read_base($fields,$condition);
}
?>
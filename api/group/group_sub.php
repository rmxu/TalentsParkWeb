<?php
include(dirname(__file__)."/../includes.php");

//群组基础api函数
function group_sub_read_base($fields="*",$condition="",$get_type="",$num="",$by_col="subject_id",$order="desc",$cache="",$cache_key=""){
	global $tablePreStr;
	global $page_num;
	global $page_total;
	$t_group_subject=$tablePreStr."group_subject";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
	$by_col = $by_col ? " $by_col " : " subject_id ";
	$order = $order ? $order:"desc";
	$get_type = $get_type ? "getRow":"getRs";
  $sql=" select $fields from $t_group_subject where $condition order by $by_col $order ";
	if(empty($result_rs)){
  	$dbo->setPages(20,$page_num);
		$result_rs=$dbo->{$get_type}($sql);
		$page_total=$dbo->totalPage;
	}
	return $result_rs;
}

function group_sub_by_sid($fields="*",$id){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$get_type="";
	if(strpos($id_str,",")){
		$condition=" subject_id in ($id_str) ";
	}else{
		$condition=" subject_id = $id_str ";
		$get_type="getRow";
	}
	return group_sub_read_base($fields,$condition,$get_type);
}

function group_sub_by_gid($fields="*",$id,$num=10){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$condition=strpos($id_str,',') ? " group_id in ($id_str) ":" group_id=$id_str ";
	return group_sub_read_base($fields,$condition,'',$num);
}

function group_sub_by_uid($fields="*",$id,$num=10){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$condition=strpos($id_str,',') ? " user_id in ($id_str) ":" user_id=$id_str ";
	return group_sub_read_base($fields,$condition,'',$num);
}

function group_sub_by_date($fields="*",$date='',$col_by='hits',$order='desc',$num=10){
	$fields=filt_fields($fields);
	if($col_by!='hits'){
		$col_by="comments";
	}
	$order=filt_order($order);
	$condition=str_replace("{date}","add_time",date_filter($date));
	return group_sub_read_base($fields,$condition,'',$num,$col_by,$order);
}
?>
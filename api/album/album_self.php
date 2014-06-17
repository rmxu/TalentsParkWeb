<?php
include(dirname(__file__)."/../includes.php");

//相册基础api函数
function album_read_base($fields="*",$condition="",$get_type="",$num="",$by_col="album_id",$order="desc",$cache="",$cache_key=""){
	global $tablePreStr;
	global $page_num;
	global $page_total;
	global $is_self;
	$is_pass=' is_pass = 1 ';
	$is_admin=get_sess_admin();
	$t_album=$tablePreStr."album";
	$result_rs=array();
	$dbo=new dbex;
  	dbplugin('r');
  	$limit=$num ? " limit $num ":"";
	$by_col = $by_col ? " $by_col " : " album_id ";
	$order = $order ? $order:"desc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
	$is_pass = ($is_self=='Y' || $is_admin) ? '1' : $is_pass;
  	$sql=" select $fields from $t_album where $is_pass $condition order by $by_col $order $limit ";
	if(empty($result_rs)){
		if($limit==''){
  		$dbo->setPages(10,$page_num);
  	}
		$result_rs=$dbo->{$get_type}($sql);
		$page_total=$dbo->totalPage;
	}
	return $result_rs;
}

function album_self_by_aid($fields="*",$id){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$get_type="";
	if(strpos($id_str,",")){
		$condition=" and album_id in ($id_str) ";
	}else{
		$condition=" and album_id = $id_str ";
		$get_type="getRow";
	}
	return album_read_base($fields,$condition,$get_type);
}

function album_self_by_uid($fields="*",$id,$num=''){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$condition=strpos($id_str,',') ? " and user_id in ($id_str) ":" and user_id = $id_str ";
	return album_read_base($fields,$condition,'getRs',$num);
}

function album_self_by_coms($fields="*",$num=10){
	$num=intval($num);
	return album_read_base($fields,"","",$num,"comments","desc",1,"coms_");
}

function album_self_get_new($fields="*",$num=10){
	$num=intval($num);
	return album_read_base($fields,"","",$num,"","",1,"new_");
}
?>
<?php
include(dirname(__file__)."/../includes.php");

//日志基础api函数
function blog_read_base($fields="*",$condition="",$get_type="",$num="",$by_col="log_id",$order="desc",$cache="",$cache_key=""){
	global $tablePreStr;
	global $page_num;
	global $page_total;
	global $is_self;
	$is_pass=' is_pass = 1 ';
	$is_admin=get_sess_admin();
	$t_blog=$tablePreStr."blog";
	$result_rs=array();
	$dbo=new dbex;
  	dbplugin('r');
	$limit=$num ? " limit $num ":"";
	$by_col = $by_col ? " $by_col " : " log_id ";
	$order = $order ? $order:"desc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
	$is_pass = ($is_self=='Y' || $is_admin) ? '1' : $is_pass;
  $sql=" select $fields from $t_blog where $is_pass $condition order by $by_col $order $limit ";
	if(empty($result_rs)){
		if($limit==''){
  		$dbo->setPages(20,$page_num);
  	}
		$result_rs=$dbo->{$get_type}($sql);
		$page_total=$dbo->totalPage;
	}
	return $result_rs;
}

function blog_self_by_bid($fields="*",$id){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$get_type="";
	if(strpos($id_str,",")){
		$condition=" and log_id in ($id_str) ";
	}else{
		$condition=" and log_id = $id_str ";
		$get_type="getRow";
	}
	return blog_read_base($fields,$condition,$get_type);
}

function blog_self_by_uid($fields="*",$id,$sort_id='',$num=''){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$sort_str=filt_num_array($sort_id);
	$condition=strpos($id_str,',') ? " and user_id in ($id_str) ":" and user_id = $id_str ";
	if($sort_id!=''){
		$condition.=" and log_sort in ($sort_str) ";
	}
	return blog_read_base($fields,$condition,'getRs',$num);
}

function blog_self_by_hits($fields="*",$order="desc",$num=10){
	$num=intval($num);
	$order=filt_order($order);
	return blog_read_base($fields,"","","hits",$order,$num,1,"hits_");
}

function blog_self_get_new($fields="*",$num=10){
	$num=intval($num);
	return blog_read_base($fields,"","",$num,"","",1,"new_");
}

function blog_self_by_date($fields="*",$date='',$by_col='hits',$order="desc",$num=10){
	$num=intval($num);
	$fields=filt_fields($fields);
	$order=filt_order($order);
	if($by_col!='hits'){
		$by_col='comments';
	}
	$condition=str_replace("{date}","add_time",date_filter($date));
	return blog_read_base("*",$condition,"",$by_col,$order,$num);
}
?>
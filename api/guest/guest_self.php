<?php
include(dirname(__file__)."/../includes.php");

//用户基础api函数
function guest_read_base($fields="*",$condition="",$get_type="",$num="",$by_col="guest_id",$order="desc",$cache="",$cache_key=""){
	global $tablePreStr;
	global $page_num;
	global $page_total;
	$t_guest=$tablePreStr."guest";
	$fields_str='';
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
  $limit=$num ? " limit $num ":"";
	$by_col = $by_col ? " $by_col " : " guest_id ";
	$order = $order ? $order:"desc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
  $sql=" select $fields from $t_guest where $condition order by $by_col $order $limit ";
	if(empty($result_rs)){
		if($limit==''){
  		$dbo->setPages(20,$page_num);
  	}
		$result_rs=$dbo->{$get_type}($sql);
		$page_total=$dbo->totalPage;
	}
	return $result_rs;
}

function guest_self_by_uid($fields="*",$id,$num=5){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$condition=" user_id = $id_str ";
	return guest_read_base($fields,$condition,'',$num);
}

?>
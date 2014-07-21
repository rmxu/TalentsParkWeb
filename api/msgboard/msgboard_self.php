<?php
include(dirname(__file__)."/../includes.php");

//留言板基础api函数
function msgboard_read_base($fields="*",$condition="",$get_type="",$num="",$by_col="mess_id",$order="desc",$cache="",$cache_key=""){
	global $tablePreStr;
	global $page_num;
	global $page_total;
	global $cachePages;
	$t_msgboard=$tablePreStr."msgboard";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
  $limit=$num ? " limit $num ":"";
	$by_col = $by_col ? " $by_col " : " mess_id ";
	$order = $order ? $order:"desc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
  $sql=" select $fields from $t_msgboard where $condition order by $by_col $order $limit ";
  /*
  可以加入缓存机制
  */
	if(empty($result_rs)){
		if($limit==''){
			$dbo->setPages(20,$page_num);
		}
		$result_rs=$dbo->{$get_type}($sql);
		$page_total=$dbo->totalPage;
	}
	return $result_rs;
}

function msgboard_self_by_uid($fields="*",$uid="",$is_read="",$num="",$date=""){
	$uid = $uid ? $uid : get_sess_userid();
	$is_read=intval($is_read);
	$fields=filt_fields($fields);
	$condition=" to_user_id = $uid ";
	if($date!=''){
		$condition.=str_replace("{date}","add_time",date_filter($date));
	}
	if($is_read!=''){
		$condition.=" and readed = $is_read ";
	}
	return msgboard_read_base($fields,$condition,"getRs",$num);
}
?>
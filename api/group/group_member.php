<?php
include(dirname(__file__)."/../includes.php");

//群组基础api函数
function group_member_base($fields="*",$condition="",$get_type="",$num="",$by_col="role",$order="asc",$cache="",$cache_key=""){
	global $tablePreStr;
	global $page_num;
	global $page_total;
	$t_group_member=$tablePreStr."group_members";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
	$by_col = $by_col ? " $by_col " : " role ";
	$order = $order ? $order:"asc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
  $sql=" select $fields from $t_group_member where $condition order by $by_col $order ";
	if(empty($result_rs)){
  	$dbo->setPages(20,$page_num);
		$result_rs=$dbo->{$get_type}($sql);
		$page_total=$dbo->totalPage;
	}
	return $result_rs;
}

function group_member_manager($fields="*",$gid=''){
	global $tablePreStr;
	$fields=filt_fields($fields);
	$gid=intval($gid);
	$condition=" group_id=$gid and role<=1 ";
	return group_member_base($fields,$condition);
}

function group_member_by_role($gid="",$uid="")
{
	global $tablePreStr;
	$gid=intval($gid);
	$uid=intval($uid);
	$condition=" group_id=$gid and user_id=$uid and state=1";
	return group_member_base("role",$condition,"getRow");
}

function group_member_by_gid($fields="*",$gid="",$state=""){
	global $tablePreStr;
	$fields=filt_fields($fields);
	$gid=intval($gid);
	$condition=" group_id=$gid ";
	if($state !== '')
	{
		$state=intval($state);
		$condition .= " and state=$state ";
	}
	return group_member_base($fields,$condition);
}
?>
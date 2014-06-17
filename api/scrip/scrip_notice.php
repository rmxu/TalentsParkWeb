<?php
include(dirname(__file__)."/../includes.php");
//群组基础api函数
function scrip_notice_get($fields="*",$num="",$condition=""){
	global $tablePreStr;
	global $page_num;
	global $page_total;
	$fields=filt_fields($fields);
	$uid=get_sess_userid();
	$t_scrip=$tablePreStr."msg_inbox";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
  $sql=" select $fields from $t_scrip where user_id = $uid and mesinit_id='' $condition order by mess_id desc ";
	$dbo->setPages(20,$page_num);
	$result_rs=$dbo->getRs($sql);
	$page_total=$dbo->totalPage;
	return $result_rs;
}
?>
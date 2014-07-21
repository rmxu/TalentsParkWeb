<?php
include(dirname(__file__)."/../includes.php");

function blog_sort_by_uid($id){
	global $tablePreStr;
	$t_blog=$tablePreStr."blog_sort";
	$id=intval($id);
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
  $sql=" select * from $t_blog where user_id = $id ";
	$result_rs=$dbo->getRs($sql);
	return $result_rs;
}
?>
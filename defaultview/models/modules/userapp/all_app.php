<?php
$pl_langpackage=new pluginslp;
require("api/base_support.php");
require("foundation/fpages_bar.php");
$search_app=get_argp('search_app');
$def_image="skin/".$skinUrl."/images/plu_def.jpg";
$page_num=intval(get_argg('page'));
$app_rs=array();
if($search_app){
	$page_total='';
	$dbo=new dbex;
	dbtarget('r',$dbServs);
	$t_plugins = $tablePreStr."plugins";
	$search_app=short_check(get_argp('search_app'));
	$sql="select * from $t_plugins where `title` like '%$search_app%'";
	$app_rs=$dbo->getRs($sql);
	$isNull=1;
	$error_str=$pl_langpackage->pl_search_none;
}else{
	$app_rs=api_proxy("plugins_get_all");
	$isNull=0;
	if(empty($app_rs)){
		$isNull=1;
	}
	$error_str=$pl_langpackage->pl_none;
}
?>
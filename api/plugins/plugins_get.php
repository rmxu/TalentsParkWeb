<?php
include(dirname(__file__)."/../includes.php");

function plugins_get_all(){
	global $tablePreStr;
	global $page_num;
	global $page_total;
	$t_plugins=$tablePreStr."plugins";
	$t_plugin_url=$tablePreStr."plugin_url";
	$page_num = $page_num ? $page_num-1:0;
	$result_rs=array();
	$result_rs_total=array();
	$dbo=new dbex;
  dbplugin('r');
  $sql="select a.*,b.url from $t_plugins as a join $t_plugin_url as b on (a.name=b.name) where b.layout_id='plugin_app' order by a.id desc";
	$key="plugin/list/id";
	$key_mt="plugin/list/id_mt";
	$result_rs_total=model_cache($key,$key_mt,$dbo,$sql);
	if($result_rs_total){
		$key=$key.'_20_total';
		$sql_count="select count(*) as total_count ".strstr($sql,"from");//查询总数
		$total_row=model_cache($key,$key_mt,$dbo,$sql_count,"getRow");
		$result_rs_cut=array_chunk($result_rs_total,20);
		$result_rs=$result_rs_cut[$page_num];
		$page_total=floor(($total_row['total_count']-1)/20)+1;//总页数
	}else{
  	$dbo->setPages(20,$page_num);
  	$result_rs=$dbo->getRs($sql);
  	$page_total=$dbo->totalPage;
  }
	return $result_rs;
}

function plugins_get_pid($id,$get_type=''){
	global $tablePreStr;
	$t_plugins=$tablePreStr."plugins";
	$t_plugin_url=$tablePreStr."plugin_url";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
	$id_str=filt_num_array($id);
	$sql="select a.*,b.url from $t_plugins as a join $t_plugin_url as b on (a.name=b.name) where a.id =$id_str";
	$get_type=$get_type?$get_type:"getRow";
	if(strpos($id_str,",")){
		$sql="select a.*,b.url from $t_plugins as a join $t_plugin_url as b on (a.name=b.name) where a.id in ($id_str)";
		$get_type="getRs";
	}
	return $dbo->{$get_type}($sql);
}

function plugins_get_mine(){
	$u_apps=get_sess_apps();
	return plugins_get_pid($u_apps,"getRs");
}
?>
<?php
	require("session_check.php");	
	require("../foundation/aintegral.php");
	require("../api/Check_MC.php");
	$is_check=check_rights("c08");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//语言包引入
	$m_langpackage=new modulelp;
	
	//变量取得
	$recommend_id=intval(get_argg('recom_id'));
	$user_id=intval(get_argg('uid'));
	
	//数据表定义区
	$t_recommend=$tablePreStr."recommend";
	$t_users=$tablePreStr."users";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);
	$sql="select show_ico,user_ico from $t_recommend where recommend_id=$recommend_id";
	$recom=$dbo->getRow($sql);
	if($recom['show_ico']!=$recom['user_ico']){
		unlink("../".$recom['show_ico']);
	}
	$sql="delete from $t_recommend where recommend_id=$recommend_id";
	if($dbo -> exeUpdate($sql)){
		$sql1="update $t_users set is_recommend=0 where user_id=$user_id";
		$dbo -> exeUpdate($sql1);
		$key_mt='recommend/list/rec_order/all/0_mt';
		updateCache($key_mt);			
	}
?>

<?php
require("session_check.php");
require("../api/Check_MC.php");

	//变量取得
	$recommend_id=intval(get_argg('recom_id'));
	$rec_class=short_check(get_argg('recclass'));
	$rec_order=short_check(get_argg('order_value'));
	$order_num=array();
	$order_num=get_argp('order_num');

	//数据表定义区
	$t_recommend=$tablePreStr."recommend";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);
	
	if(get_argp('is_batch')){
		$is_check=check_rights("c09");
		if(!$is_check){
			echo $m_langpackage->m_no_pri;
			exit;
		}
		foreach($order_num as $recom_id => $o_num){
			$o_num=intval($o_num);
			$sql="update $t_recommend set rec_order=$o_num where recommend_id=$recom_id";
			$dbo->exeUpdate($sql);
		}
		echo '<script type="text/javascript">window.location.href="recommend_list.php?order_by=rec_order&order_sc=asc";</script>';
	}else{
		if($rec_class!=""){
			$is_check=check_rights("c05");
			if(!$is_check){
				echo $m_langpackage->m_no_pri;
				exit;
			}
			if(!$is_check){
				echo $m_langpackage->m_no_pri;
				exit;
			}
			$sql = "update $t_recommend set rec_class=$rec_class where recommend_id=$recommend_id";
			$dbo -> exeUpdate($sql);
		}else if($rec_order!=""){
			check_rights("c09");
			$sql="update $t_recommend set rec_order=$rec_order where recommend_id=$recommend_id";
			$dbo -> exeUpdate($sql);
		}
		$key_mt='recommend/list/rec_order/all/0_mt';
		updateCache($key_mt);
}

?>

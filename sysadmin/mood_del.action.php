<?php
	require("session_check.php");
	require("../foundation/aintegral.php");
	$is_check=check_rights("c38");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//语言包引入
	$m_langpackage=new modulelp;
	//读写分离
	$dbo = new dbex;
	dbtarget('w',$dbServs);
		
	//判断是否批量删除
	if(get_argp('checkany')){//批量
		//数据表定义区
		$t_mood=$tablePreStr."mood";
		$t_mood_com=$tablePreStr."mood_comment";
		$mood_ids = get_argp('checkany');
		foreach($mood_ids as $rs){
			//删除心情
			$sql = "delete from $t_mood where mood_id=$rs";
			$dbo -> exeUpdate($sql);
			$sql="delete from $t_mood_com where mood_id=$rs";
			$dbo->exeUpdate($sql);
		}
	}else{//单条
		//变量取得
		$mood_id=short_check(get_argg('mood'));
		$uid=intval(get_argg('uid'));
		//数据表定义区
		$t_mood=$tablePreStr."mood";
		$t_mood_com=$tablePreStr."mood_comment";
		//删除心情
		$sql = "delete from $t_mood where mood_id=$mood_id";
		if($dbo -> exeUpdate($sql)){
			$sql="delete from $t_mood_com where mood_id = $mood_id";
			$dbo->exeUpdate($sql);
			echo $m_langpackage->m_del_suc;
		}
	}
?>
<script language="javascript" type="text/javascript">
window.location.href='mood_list.php?order_by=mood_id&order_sc=desc';
</script>
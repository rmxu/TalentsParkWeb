<?php
	require("session_check.php");	
	require("../foundation/aintegral.php");
	$is_check=check_rights("c31");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//语言包引入
	$m_langpackage=new modulelp;
	//判断是否批量删除
	if(get_argp('checkany')){//批量
		$dbo = new dbex;
		dbtarget('r',$dbServs);
		dbtarget('w',$dbServs);
		$mess_ids = get_argp('checkany');
		//数据表定义
		$t_table=$tablePreStr."msgboard";
		foreach($mess_ids as $rs){
			//变量取得
			$sql="select * from $t_table where mess_id=$rs";
			$msgboards = $dbo->getRow($sql);
			$fu_id = $msgboards['from_user_id'];
			$to_uid = $msgboards['to_user_id'];
			//删除留言
			$sql = "delete from $t_table where mess_id=$rs";
			if($dbo -> exeUpdate($sql)){
				increase_integral($dbo,$int_del_com_msg,$fu_id);
			}
		}
	}else{//单条
		//变量取得
		$mess_id=intval(get_argg('mid'));
		$fu_id=intval(get_argg('fu_id'));
		$to_uid=intval(get_argg('to_uid'));
		//数据表定义区
		$t_table=$tablePreStr."msgboard";
		$dbo = new dbex;
		//读写分离定义函数
		dbtarget('w',$dbServs);
		//删除留言
		$sql = "delete from $t_table where mess_id=$mess_id";
		if($dbo -> exeUpdate($sql)){
			increase_integral($dbo,$int_del_com_msg,$fu_id);
			echo $m_langpackage->m_del_suc;
		}
	}
?>
<script language="javascript" type="text/javascript">
window.location.href='msgboard_list.php?order_by=mess_id&order_sc=desc';
</script>
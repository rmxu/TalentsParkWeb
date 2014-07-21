<?php
	require("session_check.php");	
	require("../foundation/aintegral.php");
	$is_check=check_rights("c11");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//语言包引入
	$m_langpackage=new modulelp;
	//读写分离
	$dbo = new dbex;
	dbtarget('w',$dbServs);
	dbtarget('r',$dbServs);
	
	//判断是否批量删除
	if(get_argp('checkany')){//批量
		//数据表定义区
		$t_poll=$tablePreStr."poll";
		$t_users=$tablePreStr."users";
		$t_polloption=$tablePreStr."polloption";
		$t_polluser=$tablePreStr."polluser";
		$t_poll_com=$tablePreStr."poll_comment";
		$poll_ids = get_argp('checkany');
		foreach($poll_ids as $rs){
			$sql="select * from $t_poll where p_id=$rs";
			$dbo->getRow($sql);
			$polls=$dbo->getRow($sql);
			$sendor_id=$polls['user_id'];
			$sql="delete from $t_poll where p_id=$rs";
			$dbo->exeUpdate($sql);
			$sql="delete from $t_polloption where pid=$rs";
			$dbo->exeUpdate($sql);
			$sql="delete from $t_polluser where pid=$rs";
			$dbo->exeUpdate($sql);
			$sql="delete from $t_poll_com where p_id=$rs";
			$dbo->exeUpdate($sql);
			increase_integral($dbo,$int_del_poll,$sendor_id);
		}
	}else{
		//变量区
		if(empty($pid)){
			$pid=intval(get_argg('poll_id'));
			$sendor_id=intval(get_argg('sendor_id'));
		}
		//数据表定义区
		$t_users=$tablePreStr."users";
		$t_poll=$tablePreStr."poll";
		$t_polloption=$tablePreStr."polloption";
		$t_polluser=$tablePreStr."polluser";
		$t_poll_com=$tablePreStr."poll_comment";
		$sql="delete from $t_poll where p_id=$pid";
		$dbo->exeUpdate($sql);
		$sql="delete from $t_polloption where pid=$pid";
		$dbo->exeUpdate($sql);
		$sql="delete from $t_polluser where pid=$pid";
		$dbo->exeUpdate($sql);
		$sql="delete from $t_poll_com where p_id=$pid";
		$dbo->exeUpdate($sql);
			
		increase_integral($dbo,$int_del_poll,$sendor_id);
		//回应信息
		echo $m_langpackage->m_del_suc;
	}
	
?>
<script language="javascript" type="text/javascript">
window.location.href='poll_list.php?order_by=p_id&order_sc=desc';
</script>
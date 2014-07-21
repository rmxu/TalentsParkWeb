<?php
	require("session_check.php");
	//语言包引入
	$m_langpackage=new modulelp;
	$is_check=check_rights("c21");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//判断是否批量删除
	if(get_argp('checkany')){//批量
		$dbo = new dbex;
		dbtarget('w',$dbServs);
		$t_affair=$tablePreStr."recent_affair";
		$aff_ids = get_argp('checkany');
		foreach($aff_ids as $rs){
			$sql="delete from $t_affair where id=$rs";
			$dbo->exeUpdate($sql);
		}
	}else{//单个
		//变量区
		$aff_id=intval(get_argg('aff_id'));
		$dbo = new dbex;
		dbtarget('w',$dbServs);
		//表定义区
		$t_affair=$tablePreStr."recent_affair";
		$sql="delete from $t_affair where id=$aff_id";
		if($dbo->exeUpdate($sql)){
			echo $m_langpackage->m_del_suc;
		}
	}
?>
<script language="javascript" type="text/javascript">
window.location.href='affair_list.php?order_by=id&order_sc=desc';
</script>
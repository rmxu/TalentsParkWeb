<?php
	require("session_check.php");
	require("../foundation/aintegral.php");
	require("../foundation/module_affair.php");
	$is_check=check_rights("c34");
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
		$share_ids = get_argp('checkany');
		//数据表定义
		$t_share=$tablePreStr."share";
		foreach($share_ids as $rs){
			//变量取得
			$sql="select * from $t_share where s_id=$rs";
			$shares = $dbo->getRow($sql);
			$u_id = $shares['user_id'];
			
			//删除评论
			$sql = "delete from $t_share where s_id=$rs";
			if($dbo -> exeUpdate($sql)){
				del_affair($dbo,5,$rs);
				increase_integral($dbo,$int_del_share,$u_id);
			}
		}
	}else{//单条
		//变量取得
		if(empty($share_id)){
			$share_id=intval(get_argg('sid'));
			$u_id=intval(get_argg('u_id'));
		}
		//数据表定义区
		$t_share=$tablePreStr."share";
		$dbo = new dbex;
		//读写分离定义函数
		dbtarget('w',$dbServs);
		//删除评论
		$sql = "delete from $t_share where s_id=$share_id";
		if($dbo -> exeUpdate($sql)){
			del_affair($dbo,5,$share_id);
			increase_integral($dbo,$int_del_share,$u_id);
			echo $m_langpackage->m_del_suc;
		}
	}
?>
<script language="javascript" type="text/javascript">
window.location.href='share_list.php?order_by=s_id&order_sc=desc';
</script>
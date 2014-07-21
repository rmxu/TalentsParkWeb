<?php
require("session_check.php");
	$is_check=check_rights("c03");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//语言包引入
	$m_langpackage=new modulelp;
	
	//变量区
	$user_id=intval(get_argg('user_id'));
	
	$dbo = new dbex;
	dbtarget('w',$dbServs);
	
	//表定义区
	$t_users=$tablePreStr."users";
	
	$sql="delete from $t_users where user_id=$user_id";
	$dbo->exeUpdate($sql);
	echo $m_langpackage->m_del_suc;
?>
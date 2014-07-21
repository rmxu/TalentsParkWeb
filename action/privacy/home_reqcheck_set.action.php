<?php
	//引入语言包
	$pr_langpackage=new privacylp;

	//变量获得
	$user_id=get_sess_userid();
	$palsreq_para=short_check(get_argp('palsreq_check'));

	//数据表定义区
	$t_users=$tablePreStr."users";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);

	$sql="update $t_users set palsreq_limit=$palsreq_para where user_id=$user_id";
	$dbo ->exeUpdate($sql);
	action_return(1,$pr_langpackage->pr_save_sec,"modules.php?app=pr_reqcheck");

?>
<?php
	//引入语言包
	$u_langpackage=new userslp;

	//变量获得
	$user_id =get_sess_userid();
	$ol_is_latent=intval(get_argg('is_hidden'));
echo $ol_is_latent;
	//表声明区
	$t_users = $tablePreStr."users";
	$t_online=$tablePreStr."online";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);

//更新online表
	$sql = "update $t_online set `hidden`=$ol_is_latent where user_id=$user_id;";
	if($dbo -> exeUpdate($sql)){
		 set_sess_online($ol_is_latent);
		 echo $u_langpackage->u_set_suc;
	}else{
		 echo $u_langpackage->u_set_los;
	}
?>

<?php
	//语言包引入
	$u_langpackage=new userslp;
	//变量取得
	$user_id = get_sess_userid();
	$dress_name = short_check(get_argg('dress_name'));

	//数据表定义区
	$t_users = $tablePreStr."users";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);
	$sql="update $t_users set dressup = '$dress_name' where user_id = $user_id";
	if($dbo->exeUpdate($sql)!==false){
		set_session($user_id."_dressup",$dress_name);
		action_return(1,'','home.php?h='.$user_id);
	}else{
		action_return(0,$u_langpackage->u_dressup_false,-1);
	}
?>
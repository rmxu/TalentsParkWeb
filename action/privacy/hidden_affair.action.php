<?php
	require("foundation/module_users.php");
	require("api/base_support.php");

	//引入语言包
	$pr_langpackage=new privacylp;

	//变量获得
	$user_id=get_sess_userid();
	$type=short_check(get_argg('type'));
	$hidden_value=short_check(get_argg('hidden_value'));
	$is_del=short_check(get_argg('is_del'));

	//数据表定义区
	$t_users=$tablePreStr."users";
	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('r',$dbServs);
	if($type==0){
		$col="hidden_pals_id";
	}else{
		$col="hidden_type_id";
	}
	$user_row = api_proxy("user_self_by_uid",$col,$user_id);
	
	if($is_del==0){
		if(preg_match("/,$hidden_value,/",$user_row[$col])){
			echo $pr_langpackage->pr_rep_screen;
			exit;
		}
		if(empty($user_row[$col])){
			$hidden_data=",".$hidden_value.",";
		}else{
			$hidden_data=$user_row[$col].$hidden_value.",";
		}
		
		if($type==0){
			set_session('hidden_pals',$hidden_data);
		}else{
			set_session('hidden_type',$hidden_data);
		}
		
	}else{
		$hidden_data=str_replace(",".$hidden_value.",",",",$user_row[$col]);
		if($type==0){
			set_session('hidden_pals',$hidden_data);
		}else{
			set_session('hidden_type',$hidden_data);
		}
		
	}

		dbtarget('w',$dbServs);
		$sql="update $t_users set $col='$hidden_data' where user_id=$user_id";
		if($dbo->exeUpdate($sql)){
			echo "success";
		}else{
			echo $pr_langpackage->pr_amend_los;
		}

?>
<?php
	//引入语言包
	$u_langpackage=new userslp;
	$forget_pws=get_argg('forget_pws');

	//数据表定义区
	$t_users = $tablePreStr."users";
	$dbo = new dbex;
	dbtarget('r',$dbServs);

	if($forget_pws==1){
		$new_pw = short_check(get_argp('new_pw'));
		$new_pw_repeat = short_check(get_argp('new_pw_repeat'));
		if($new_pw=='' || $new_pw_repeat==''){
			action_return(0,$u_langpackage->u_password_not_empty,-1);exit;
		}
		if($new_pw != $new_pw_repeat){
			action_return(0,$u_langpackage->u_pw2_err,-1);exit;
		}
			$user_id=get_session('forgeter');
			$code=get_session('forgetcode');
			$user_id=$user_id ? $user_id : 0;
			$sql="select forget_pass from $t_users where user_id=$user_id";
			$user_info=$dbo->getRow($sql);

			if($user_info['forget_pass']==$code){
				$new_pw=md5($new_pw);
				$sql_update="update $t_users set user_pws='$new_pw' where user_id=$user_id";
				$is_success=$dbo->exeUpdate($sql_update);
				$sql="update $t_users set forget_pass='' where user_id=$user_id";
				$dbo->exeUpdate($sql);
				set_session('forgeter',NULL);
				set_session('forgetcode',NULL);
				if($is_success!==''){
					action_return(0,$u_langpackage->u_change_successfully,$indexFile);exit;
				}else{
					action_return(0,$u_langpackage->u_change_successfully,-1);exit;
				}
			}else{
				action_return(0,$u_langpackage->u_checksum_not_match,'modules.php?app=user_forget');exit;
			}
	}else{
	//变量获得
	$user_id = get_sess_userid();
	$formerly_pw = short_check(get_argp('formerly_pw'));
	$new_pw = short_check(get_argp('new_pw'));
	$new_pw_repeat = short_check(get_argp('new_pw_repeat'));
	if($new_pw==''||$new_pw_repeat==''){
		action_return(0,$u_langpackage->u_password_not_empty,-1);exit;
	}
	if($new_pw != $new_pw_repeat){
		action_return(0,$u_langpackage->u_pw2_err,"modules.php?app=user_pw_change");exit;
	}
	if(!$user_id){
		action_return(0,$u_langpackage->u_after_operation_log,$indexFile);exit;
	}
	//读写分离定义函数
	$sql="select user_pws from $t_users where user_id=$user_id";
	$user_row=$dbo->getRow($sql);
	$formerly_pw=md5($formerly_pw);
	if($user_row['user_pws']==$formerly_pw){
		$new_pw = md5($new_pw);
		//读写分离定义函数
		dbtarget('w',$dbServs);
		$sql="update $t_users set user_pws ='$new_pw' where user_id = $user_id";
		$dbo->exeUpdate($sql);
		action_return(1,$u_langpackage->u_pw_chg_suc,"modules.php?app=user_pw_change");
	}else{
		action_return(0,$u_langpackage->u_pw_err,"modules.php?app=user_pw_change");
	}
}
?>
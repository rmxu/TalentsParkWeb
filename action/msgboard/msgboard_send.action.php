<?php
	//引入公共方法
	require("foundation/module_users.php");
	require("foundation/module_mypals.php");
	require("foundation/aanti_refresh.php");
	require("foundation/aintegral.php");
	require("api/base_support.php");

	//引入语言包
	$mb_langpackage=new msgboardlp;

	//变量取得
	$to_user_id = intval(get_argg('user_id'));
	$message = short_check(get_argp('msgboard'));
	$from_user_id=get_sess_userid();
	$from_user_name=get_sess_username();
	$from_user_ico=get_sess_userico();
	$restore=0;
	$result_type=get_argg('ajax')? 2:1;

	if(strlen($message) >=500){
		action_return($result_type,$mb_langpackage->mb_add_exc,-1);exit;
	}

	if(intval(get_argp('to_user_id'))){
		$to_user_id = intval(get_argp('to_user_id'));
		if($to_user_id!=$from_user_id){
			$restore=1;
		}
	}

	//数据表定义区
	$t_message = $tablePreStr."msgboard";
	$t_users = $tablePreStr."users";
	$t_holerpals = $tablePreStr."pals_mine";
	
	$user_info=api_proxy("user_self_by_uid","inputmess_limit",$to_user_id);
	
	if(!$user_info){
		action_return($result_type,$mb_langpackage->mb_add_err,"modules.php?app=msgboard_more&user_id=$to_user_id");exit;
	}	
	
	if($to_user_id!=$from_user_id && $user_info['inputmess_limit']){
		if($user_info['inputmess_limit']==2){
			action_return($result_type,$mb_langpackage->mb_add_pvw,"-1");exit;
		}else if($user_info['inputmess_limit']==1){
			if(!api_proxy("pals_self_isset",$to_user_id)){
				action_return($result_type,$mb_langpackage->mb_add_pvw,"-1");exit;
			}
		}
	}
	
	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);

	//留言
	$sql = "insert into $t_message(`from_user_id`,`from_user_name`,`from_user_ico`,`to_user_id`,`message`,`add_time`) values($from_user_id,'$from_user_name','$from_user_ico',$to_user_id,'$message',now())";
	if($dbo -> exeUpdate($sql)){
		if($to_user_id!=$from_user_id){
			increase_integral($dbo,$int_com_msg,$from_user_id);
			api_proxy("message_set",$to_user_id,$mb_langpackage->mb_remind,"modules.php?app=msgboard_more",0,7,"remind");
		}
		if($restore==1){
			$sql="insert into $t_message(`from_user_id`,`from_user_name`,`from_user_ico`,`to_user_id`,`message`,`add_time`) values($from_user_id,'$from_user_name','$from_user_ico',$from_user_id,'$message',now())";
			$dbo->exeUpdate($sql);
		}
		action_return($result_type,"","-1");
	}else{
		action_return($result_type,$mb_langpackage->mb_message_failed,"-1");
	}
?>

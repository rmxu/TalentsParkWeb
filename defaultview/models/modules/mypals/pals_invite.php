<?php
	//必须登录才能浏览该页面
	require("foundation/auser_mustlogin.php");
	require("api/base_support.php");
	require("foundation/fcontent_format.php");
	
	//引入公共方法
	require("foundation/fdnurl_aget.php");
	
	//引入语言包
	$mp_langpackage=new mypalslp;
	
	//变量区
	$user_id=get_sess_userid();
	$user_invite_url=get_uinvite_url($user_id);
	$user_home_url=get_uhome_url($user_id);
		
	if($inviteCode){
  	$user_info=api_proxy('user_self_by_uid','integral',$user_id);
  	$intg=$user_info['integral'];
		$t_invite_code=$tablePreStr."invite_code";
		$dbo=new dbex;
		dbtarget('r',$dbServs);
		if(get_argg('invite_code')==1){
			require("servtools/rand_code/produce_rand.php");
			$code_value=code_exists();
			if($code_value===false){
				echo $mp_langpackage->mp_invite_code_error;
				exit;
			}else{
				$mp_c_ic = $mp_langpackage->mp_congratulations_invite_code;
				echo $mp_c_ic.$code_value;
				exit;
			}
		}else if(get_argg('del_code')==1){
			$id_array=array();
			$id=intval(get_argg('id'));
			$id_array=get_argp('attach');
			$id_array=$id ? $id : $id_array;
			foreach($id_array as $val){
				$val=intval($val);
				$sql="delete from $t_invite_code where id=$val and sendor_id=$user_id";
				$dbo->exeUpdate($sql);
			}
		}
		$sql="select * from $t_invite_code where sendor_id=$user_id and is_admin=0 order by id desc";
		$invite_rs=$dbo->getRs($sql);		
	}
?>
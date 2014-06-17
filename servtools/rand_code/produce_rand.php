<?php
$session_power=true;
require(dirname(__FILE__)."/../../api/base_support.php");
function randkeys($length){
	$output='';
	$pool_base='0123456789abcdefghigklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
	for($a = 0;$a < $length; $a++){
		$output .= $pool_base[mt_rand(0,62)];
	}
	return $output;
}

function code_exists(){
	$is_admin='';
	$sendor_id='';
	$admin_id=get_session('admin_id');
  if($admin_id){
  	$is_admin=1;
  	$sendor_id=$admin_id;
  }else{
  	$user_id=get_sess_userid();
  	if(!$user_id){
  		return false;exit;
  	}
  	$is_admin=0;
  	$sendor_id=$user_id;
  }
  if($sendor_id!='' && $is_admin!==''){
  	global $inviteCodeValue;
		global $tablePreStr;
		global $inviteCodeLength;
		$t_invite_code=$tablePreStr."invite_code";
		$t_users=$tablePreStr."users";
	  if($is_admin==0){
	  	$user_info=api_proxy('user_self_by_uid','integral',$sendor_id);
	  	$intg=$user_info['integral'];
	  	if($inviteCodeValue > $intg){
	  		return false;
	  	}
	  }
		$dbo=new dbex;
	  dbplugin('r');
	  $invite_code=randkeys($inviteCodeLength);
		$sql="select id from $t_invite_code where code_txt='$invite_code'";
		$is_exists=$dbo->getRow($sql);
	  if($is_exists['id']){
			code_exists();
	  }else{
	  	$time=time();
			$sql="insert into $t_invite_code (sendor_id,code_txt,is_admin,add_time) values($sendor_id,'$invite_code',0,$time)";
			$success=$dbo->exeUpdate($sql);
			if($success){
				if($is_admin==0){
					$sql="update $t_users set integral=integral-$inviteCodeValue where user_id=$sendor_id";
					$dbo->exeUpdate($sql);					
				}
				return $invite_code;
			}else{
				return false;
			}
	  }
  }
}
?>
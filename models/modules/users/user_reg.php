<?php
//语言包引入
$pu_langpackage=new publiclp;
$u_langpackage=new userslp;
$l_langpackage=new loginlp;

//引入语言包
$reg_langpackage=new reglp;
$regInfo_root="langpackage/".$langPackagePara."/regInfo.php";
$regInfo=file_get_contents($regInfo_root);
$regInfo=$regInfo=='' ?  "<center><b>".$reg_langpackage->re_none_ser."</b></center>":$regInfo;
$invite_code='';
//是否可以注册
$is_show=1;
if($allowReg==false){
	$is_show=0;
	$error_str=$u_langpackage->u_not_open_register;
}else{
	if($inviteCode==1){
		$invite_code=get_argg('invite_code');
		if(strlen($invite_code)==$inviteCodeLength){
			$t_invite_code=$tablePreStr."invite_code";
			$dbo=new dbex;
			dbtarget('r',$dbServs);
			$now_time=time();
			$left_time=$inviteCodeLife*60*60;
			$sql="delete from $t_invite_code where $now_time-add_time > $left_time";
			$dbo->exeUpdate($sql);
			
			$sql="select id from $t_invite_code where code_txt='$invite_code'";
			$is_check=$dbo->getRow($sql);
			if(empty($is_check)){
				$error_str=$u_langpackage->u_invite_incorrect_or_failed;
				$is_show=0;
			}else{
				$is_show=1;
			}
		}else{
			$is_show=0;
			$error_str=$u_langpackage->u_need_invite_register;
		}
	}
}

?>
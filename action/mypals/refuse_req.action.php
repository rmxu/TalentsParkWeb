<?php
//引入语言包
$m_langpackage=new msglp;
$mp_langpackage=new mypalslp;
require("api/base_support.php");

//变量区
  $user_id=get_sess_userid();
  $user_name=get_sess_username();
  $req_id=intval(get_argg('req_id'));
  $userico=get_sess_userico();

//数据表定义区
	$t_mypals=$tablePreStr."pals_mine";
	$t_pals_request=$tablePreStr."pals_request";

//定义读操作
	dbtarget('r',$dbServs);
	$dbo=new dbex();

	$sql="select * from $t_pals_request where user_id=$user_id and id=$req_id";
	$req_row=$dbo->getRow($sql);
  $res_uid=$req_row["req_id"];
  $res_uname=$req_row["req_name"];
  $res_usex=$req_row["req_sex"];
  $res_rpid=$req_row["from_id"];

	//定义写操作
	dbtarget('w',$dbServs);

	$sql="delete from $t_mypals where user_id=$res_uid and pals_id=$user_id";
	$dbo->exeUpdate($sql);

	$sql="delete from $t_pals_request where id=$req_id and user_id=$user_id";
	$dbo->exeUpdate($sql);
	
	$title=$user_name.$m_langpackage->m_rej_app;
	$scrip_content=$user_name.$m_langpackage->m_reject_app;
	$is_success=api_proxy('scrip_send',$mp_langpackage->mp_system_sends,$title,$scrip_content,$res_uid,0);
	if($is_success){
		api_proxy("message_set",$res_uid,"{num}".$mp_langpackage->mp_a_notice,"modules.php?app=msg_notice",0,1,"remind");
		action_return(2,$mp_langpackage->mp_treatment_success,-1);
	}else{
		action_return(2,$mp_langpackage->mp_treatment_failure,-1);
	}	
	action_return(0,'',"");
?>


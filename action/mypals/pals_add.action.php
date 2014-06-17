<?php
//引入小纸条模块功能
	require("foundation/module_users.php");
	require("api/base_support.php");

//引入语言包
	$mp_langpackage=new mypalslp;
	$m_langpackage=new msglp;
//变量区
	$mreq_touser=intval(get_argg("other_id"));
 	$user_id=get_sess_userid();//发申请人id
	$user_name=get_sess_username();//发申请人姓名
	$user_sex=get_sess_usersex();//发申请人性别
	$userico=get_sess_userico();//发申请人的头像
	$user_mypals=get_sess_mypals();

//数据表定义区
	$t_users=$tablePreStr."users";
	$t_mypals=$tablePreStr."pals_mine";
	$t_pals_request=$tablePreStr."pals_request";

//添加好友是自己
  if($user_id==$mreq_touser){
    echo $mp_langpackage->mp_not_self;
    exit();
  }

//定义读操作
	dbtarget('r',$dbServs);
	$dbo=new dbex();

//是否已经提交了申请
	$sql="select user_id from $t_pals_request where user_id=$mreq_touser and req_id=$user_id";
	$is_req=$dbo->getRow($sql);
	if(!empty($is_req['user_id'])){
		echo $mp_langpackage->mp_wait_confirm;
		exit;
	}

//看看好友列里是否已经有该人
		$sql="select user_id from $t_mypals where user_id=$user_id and pals_id=$mreq_touser";
		$is_pals=$dbo->getRow($sql);
  if(!empty($is_pals['user_id'])){
      echo $mp_langpackage->mp_rep_req;
      exit();
  }

//取得该人的资料信息
  $user_row=api_proxy("user_self_by_uid","user_id,user_name,user_sex,user_ico,palsreq_limit",$mreq_touser);
  if($user_row){
		$touser_id=$user_row['user_id'];
		$touser_name=$user_row['user_name'];
		$touser_sex=$user_row['user_sex'];
		$touser_ico=$user_row['user_ico'];
		$touser_pals_limit=$user_row['palsreq_limit'];
  }else{
		echo $mp_langpackage->mp_info_wrong;
		exit();
  }

//此人拒绝加入
if($touser_pals_limit==2){echo $mp_langpackage->mp_ref_add;exit;}

//定义写操作
	dbtarget('w',$dbServs);
if($touser_pals_limit==0){

//判断自己是否存在对方朋友圈中
	$is_pals=api_proxy("pals_self_isset",$mreq_touser,$user_id);
	if(!$is_pals){
		$accepted=1;
	}
	else{
		$accepted=2;
		$sql="update $t_mypals set accepted=2 where pals_id=$user_id and user_id=$mreq_touser";
		$dbo->exeUpdate($sql);
	}
	
	$sql="insert into $t_mypals (user_id,pals_id,pals_name,pals_sex,add_time,pals_ico,accepted) value($user_id,$mreq_touser,'$touser_name','$touser_sex',NOW(),'$touser_ico',$accepted)";
	$dbo->exeUpdate($sql);

	if(empty($user_mypals)){
		set_sess_mypals($touser_id);
	}else{
		set_sess_mypals($user_mypals.",".$mreq_touser);
	}
		$title=$user_name.$m_langpackage->m_app_fri;
		$scrip_content=$user_name.$m_langpackage->m_app_friend;
		$is_success=api_proxy('scrip_send',$mp_langpackage->mp_system_sends,$title,$scrip_content,$mreq_touser,0);
		if($is_success){
			api_proxy("message_set",$mreq_touser,"{num}".$mp_langpackage->mp_a_notice,"modules.php?app=msg_notice",0,1,"remind");
			action_return(2,$mp_langpackage->mp_treatment_success,-1);
		}else{
			action_return(2,$mp_langpackage->mp_treatment_failure,-1);
		}
}

//验证加入
if($touser_pals_limit==1){
 $sql="insert into $t_mypals (user_id,pals_id,pals_name,pals_sex,add_time,pals_ico)"
                         ."value($user_id,$touser_id,'$touser_name','$touser_sex',NOW(),'$touser_ico')";
 $dbo->exeUpdate($sql);
 $from_pals_id=MYSQL_INSERT_ID();
 $sql="insert into $t_pals_request (user_id,req_id,req_name,req_sex,add_time,from_id,req_ico)"
                        ."value($touser_id,$user_id,'$user_name','$user_sex',NOW(),$from_pals_id,'$userico')";
 if($dbo->exeUpdate($sql)){
 	api_proxy("message_set",$mreq_touser,$mp_langpackage->mp_remind,"modules.php?app=mypals_request",0,3,"remind");//提醒机制
  echo $mp_langpackage->mp_suc_reg;
 }else{
  $sql="delete from $t_mypals where id=$from_pals_id";
  $dbo->exeUpdate($sql);
  echo $mp_langpackage->mp_req_false;
 }
}
?>
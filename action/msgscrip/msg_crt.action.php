<?php
	//引入模块公共方法文件
	require("foundation/aanti_refresh.php");
	require("api/base_support.php");

	//引入语言包
	$m_langpackage=new msglp;

  //变量获得
  $msgStr="";
  $msg_touser=intval(get_argp("msToId"));
  $msg_title=short_check(get_argp("msTitle"));
  $msg_txt=long_check(get_argp("msContent"));
  $touser_id="";//收件人id
  $touser="";//收件人name
  $tousex="";
  $user_id=get_sess_userid();//发件人id
  $user_name=get_sess_username();//发件人姓名
  $user_ico = get_sess_userico();

  if(strlen($msg_txt) >=500){
		action_return(0,$m_langpackage->m_add_exc,-1);exit;
  }
  //数据表定义
  $t_users = $tablePreStr."users";
  $t_msg_outbox = $tablePreStr."msg_outbox";
  $t_msg_inbox = $tablePreStr."msg_inbox";
	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('r',$dbServs);
  $toidUrlStr="";
  if(get_argp("2id")!=""){
     $msg_touser=intval(get_argp("2id"));
     $toidUrlStr="&2id=".$msg_touser;
     if(get_argp("nw")!=""){$toidUrlStr=$toidUrlStr."&nw=1";}//判断是否为新窗口
  }
  $users_row = api_proxy("user_self_by_uid","user_id,user_name,user_ico",$msg_touser);
  if($users_row){
		$touser_id=$users_row[0];
		$touser=$users_row[1];
		$touser_ico=$users_row[2];
		if($touser_id==$user_id){
			action_return(0,$m_langpackage->m_no_mys,"modules.php?app=msg_creator".$toidUrlStr);
		}
  }else{
		action_return(0,$m_langpackage->m_one_err,"modules.php?app=msg_creator".$toidUrlStr);
  }
	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);
  $sql="insert into $t_msg_outbox (mess_title,mess_content,to_user_id,to_user,to_user_ico,user_id,add_time,state)"
                        ."value('$msg_title','$msg_txt',$touser_id,'$touser','$touser_ico',$user_id,NOW(),'1')";
	if(!$dbo ->exeUpdate($sql)){
		action_return(0,$m_langpackage->m_data_err,"-1");exit;
	}
	$sql="insert into $t_msg_inbox (mess_title,mess_content,from_user_id,from_user,from_user_ico,user_id,add_time,mesinit_id)"
                   ."value('$msg_title','$msg_txt',$user_id,'$user_name','$user_ico',$touser_id,NOW(),LAST_INSERT_ID())";

	if($dbo ->exeUpdate($sql)){
			api_proxy("message_set",$touser_id,$m_langpackage->m_remind,"modules.php?app=msg_minbox",0,5,"remind");
			if(get_argp('nw')=="2"){
				action_return(1,'',"modules.php?app=hstart&user_id=".$touser_id);
			}else{
				action_return(1,'',"modules.php?app=msg_moutbox".$toidUrlStr);
			}
	}else{
	   $sql="update $t_msg_outbox set state='0' where mess_id=LAST_INSERT_ID()";
	   $dbo ->exeUpdate($sql);
	   action_return(0,$m_langpackage->m_send_err,"-1");
	 }

?>


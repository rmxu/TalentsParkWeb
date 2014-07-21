<?php
	//引入语言包
	$m_langpackage=new msglp;
	$mp_langpackage=new mypalslp;
	require('api/base_support.php');
	
  //变量获得
  $msg_id=intval(get_argg("id"));
  $user_id=get_sess_userid();
	$type=intval(get_argg("t"));
	$send_join_js='';
	
  //数据表定义
  $t_msg_inbox = $tablePreStr."msg_inbox";
  $t_msg_outbox = $tablePreStr."msg_outbox";
  
  if($type==1){
  	$dbo = new dbex;
		//读写分离定义函数
		dbtarget('r',$dbServs);
    $sql="select mess_title,mess_content,to_user_id,to_user,to_user_ico,add_time,state,mess_id "
         ."from $t_msg_outbox where mess_id='$msg_id'";
		$msg_row = $dbo ->getRow($sql);
		
    $relaUserStr=$m_langpackage->m_to_user;
    $reTurnTxt=$m_langpackage->m_out;
    $reTurnUrl="modules.php?app=msg_moutbox";
		$mess_id=$msg_row['mess_id'];
    if($msg_row['state']=="0"){
       $reButTxt=$m_langpackage->m_b_sed;
       $reButUrl="do.php?act=msg_send&to_id=$mess_id";
    }else{
       $reButTxt=$m_langpackage->m_b_con;
       $reButUrl=$reTurnUrl;
    }
  }else{
		$dbo = new dbex;
		//读写分离定义函数
		dbtarget('r',$dbServs);

		$sql="select mess_title,mess_content,from_user_id,from_user,from_user_ico,add_time,mesinit_id,mess_id,readed "
		   ."from $t_msg_inbox where mess_id='$msg_id'";
		$msg_row = $dbo ->getRow($sql);
		$relaUserStr=$m_langpackage->m_from_user;
		$reTurnTxt=$m_langpackage->m_in;
		$reButTxt=$m_langpackage->m_b_com;
		$reTurnUrl="modules.php?app=msg_minbox";
		$mess_id=$msg_row['mess_id'];
		$from_user_id=$msg_row['from_user_id'];
		$mess_title=$msg_row['mess_title'];
		$mesint_id=$msg_row['mesinit_id'];
		$reButUrl="modules.php?app=msg_creator&2id=$from_user_id&rt=".urlencode($mess_title);
		if($type=='2'){
			$send_join_js="mypals_add($from_user_id);";
			$reTurnUrl="modules.php?app=msg_notice";
			$reButTxt=$m_langpackage->m_b_bak;
			$reTurnTxt=$m_langpackage->m_to_notice;
			$reButUrl=$reTurnUrl;
 	 	}
		//读写分离定义函数
		dbtarget('w',$dbServs);
    if($msg_row['readed']=="0"){
      $sql="update $t_msg_inbox set readed='1' where mess_id=$mess_id";
      $dbo ->exeUpdate($sql);
      $sql="update $t_msg_outbox set state='2' where mess_id=$mesint_id";
      $dbo ->exeUpdate($sql);
    }
  }
?>
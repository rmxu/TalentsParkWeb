<?php
	//引入语言包
	$pr_langpackage=new privacylp;

	//变量获得
	$user_id=get_sess_userid();
	$h_access_para=short_check(get_argp('home_acess'));
	$access_q_1=short_check(get_argp('question_1'));
	$access_q_2=short_check(get_argp('question_2'));
	$access_q_3=short_check(get_argp('question_3'));
	$access_a_1=short_check(get_argp('answer_1'));
	$access_a_2=short_check(get_argp('answer_2'));
	$access_a_3=short_check(get_argp('answer_3'));

	//数据表定义区
	$t_users=$tablePreStr."users";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);

  $sql_part='';
  if($h_access_para=='3'){  	
  	  $sql_part=",access_questions='$access_q_1,$access_q_2,$access_q_3'";
  	  $sql_part=$sql_part.",access_answers='$access_a_1,$access_a_2,$access_a_3'";
  }

		$sql="update $t_users set access_limit=$h_access_para $sql_part where user_id=$user_id";
		$dbo ->exeUpdate($sql);
		
		action_return(1,$pr_langpackage->pr_save_sec,"modules.php?app=privacy");

?>
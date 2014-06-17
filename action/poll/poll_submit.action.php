<?php
//引入模块公共方法文件
require("api/base_support.php");
require("foundation/aanti_refresh.php");

//引入语言包
$pol_langpackage=new polllp;

//权限验证
if(!get_argp('action')){
		action_return(0,"$pol_langpackage->pol_error","-1");
	}

//变量声明区
	$user_id=get_sess_userid();
	$user_name=get_sess_username();
	$userico=get_sess_userico();
  $cho=get_argp('pol_cho');
  $pid=intval(get_argg('pid'));
  $anon=short_check(get_argp('anonymity'));
  $total_credit=short_check(get_argp('credit'));
  $per_int=short_check(get_argp('percredit'));
  $p_subject=short_check(get_argp('subject'));

  if(empty($anon)){
  	$anon=0;
  }

	if(empty($cho)){
		action_return(0,"$pol_langpackage->pol_error",-1);
	}

//数据表定义区
	$t_poll=$tablePreStr."poll";
	$t_polloption=$tablePreStr."polloption";
	$t_polluser=$tablePreStr."polluser";
	$t_users=$tablePreStr."users";

//定义写操作
  dbtarget('r',$dbServs);
  $dbo=new dbex();

	$sql="select username from $t_polluser where uid=$ses_uid and pid=$pid";
	$is_poll=$dbo->getRow($sql);
	if(!empty($is_poll)){
		action_return(0,"$pol_langpackage->pol_repeat",-1);
	}
  $option = '';
  foreach($cho as $v){
  	$option.=",".$v;
  }

  $sql="select `option` from $t_polloption where oid in(0.$option)";
  $option_rs=$dbo->getRs($sql);

  $cho_str = '';
  foreach($option_rs as $val){
  	$cho_str.="\"".$val['option']."\",";
  }

//定义写操作
  dbtarget('w',$dbServs);

   foreach($cho as $value){
  	if(short_check($value)!=''){
  		$cho_value=short_check($value);
  		$sql="update $t_polloption set votenum=votenum+1 where oid=$cho_value";
  		$dbo->exeUpdate($sql);
  	}
  }

//加分过程
	if(!empty($total_credit) && !empty($per_int)){
		if($total_credit <= $per_int){
			$per_int=$total_credit;
			$sql="update $t_poll set credit = 0 , percredit = 0 where p_id=$pid";
			$dbo->exeUpdate($sql);
		}else{
			$sql="update $t_poll set credit=credit-$per_int where p_id=$pid";
			$dbo->exeUpdate($sql);
		}
			$sql="update $t_users set integral=integral+$per_int where user_id=$user_id";
			$is_suc=$dbo->exeUpdate($sql);
	}

	//纪录新鲜事
	$title=$pol_langpackage->pol_participate_vote.'<a href="home.php?h='.$user_id.'&app=poll&p_id='.$pid.'" target="_blank">'.$p_subject.'</a>';
	$content='<a href="home.php?h='.$user_id.'&app=poll&p_id='.$pid.'" target="_blank">'.$p_subject.'</a>';
	$is_suc=api_proxy("message_set",0,$title,$content,0,4);

  $sql="update $t_poll set voternum=voternum+1,lastvote=NOW() where p_id=$pid";
  $dbo->exeUpdate($sql);

  $sql="insert into $t_polluser (`uid`,`username`,`pid`,`option`,`dateline`,`anony`) values ($user_id,'$user_name',$pid,'$cho_str',NOW(),$anon)";
	$dbo->exeUpdate($sql);

	//回应信息
	action_return(0,"",-1);
?>
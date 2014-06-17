<?php
//引入模块公共方法文件
require("foundation/aanti_refresh.php");
require("foundation/aintegral.php"); 
require("foundation/module_affair.php");

require("api/base_support.php");

//引入语言包
$pol_langpackage=new polllp;

//变量声明区
	$user_id=get_sess_userid();
	$set_option=short_check(get_argg('set_option'));
  $pid=intval(get_argg('pid'));
  $new_option=short_check(get_argp('add_new_option'));
  $add_award_sum=intval(get_argp('add_award_sum'));
  $add_award_sing=intval(get_argp('add_award_sing')));
  $add_new_option=short_check(get_argp('add_new_optin'));
  $expiration=short_check(get_argp('expiration'));
  $add_summary=short_check(get_argp('add_summary'));
  
//数据表定义区
	$t_users=$tablePreStr."users";
	$t_poll=$tablePreStr."poll";
	$t_polloption=$tablePreStr."polloption";
	$t_polluser=$tablePreStr."polluser";
	$t_poll_com=$tablePreStr."poll_comment";
	
  dbtarget('r',$dbServs);	
  $dbo=new dbex();
  
  $poll_info=api_proxy("poll_self_by_pollid","*",$pid);

	$u_int=api_proxy("user_self_by_uid","integral",$user_id);
	
	if($user_id!=$poll_info['user_id']){
		echo 'error';exit;
	}
	
	dbtarget('w',$dbServs);
	
	switch ($set_option){
		case "stop_award":
		$sql="update $t_poll set credit = 0 , percredit = 0 where p_id = $pid ";
		$dbo->exeUpdate($sql);
		$sql="update $t_users set integral = integral+$poll_info[credit] where user_id=$user_id";
		$dbo->exeUpdate($sql);
		echo "success";
		break;
		
		case "add_award":
		if($add_award_sing!=''&&$add_award_sing<=0){
			echo $pol_langpackage->pol_empty_s;
		}
		elseif(empty($add_award_sum)){
			echo $pol_langpackage->pol_award_num;
		}
		elseif($add_award_sum > $u_int['integral']){
			echo $pol_langpackage->pol_total_limit;
		}
		elseif($poll_info['percredit']+$add_award_sing > 10){
			echo $pol_langpackage->pol_sing_limit;
		}else{
			$sql="update $t_poll set credit=credit+$add_award_sum , percredit=percredit+$add_award_sing where p_id=$pid";
			$dbo->exeUpdate($sql);
			$sql="update $t_users set integral=integral-$add_award_sum where user_id=$user_id";
			$dbo->exeUpdate($sql);
			echo "success";
		}
		break;
		
		case "add_option":
		if(empty($new_option)){
			echo $pol_langpackage->pol_empty_option;
		}else{
			$sql="insert into $t_polloption (`pid` , `votenum` , `option`) values ($pid , 0 , '$new_option')";
			$dbo->exeUpdate($sql);
			echo "success";
		}
		break;

		case "del_poll":
		$sql="delete from $t_poll where p_id=$pid";
		$dbo->exeUpdate($sql);
		$sql="delete from $t_polloption where pid=$pid";
		$dbo->exeUpdate($sql);
		$sql="delete from $t_polluser where pid=$pid";
		$dbo->exeUpdate($sql);
		$sql="delete from $t_poll_com where p_id=$pid";
		$dbo->exeUpdate($sql);
		$sql="update $t_users set integral = integral+$poll_info[credit] where user_id=$user_id";
		$dbo->exeUpdate($sql);
		increase_integral($dbo,$int_del_poll,$poll_info['user_id']);
		del_affair($dbo,4,$pid);
		echo "success";
		break;
		
		case "change_date":
		$sql="update $t_poll set expiration = '$expiration' where p_id=$pid";
		$dbo->exeUpdate($sql);
		echo "success";
		break;
	
		case "add_summary":
		$sql="update $t_poll set summary = '$add_summary' where p_id=$pid";
		$dbo->exeUpdate($sql);
		echo "success";
		break;										
		default:
		echo "error";
	}
?>
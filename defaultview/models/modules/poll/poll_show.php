<?php
  //引入公共模块
  require("foundation/module_users.php");
	require("foundation/module_mypals.php");
	require("foundation/module_poll.php");
	require("foundation/fcontent_format.php");

  //语言包引入
  $pol_langpackage=new polllp;
  $g_langpackage=new grouplp;
	$mn_langpackage=new menulp;

  //变量区
	$ses_uid=get_sess_userid();
	$u_sex=get_sess_usersex();
	$pid=intval(get_argg('p_id'));
	$url_uid=intval(get_argg('user_id'));
	$is_admin=get_sess_admin();
	$mod=get_argg('m');

	$new_active="";
	$hot_active="";
	$reward_active="";
	$mine_active="";
	switch($mod){
		case "new":
		$new_active="active";
		break;
		case "hot":
		$hot_active="active";
		break;
		case "reward":
		$reward_active="active";
		break;
		default:
		$mine_active="active";
	}

  //引入模块公共权限过程文件
	$is_self_mode='partLimit';
	$is_login_mode='';
	require("foundation/auser_validate.php");

	 dbtarget('r',$dbServs);
   $dbo=new dbex;

//数据表定义区
	$t_mypals=$tablePreStr."pals_mine";
	$t_poll=$tablePreStr."poll";
	$t_polloption=$tablePreStr."polloption";
	$t_polluser=$tablePreStr."polluser";

	$p_result_rs=array();
	$p_com_rs=array();
	$p_put_info=array();
	$poll_row=array();
	$sum_poll_num=array();
	$is_poll=array();
	$pals_id='';
	$refuse_str='';
	$error_str='';
	if($pid){
		$poll_row=get_poll_row($dbo,$t_poll,$pid);

		$sql="select * from $t_polloption where pid=$pid";
	 	$p_result_rs=$dbo->getRs($sql);

	 	$sql="select sum(votenum) as sum_votenum from $t_polloption where pid=$pid";
	 	$sum_poll_num=$dbo->getRow($sql);

	 	$pals_id=getMypals($dbo,$poll_row['user_id'],$t_mypals);

		$sql="select * from $t_polluser where pid=$pid order by dateline desc limit 10";
		$p_put_info=$dbo->getRs($sql);
	}

//投票信息
	$show_poll_info="";
	if(empty($p_put_info)){
		$show_poll_info="content_none";
	}
	if($ses_uid!=''){
		$sql="select username from $t_polluser where uid=$ses_uid and pid=$pid";
		$is_poll=$dbo->getRs($sql);
	}
	//防止重复投票
	if(empty($is_poll)){
		$check_form="check_form();";
		$show_check="";
		$action_URL="do.php?act=poll_submit&pid=".$poll_row['p_id'];
	}else{
		$show_check="content_none";
		$action_URL='#';
		$check_form="refuse_poll();";
		$refuse_str=$pol_langpackage->pol_repeat;
	}

//性别限定
if($ses_uid!=''){
	if($poll_row['sex']!=2&&$poll_row['sex']!=$u_sex){
		$show_check="content_none";
		$action_URL='#';
		$check_form="refuse_poll();";
		$refuse_str=str_replace("{sex}",get_user_sex($poll_row['sex']),$pol_langpackage->pol_sex_limit);
	}
}

//锁定判断
	$show_error="content_none";
	if($poll_row['is_pass']==0 && $is_admin==''){
    $error_str=$pol_langpackage->pol_lock;
    $show_error="";
	}

//总结报告
	$show_sumary="";
	if(empty($poll_row['summary'])){
		$show_sumary="content_none";
	}

//操作显示
	$action_ctrl="";
	$sendor_info="content_none";
	$show_share="content_none";
	$show_i_com="";
	if($ses_uid!=$poll_row['user_id']){
		$sendor_info="";
		$action_ctrl="content_none";
		$show_share="";
	//评论好友限定
		if($poll_row['noreply']==1&&!strstr($pals_id,$ses_uid)||$ses_uid==NULL){
			$show_i_com="content_none";
		}
	}

//显示奖励积分
	$show_award_int="";
	if(empty($poll_row['percredit'])){
		$show_award_int="content_none";
	}

//控制数据显示
	$show_data="";
	if(empty($poll_row)){
		$error_str=$pol_langpackage->pol_error;
		$show_error="";
		$show_data="content_none";
		$show_com="content_none";
		$show_sumary="content_none";
		$isNull=1;
		$sendor_info="content_none";
		$action_ctrl="content_none";
		$show_i_com="content_none";
		$show_poll_info="content_none";
		$show_award_int="content_none";
	}

//投票结束
	if(strtotime($poll_row['expiration']) < strtotime(date("Y-m-d",time()))){
		$show_check="content_none";
		$action_URL='#';
		$check_form="refuse_poll();";
		$refuse_str=$pol_langpackage->pol_date_over;
		$date_color='color:red';
	}
//未登录
	if(!$ses_uid){
		$show_check="content_none";
		$action_URL='#';
		$check_form="refuse_poll();";
		$refuse_str=$pol_langpackage->pol_not_login;
	}
?>
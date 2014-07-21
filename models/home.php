<?php
header("content-type:text/html;charset=utf-8");
require("foundation/asession.php");
require("configuration.php");
require("includes.php");

//引入公共方法
require("foundation/fcontent_format.php");
require("foundation/module_mood.php");
require("foundation/module_users.php");
require("foundation/fplugin.php");
require("foundation/fgrade.php");
require("api/base_support.php");

	//语言包引入
	$u_langpackage=new userslp;
	$pu_langpackage=new publiclp;
	$s_langpackage=new sharelp;
	$mn_langpackage=new menulp;
	$hi_langpackage=new hilp;
	$mo_langpackage=new moodlp;
	$pr_langpackage=new privacylp;
	$ah_langpackage=new arrayhomelp;

	//变量获得
	$holder_id=intval(get_argg('h'));//主人id
	$user_id =get_sess_userid();
	$dress_name=short_check(get_argg('dress_name'));//装扮名称

	//表声明区
	$t_mood=$tablePreStr."mood";
	$t_users=$tablePreStr."users";
	$t_online=$tablePreStr."online";

	//获取并重写url参数
	$urlParaStr=getReUrl();

	//取得主人信息
	$user_info=$holder_id ? api_proxy("user_self_by_uid","*",$holder_id):array();
	$holder_name=empty($user_info) ? '':$user_info['user_name'];
	$is_self=($holder_id==$user_id) ? 'Y':'N';

	//隐私显示控制
	$show_error=false;
	$show_ques=false;
	$is_visible=0;
	$show_info="";
	$dbo = new dbex;
	dbtarget('r',$dbServs);
	if($user_info){
	  //最后更新心情
		$last_mood_rs=get_last_mood($dbo,$t_mood,$holder_id);
		$last_mood_txt='';
		if($last_mood_rs['mood']){
			$last_mood_txt=get_face($last_mood_rs['mood']);
			$last_mood_time=format_datetime_short($last_mood_rs['add_time']);
		}else{
			$last_mood_txt=$mo_langpackage->mo_null_txt;
			$last_mood_time='';
		}
		//主人姓名
		set_session($holder_id.'_holder_name',$user_info['user_name']);
		$user_online=array();
		
		//登录状态
		$ol_state_ico="skin/$skinUrl/images/online.gif";
		$ol_state_label=$ah_langpackage->ah_current_online;
		$timer_txt='';
		$user_online=get_user_online_state($dbo,$t_online,$holder_id);
		if($is_self=='N'&&(empty($user_online)||$user_online['hidden']==1)){
		  $ol_state_ico="skin/$skinUrl/images/offline.gif";
		  $ol_state_label=$ah_langpackage->ah_offline;
		  $timer_txt='('.format_datetime_short($user_info['lastlogin_datetime']).')';
		}else if($is_self=='Y' && $user_online['hidden']==1){
			$ol_state_ico="skin/$skinUrl/images/hiddenline.gif";
			$ol_state_label=$ah_langpackage->ah_stealth;
		}

		$is_admin=get_sess_admin();
		if($is_admin==''&&$is_self=='N'){
			if($user_info['is_pass']==0){
				$show_error=true;$show_info=$pu_langpackage->pu_lock;
			}elseif($user_info['access_limit']==1 && $user_id==''){
				$show_error=true;$show_info=$pr_langpackage->pr_acc_false;
			}elseif($user_info['access_limit']==2 && !api_proxy("pals_self_isset",$holder_id)){
				$show_error=true;$show_info=$pr_langpackage->pr_acc_false;
			}elseif($user_info['access_limit']==3 && get_session($holder_id.'homeAccessPass')!='1'){
				$show_ques=true;
			}else{
				$is_visible=1;
			}
		}else{
			$is_visible=1;
		}
	}else{
		$show_error=true;$show_info=$pu_langpackage->pu_no_user;
	}

	if($user_id){
		$inc_header="uiparts/homeheader.php";
	}else{
		$inc_header="uiparts/guestheader.php";
	}
?>
<?php
header("content-type:text/html;charset=utf-8");
require("foundation/asession.php");
require("configuration.php");
require("includes.php");
//当前可访问的action动作,先列出公共部分,然后按各个模块列出
$actArray=array(
    "login"=> array('action/login_act.php','main.php'),
    "logout"=> array('action/logout_act.php',"$indexFile"),
    "reg"=> array('action/reg_act.php','main.php'),

    "hello_add"=>array('action/helloword/helloword_add.action.php','modules.php?app=hello'),
    "hello_del"=>array('action/helloword/helloword_del.action.php','modules.php?app=hello'),

    "group_creat"=> array('action/group/group_creat.action.php','modules.php?app=group'),
    "group_join"=> array('action/group/group_join.action.php'),
    "group_del_sub"=> array('action/group/group_del_subject.action.php'),
    "group_exit"=> array('action/group/group_exit.action.php','modules.php?app=group'),
    "group_drop"=> array('action/group/group_drop.action.php','modules.php?app=group'),
    "group_appoint"=> array('action/group/group_appoint.action.php'),
    "group_revoke"=> array('action/group/group_revoke.action.php'),
    "group_del_member"=> array('action/group/group_del_memeber.action.php'),
    "group_del_req"=> array('action/group/group_del_request_member.action.php'),
    "group_info_change"=> array('action/group/group_info_change.action.php'),
    "group_send_sub"=> array('action/group/group_send_subject.action.php'),
    "group_approve"=> array('action/group/group_approve.action.php'),
    "group_change_group_info"=> array('action/group/group_info_change.action.php'),

    "msg_crt"=> array('action/msgscrip/msg_crt.action.php'),
    "msg_del"=> array('action/msgscrip/msg_del.action.php'),
    "msg_send"=> array('action/msgscrip/msg_send.action.php'),
    "msgboard_send"=> array('action/msgboard/msgboard_send.action.php'),
    "msgboard_del"=> array('action/msgboard/msgboard_del.action.php'),

    "user_info"=> array('action/users/user_info.action.php'),
    "user_pw_change"=> array('action/users/user_pw_change.action.php'),
    "user_ico_upload"=> array('action/users/user_ico_upload.action.php'),
    "user_ico_save"=> array('action/users/user_ico_cut_save.action.php'),
    "user_ol_reset"=> array('action/users/user_online_reset.action.php'),
    "user_add_hi"=> array('action/users/user_add_hi.action.php'),
    "user_del_hi"=> array('action/users/user_del_hi.action.php'),
    "user_forget"=> array('action/users/user_forget.action.php'),
    "user_dress_change"=> array('action/users/user_dressup.action.php'),

    "mood_add"=> array('action/mood/mood_add.action.php'),
    "mood_del"=> array('action/mood/mood_del.action.php'),

    "add_mypals"=> array('action/mypals/pals_add.action.php'),
    "pals_sort_add"=> array('action/mypals/pals_sort_add.action.php','modules.php?app=mypals_sort'),
    "pals_change"=> array('action/mypals/pals_change.action.php'),
    "pals_sort_change" => array('action/mypals/pals_sort_change.action.php'),
    "pals_sort_del" => array('action/mypals/pals_sort_del.action.php','modules.php?app=mypals_sort'),
    "del_mypals" => array('action/mypals/pals_del.action.php','modules.php?app=mypals'),
    "refuse_req" => array('action/mypals/refuse_req.action.php','modules.php?app=mypals_request'),
    "del_req" => array('action/mypals/del_req.action.php','modules.php?app=mypals_request'),
    "confirm_both" => array('action/mypals/confirm_both.action.php','modules.php?app=mypals_request'),
    "confirm_other" => array('action/mypals/confirm_other.action.php','modules.php?app=mypals_request'),

    "blog_add" => array('action/blog/blog_add.action.php','modules.php?app=blog_list'),
    "blog_del" => array('action/blog/blog_del.action.php','modules.php?app=blog_list'),
    "blog_edit" => array('action/blog/blog_edit.action.php'),
    "blog_sort_add" => array('action/blog/blog_sort_add.action.php'),
    "blog_sort_del" => array('action/blog/blog_sort_del.action.php','modules.php?app=blog_manager_sort'),
    "blog_sort_change" => array('action/blog/blog_sort_change.action.php'),

    "upload_act" => array('action/pubtools/upload.action.php'),

    "pr_access" => array('action/privacy/home_access_set.action.php'),
    "pr_access_login" => array('action/privacy/home_acess_login.action.php'),
    "pr_inputmess" => array('action/privacy/home_inputmess_set.action.php'),
    "pr_reqcheck" => array('action/privacy/home_reqcheck_set.action.php'),
    "pr_affair" => array('action/privacy/hidden_affair.action.php'),

    "poll_add" => array('action/poll/poll_add.action.php','modules.php?app=poll_mine'),
    "poll_submit" => array('action/poll/poll_submit.action.php'),
    "poll_set_config" => array('action/poll/poll_set_config.action.php'),

    "share_action" => array('action/share/share.action.php'),
    "share_del" => array('action/share/share_del.action.php'),
    "share_get_info" => array('action/share/share_outer.action.php'),

    "report_add" => array('action/report/report_add.action.php'),

    "restore_add" => array('action/restore/restore_add.action.php'),
    "restore_del"=> array('action/restore/restore_del.action.php'),

    "message_del" => array('action/message/message_del.action.php'),
    "add_app" => array('action/userapp/add_app.action.php'),
    "del_app" => array('action/userapp/del_app.action.php'),
);
$actId=getActId();
$free_act_array=array("login","reg","logout","pr_access_login","photo_upl_flash","user_forget","user_pw_change");
//除必须登录才能访问文件
if(!in_array($actId,$free_act_array)){
	limit_time($limit_action_time);
	require("foundation/auser_mustlogin.php");
}

//action动作成功控制函数
function action_return($state=1,$retrun_mess="",$activeUrl=""){
		if($state==2){echo $retrun_mess;exit;}
	  Global $acttarget;
	  echo "<script language='javascript'>";
	  if(trim($retrun_mess)!=''){
	  	 echo "alert('".$retrun_mess."');";
	  }
	  $setUrl='';
	  if($activeUrl!=''){
	    $setUrl=$activeUrl;
	  }else{
	  	$setUrl=$acttarget[1];
	  }
		if($setUrl=='-1'){
			echo "history.go(-1);";
		}else if($setUrl=='0'){
			echo "window.close();";
		}else{
			echo "location.href='".$setUrl."';";
		}
			echo "</script>";exit();
}
//echo 'here';
if(array_key_exists($actId,$actArray)){
//    echo 'exist';
	$acttarget=$actArray[$actId];
 //   echo $acttarget[0];
	require($acttarget[0]);
}else{
	  echo 'error';
}
?>
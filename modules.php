<?php
header("content-type:text/html;charset=utf-8");
require("foundation/asession.php");
require("configuration.php");
require("includes.php");

//当前可访问的应用工具
$appArray=array(
		   "start" => 'modules/start.php',
		   "hstart" => 'modules/homestart.php',
		   "blog_list" => 'modules/blog/blog_list.php',
		   "blog" => 'modules/blog/blog_show.php',
		   "blog_edit" => 'modules/blog/blog_edit.php',
		   "blog_manager_sort" => 'modules/blog/blog_manager_sort.php',
		   "blog_sort" => 'modules/blog/blog_sort.php',
		   "blog_friend" => 'modules/blog/blog_friend.php',
		   "group" => 'modules/group/group_mine.php',
		   "group_creat" => 'modules/group/group_creat.php',
		   "group_hot" => 'modules/group/group_hot.php',
		   "group_manager" => 'modules/group/group_manager.php',
		   "group_member_manager" => 'modules/group/group_member_manager.php',
		   "group_info_manager" => 'modules/group/group_info_manager.php',
		   "group_select" => 'modules/group/group_select.php',
		   "group_space" => 'modules/group/group_space.php',
		   "group_subject" => 'modules/group/group_subject.php',
		   "search_group" => 'modules/group/search_group.php',
		   "search_subject" => 'modules/group/search_subject.php',
		   "group_sub_show" => 'modules/group/group_sub_show.php',
		   "mypals" => 'modules/mypals/pals_list.php',
		   "mypals_search" => 'modules/mypals/search_pals.php',
		   "mypals_search_list" => 'modules/mypals/search_pals_list.php',
		   "mypals_request" => 'modules/mypals/pals_request.php',
		   "mypals_invite" => 'modules/mypals/pals_invite.php',
		   "mypals_sort" => 'modules/mypals/pals_manager_sort.php',
		   "user_forget" => 'modules/users/user_forget.php',
		   "user_reg" => 'modules/users/user_reg.php',
		   "user_info" => 'modules/users/user_info.php',
		   "user_pw_change" => 'modules/users/user_pw_change.php',
		   "user_pw_reset" => 'modules/users/user_pw_reset.php',
		   "user_ico" => 'modules/users/user_ico.php',
		   "user_ico_select" => 'modules/album/photo_ico_select.php',
		   "user_ico_cut" => 'modules/users/user_ico_cut.php',
		   "user_archives" => 'modules/users/user_archives.php',
		   "user_hi"=>'modules/users/user_hi.php',
		   "user_dressup"=>'modules/users/user_dressup.php',
		   "user_affair" => 'modules/users/affair_set.php',
		   "all_app"=>'modules/userapp/all_app.php',
		   "add_app"=>'modules/userapp/add_app.php',
		   "mag_app"=>'modules/userapp/mag_app.php',
		   "msg_notice" => 'modules/msgscrip/notice.php',
		   "msg_creator" => 'modules/msgscrip/creator.php',
		   "msg_minbox" => 'modules/msgscrip/minbox.php',
		   "msg_moutbox" => 'modules/msgscrip/moutbox.php',
		   "msg_rpshow" => 'modules/msgscrip/rpshow.php',
		   "msgboard" => 'modules/msgboard/msgboard.php',
		   "msgboard_more" => 'modules/msgboard/msgboard_more.php',
		   "guest" => 'modules/guest/guest.php',
		   "guest_more" => 'modules/guest/guest_more.php',
		   "friend" => 'modules/friend/friend.php',
		   "friend_all" => 'modules/friend/friend_all.php',
		   "mood_friend" => 'modules/mood/mood_friend.php',
		   "mood_more" => 'modules/mood/mood_more.php',
		   "upload_form" => 'modules/pubtools/upload.form.php',
		   "recent_affair" => 'modules/recentaffair/rec_affair.php',
		   "remind" => 'modules/uiparts/remind.php',
		   "remind_message" => 'modules/uiparts/remind_message.php',
		   "refresh" => 'modules/uiparts/refresh.php',
		   "restore" => 'modules/restore/get_restore.php',
		   "share_list" => 'modules/share/share_list.php',
		   "share_friend" => 'modules/share/share_friend.php',
		   "share_show" => 'modules/share/share_show.php',
		   "privacy" => 'modules/privacy/home_access_set.php',
		   "pr_inputmess" => 'modules/privacy/home_inputmess_set.php',
		   "pr_reqcheck" => 'modules/privacy/request_check_set.php',
		   'hello'=>'modules/helloword/helloword_list.php',
       );

$appId=getAppId();
if(array_key_exists($appId,$appArray)){
	$apptarget=$appArray[$appId];
	require($apptarget);
}else{
	echo '<script>top.location.href="'.$siteDomain.$indexFile.'";</script>';
}
?>

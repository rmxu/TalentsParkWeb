<?php
	//引入语言包
	$mn_langpackage=new menulp;
	$u_langpackage=new userslp;
	$ah_langpackage=new arrayhomelp;
	
	$send_msgscrip='modules.php?app=msg_creator&2id='.$holder_id.'&nw=2';
	$add_friend="javascript:mypalsAddInit($holder_id)";
	$leave_word='modules.php?app=msgboard_more&user_id='.$holder_id;
	$send_hi="hi_action($holder_id)";
	$send_report="report_action(10,$holder_id,$holder_id);";
	if(!isset($user_id)){
	  	$send_msgscrip="javascript:parent.goLogin();";
	  	$add_friend='javascript:parent.goLogin()';
	  	$leave_word="javascript:parent.goLogin();";
	  	$send_hi='javascript:parent.goLogin()';
	  	$send_report='javascript:parent.goLogin()';
	  	set_session('pre_login_url',$_SERVER["REQUEST_URI"]);
	}
?>
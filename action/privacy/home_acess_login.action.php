<?php
//语言包引入
 $pr_langpackage=new privacylp;

	//变量获得
	$user_id=get_sess_userid();
	$holder_id=intval(get_argg('holder_id'));
	$answer_key=short_check(get_argp('questions'));
	$input_answer=short_check(get_argp('answer'));

	$ha_arr=get_session($holder_id.'homeAccessAnswers');
	
	if($ha_arr[$answer_key]==$input_answer){
		set_session($holder_id.'homeAccessAnswers','');
		set_session($holder_id.'homeAccessPass','1');
		action_return(1,'',"home.php?h=$holder_id");
	}else{
		set_session($holder_id.'homeAccessPass','0');
		action_return(0,$pr_langpackage->pr_qanswer_err,-1);
	}
?>
<?php
	//语言包引入
	$u_langpackage=new userslp;
	//变量获得
	$user_id = get_sess_userid();
	$formerly_pw = short_check(get_argp('formerly_pw'));
	$new_pw = short_check(get_argp('new_pw'));
	$new_pw_repeat = short_check(get_argp('new_pw_repeat'));
	$model = short_check(get_argg('model'));
?>
<?php
	//引入模块公共方法文件
	$RefreshType='ajax';
	require("foundation/aanti_refresh.php");
	require("api/base_support.php");

	//引入语言包
	$mo_langpackage=new moodlp;

	//变量取得
	$user_id=get_sess_userid();
	$user_name=get_sess_username();//用户名
	$uico_url=get_sess_userico();//用户头像
	$mood = long_check(get_argp('mood'));

	//防止重复提交
	antiRePost($mood);
	if(strlen($mood) >=500){
		action_return(0,$mo_langpackage->mo_add_exc,-1);exit;
	}else{
		//数据表定义区
		$t_mood = $tablePreStr."mood";
		$dbo = new dbex;
		//读写分离定义函数
		dbtarget('w',$dbServs);
		//留言
		$sql = "insert into $t_mood(`user_id`,`mood`,`add_time`,`user_ico`,`user_name`) values($user_id,'$mood',now(),'$uico_url','$user_name')";
		if($dbo->exeUpdate($sql)){
			$last_id=mysql_insert_id();
			$title=$mo_langpackage->mo_mood_update;
			$content=$mood;
			$is_suc=api_proxy("message_set",$last_id,$title,$content,1,6);
		}
		//回应信息
		if(get_argg('ajax')=='1'){
			  echo get_face($mood).' [<a href="modules.php?app=mood_more">'.$mo_langpackage->mo_more_label.'</a>]';
		}else{
		    action_return(1,"","-1");
		}
	}
?>
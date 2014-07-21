<?php
function update_online_time($dbo,$table){
	$user_id=get_sess_userid();
	$now_time=time();
	$kick_time=20;//设置超时时间
	if($user_id){
		$sql="update $table set active_time='$now_time' where user_id=$user_id";
		if(!$dbo->exeUpdate($sql)){
			global $tablePreStr;
			$t_online=$tablePreStr."online";
			$user_id=get_sess_userid();
			$user_name=get_sess_username();
			$user_ico=get_sess_userico();
			$user_sex=get_sess_usersex();
			$sql="insert into $t_online (`user_id`,`user_name`,`user_sex`,`user_ico`,`active_time`,`hidden`) values ($user_id,'$user_name','$user_sex','$user_ico','$now_time',0)";
			$dbo->exeUpdate($sql);
		}
	}
	$sql="delete from $table where $now_time-active_time>$kick_time*60";
	$dbo->exeUpdate($sql);
}

function rewrite_delay(){
	$now_time=time();
	$content=file_get_contents("foundation/fdelay.php");
	$content=preg_replace('/(\$LAST_DELAY_TIME)=(\d*)(;)/',"$1={$now_time}$3",$content);
	$ref=fopen("foundation/fdelay.php",'w+');
	fwrite($ref,$content);
	fclose($ref);
}

$rem_short_act=array(
	3=>"modules.php?app=mypals_request",
	10=>"modules.php?app=remind_group",
	5=>"modules.php?app=msg_minbox",
	7=>"modules.php?app=msgboard_more",
	4=>"modules.php?app=user_hi",
);
?>
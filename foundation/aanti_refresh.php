<?php 
//防刷新控制代码
$ref_langpackage=new gobacklp;

if(get_session("post_sep_time")){
	if(time() - get_session("post_sep_time") < $allowRefreshTime){
		if(isset($RefreshType)=='ajax'){
			action_return(2,'error:'.$ref_langpackage->ref_delay_time,"-1");
		}else{
			action_return(1,$ref_langpackage->ref_delay_time,"-1");
		}
		sleep($delayTime);
	}else{
		set_session("post_sep_time",time());
	}
}else{
	set_session("post_sep_time",time());
}

$anit_refresh=$ref_langpackage->ref_anit_refresh;
function antiRePost($sendStr){
	global $RefreshType;
	global $anit_refresh;
	if($sendStr==get_session('PostSendStr')){
		if(isset($RefreshType)=='ajax'){
			action_return(2,'error:'.$anit_refresh,"-1");
		}else{
			action_return(1,$anit_refresh,"-1");
		}
	}
	set_session('PostSendStr',$sendStr);
}
?>
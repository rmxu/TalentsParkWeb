<?php
//限制ip访问站点
$er_langpackage=new errorlp;
$er_lp_action=$er_langpackage->er_refuse_action;
$er_lp_guest=$er_langpackage->er_refuse_guest;

if(!empty($limit_ip_list)){
	if(is_string($limit_ip_list)){
		$limit_ip_list=array($limit_ip_list);
	}
	foreach($limit_ip_list as $ip_rs){
		if(preg_match("/$ip_rs/",$_SERVER['REMOTE_ADDR'])){
			echo $er_langpackage->er_refuse_ip;exit;
		}
	}
}

//限制时间段访问站点
limit_time($limit_guest_time,"guest");

function limit_time($limit_time,$type=''){
	if(!empty($limit_time)){
		global $er_lp_action;
		global $er_lp_guest;
		$str=$er_lp_action;
		if($type=='guest'){
			$str=$er_lp_guest;
		}
		if(is_string($limit_time)){
			$limit_time=array($limit_time);
		}
		foreach($limit_time as $time_rs){
			$time_array=explode("-",$time_rs);
			$limit_min_time=strtotime($time_array[0]);
			$limit_max_time=strtotime($time_array[1]);
			if(strtotime($time_array[1]) < strtotime($time_array[0])){
				$limit_max_time=strtotime($time_array[1])+60*60*24;
			}
			if(time()>=$limit_min_time && time()<=$limit_max_time){
				echo $str;exit;
			}
		}
	}
}

?>
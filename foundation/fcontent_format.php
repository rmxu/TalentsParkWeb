<?php
//获得文本部分内容
function get_lentxt($txtstr,$maxlen){
	 if(strlen($txtstr)>$maxlen){
	 	  return substr(filt_word($txtstr),0,$maxlen).'...';
	 }else{
	 	  return filt_word($txtstr);
	 }
}

function is_utf8($word){
	if(preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$word) == true){ 
		return true; 
	}else{ 
		return false; 
	}
}

function get_short_txt($str){
	$str=strip_tags($str);
	return get_lentxt(trim($str),180);
}

function get_blog_lentxt($ublog_txt){
	$pic_blog=array();
	$pic_share=array();
	$pic='';
	preg_match("/<img src=(.*) onload=fixImage\(this,400,0\) classId=\\\\\"\d*\\\\\" \/>/",$ublog_txt,$pic_blog);
	if(!empty($pic_blog)){
		$pic=$pic_blog[0];
	}
	if($pic==''){
		preg_match("/<img src=(.*) onload=fixImage\(this,400,0\) classId=\"\d*\" \/>/",$ublog_txt,$pic_share);
		if(!empty($pic_share)){
			$pic=$pic_share[0];
		}
	}
	
	if($pic!=''){
		$pic="<div style=\"float:left\">".$pic."</div>";
	}
	$ublog_txt=strip_tags($ublog_txt);
	$ublog_txt=$pic.get_lentxt($ublog_txt,180);
	return $ublog_txt;
}

//格式化日期文本函数
function format_datetime_txt($event_time){
  $nyear=date("Y", time());
  $nmonth=date("m", time());
  $nday=date("d", time());
  $nhour=date("H", time());
  $nminute=date("i", time());
  
  $etimeArr=explode('-',$event_time);
  $eyear=$etimeArr[0];
  $emonth=$etimeArr[1];
  $etimeArr2=explode(' ',$etimeArr[2]);
  $eday=$etimeArr2[0];
  $etimeArr3=explode(':',$etimeArr2[1]);
  $ehour=$etimeArr3[0];
  $eminute=$etimeArr3[1];
  $tmpStr='';
  if(time()-strtotime($event_time) >= 60*60*24*365){
  	$tmpStr=$eyear."年".$emonth."月";
  }else{
		if(time()-strtotime($event_time) < 60*60){
			$tmpStr=intval((time()-strtotime($event_time))/60);
			$tmpStr=empty($tmpStr) ? 1:$tmpStr;
			$tmpStr=intval($tmpStr)."分钟前";
		}
		elseif(time()-strtotime($event_time) < 60*60*9 && time()-strtotime($event_time) >= 60*60){
			$tmpStr=intval((time()-strtotime($event_time))/(60*60))."小时前";
		}
		elseif(strtotime($nyear."-".$nmonth."-".$nday) - strtotime($eyear."-".$emonth."-".$eday) < 60*60*24  && time()-strtotime($event_time) >= 60*60*9){
			$tmpStr="今天".$ehour."时".$eminute."分";
		}
		elseif(strtotime($nyear."-".$nmonth."-".$nday) - strtotime($eyear."-".$emonth."-".$eday) >= 60*60*24 && strtotime($nyear."-".$nmonth."-".$nday) - strtotime($eyear."-".$emonth."-".$eday) < 60*60*24*2){
			$tmpStr="昨天".$ehour."时".$eminute."分";
		}
		elseif(strtotime($nyear."-".$nmonth."-".$nday) - strtotime($eyear."-".$emonth."-".$eday) < 60*60*24*3 && strtotime($nyear."-".$nmonth."-".$nday) - strtotime($eyear."-".$emonth."-".$eday) >= 60*60*24*2){
			$tmpStr="前天".$ehour."时".$eminute."分";
		}
		else{
			$tmpStr=$emonth."月".$eday."日";
		}
	}
	return $tmpStr;
}

//格式化日期文本函数(短的)
function format_datetime_short($event_time){
  $nyear=date("Y", time());
  $nmonth=date("m", time());
  $nday=date("d", time());
  $nhour=date("H", time());
  $nminute=date("i", time());
  
  $etimeArr=explode('-',$event_time);
  $eyear=$etimeArr[0];
  $emonth=$etimeArr[1];
  $etimeArr2=explode(' ',$etimeArr[2]);
  $eday=$etimeArr2[0];
  $etimeArr3=explode(':',$etimeArr2[1]);
  $ehour=$etimeArr3[0];
  $eminute=$etimeArr3[1];
  $tmpStr='';
  if(time()-strtotime($event_time) >= 60*60*24*365){
  	$tmpStr=$eyear."年".$emonth."月";
  }else{
		if(time()-strtotime($event_time) < 60*60){
			$tmpStr=intval((time()-strtotime($event_time))/60);
			$tmpStr=empty($tmpStr) ? 1:$tmpStr;
			$tmpStr=intval($tmpStr)."分钟前";
		}
		elseif(time()-strtotime($event_time) < 60*60*9 && time()-strtotime($event_time) >= 60*60){
			$tmpStr=intval((time()-strtotime($event_time))/(60*60))."小时前";
		}
		elseif(strtotime($nyear."-".$nmonth."-".$nday) - strtotime($eyear."-".$emonth."-".$eday) < 60*60*24  && time()-strtotime($event_time) >= 60*60*9){
			$tmpStr="今天";
		}
		elseif(strtotime($nyear."-".$nmonth."-".$nday) - strtotime($eyear."-".$emonth."-".$eday) >= 60*60*24 && strtotime($nyear."-".$nmonth."-".$nday) - strtotime($eyear."-".$emonth."-".$eday) < 60*60*24*2){
			$tmpStr="昨天";
		}
		elseif(strtotime($nyear."-".$nmonth."-".$nday) - strtotime($eyear."-".$emonth."-".$eday) < 60*60*24*3 && strtotime($nyear."-".$nmonth."-".$nday) - strtotime($eyear."-".$emonth."-".$eday) >= 60*60*24*2){
			$tmpStr="前天";
		}
		else{
			$tmpStr=$emonth."月".$eday."日";
		}
	}
	return $tmpStr;
}

function leave_time($date_value,$life_time){
	if(preg_match("/\d{4}-\d{2}-\d{2}/",$date_value)){
		$date_value=strtotime($date_value);
	}
	$life_sec=$life_time*60*60;
	$time=time();
	$pass_sec=$time-$date_value;
	if($pass_sec >= $life_sec){
		return '已过期';
	}else{
		$leave_sec=$life_sec-$pass_sec;
		return conver_time($leave_sec);
	}
}

function conver_time($second){
	$result_str='';
	$leave_time='';
	$leave_time=$leave_time ? $leave_time:$second;
	if($leave_day=floor($leave_time/(3600*24))){
		$leave_time=$leave_time-($leave_day*3600*24);
		$result_str.=$leave_day.'天';
	}
	$leave_time=$leave_time ? $leave_time:$second;
	if($leave_hour=floor($leave_time/3600)){
		$leave_time=$leave_time-($leave_hour*3600);
		$result_str.=$leave_hour.'小时';
	}
	$leave_time=$leave_time ? $leave_time:$second;
	if($leave_min=floor($leave_time/60)){
		$leave_time=$leave_time-($leave_min*60);
		$result_str.=$leave_min.'分钟';
	}
	if($leave_time==$second){
		$result_str='1分钟以内';
	}	
	return $result_str;
}

function info_item_format($defaultTxt,$inputTxt){
    if($inputTxt!=''){
    	 return $inputTxt;
    }else{
    	 return $defaultTxt;
    }
}

function brithday_format($y,$m,$d){
	$tmp='';
    if($y!=''){
    	$tmp=$y."年";
    }
    if($m!=''){
    	$tmp=$tmp.$m."年";
    }
    if($d!=''){
    	$tmp=$tmp.$d."日";
    }
    return $tmp;
}

function get_thirdperson($usex=1){
	 if($usex==0){
	 	 return "她";
	 }	 
	 if($usex==1){
	 	 return "他";
	 }
}

function radio_checked($input_val,$rs_val){
	if($input_val==$rs_val){
	  return ' checked ';
	}
}

//列表控制
$def_show_list=0;
function newline($num){
	global $def_show_list;
	if($def_show_list%$num==0){
		if($def_show_list>0){ echo "</div>"; }
		echo "<div style='clear:both'>";
	}
	$def_show_list++;
}

//取得后缀名
function get_ext($file_name) 
{
	$extend=explode("." , $file_name); 
	$va=count($extend)-1;
	$ext_name=trim($extend[$va]);
	if($ext_name=='jpeg'){
		 $ext_name='jpg';
	}
	return $ext_name; 
}
?>
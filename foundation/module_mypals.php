<?php
//引入公共方法
	require_once("foundation/fsqlseletiem_set.php");
	
//引入语言包
	$mp_langpackage=new mypalslp;

	$lp_wait=$mp_langpackage->mp_wait_confirm;
	$lp_other=$mp_langpackage->mp_other_confirm;
	$lp_both=$mp_langpackage->mp_both_confirm;
	$lp_woman=$mp_langpackage->mp_woman;
	$lp_t_woman=$mp_langpackage->mp_third_woman;
	$lp_man=$mp_langpackage->mp_man;
	$lp_t_man=$mp_langpackage->mp_third_man;
	$lp_unset=$mp_langpackage->mp_unset;
	$lp_p_sel=$mp_langpackage->mp_p_sel;


//获取我的好友列表字符串
function getMypals($dbo,$user_id,$t_mypals){
	$mypals_id='';
	$sql="select pals_id from $t_mypals where user_id='$user_id' and accepted>='1'";
	$mypals_rs=$dbo->getRs($sql);
	$comma_str='';
	$i=0;
	foreach($mypals_rs as $rs){
		if($i>0){
			 $comma_str=',';
		}
	  $mypals_id.=$comma_str.$rs[0];
	  $i++;
	}
	return $mypals_id;
}

//获取我的好友列表数组
function getPals_mine($dbo,$select_items,$t_pals,$userid,$limit){
	  return getPals_mine_base($dbo,$select_items,$t_pals,$userid,1,$limit);
}

function getPals_mine_all($dbo,$t_pals,$userid){
 	  return getPals_mine_base($dbo,'*',$t_pals,$userid,1,'');
}

function getPals_mine_base($dbo,$select_items,$t_pals,$userid,$accpara,$limit){
	$acceptedStr='';
	if($limit!=''){$limit='LIMIT '.$limit;}
	if($accpara==1){$acceptedStr=' and accepted>=1 ';}
	$si_item_sql=get_select_item_sql($select_items);
	$sql="select $si_item_sql from $t_pals where user_id='$userid' $acceptedStr order by add_time desc $limit";
	return $dbo->getRs($sql);
}

function get_pals_sex($pals_sex){
	
	global $lp_woman;
	global $lp_man;
	
  if($pals_sex=="0"){ $pSexStr=$lp_woman; }
  if($pals_sex=="1"){ $pSexStr=$lp_man; }
  
  return $pSexStr;
}

function get_TP_pals_sex($pals_sex){
	
	global $lp_t_man;
	global $lp_t_woman;	

  if($pals_sex=="0"){ $pSexTpStr=$lp_t_woman; }
  if($pals_sex=="1"){ $pSexTpStr=$lp_t_man; }
  
  return $pSexTpStr;
}
	
function get_pals_state($accepted){
	global $lp_wait;
	global $lp_other;
	global $lp_both;
	$pStateStr="";
	if($accepted=="0"){ $pStateStr=$lp_wait; }
	if($accepted=="1"){ $pStateStr=$lp_other; }
	if($accepted=="2"){ $pStateStr=$lp_both; }
	return $pStateStr;
}

function get_pals_reside($reside_province,$reside_city){
	global $lp_unset;
	$reside_str=$reside_province.",".$reside_city;
	if(empty($reside_province)&&empty($reside_city)){
		$reside_str=$lp_unset;
	}
	return $reside_str;
}
	
function get_pals_birth($birth_province,$birth_city){
	global $lp_unset;
	$birth_str=$birth_province.",".$birth_city;
	if(empty($birth_province)&&empty($birth_city)){
		$birth_str=$lp_unset;
	}
	return $birth_str;
}
?>    
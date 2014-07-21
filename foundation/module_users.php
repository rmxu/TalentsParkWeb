<?php
	//引入语言包
	$user_langpackage=new userslp;
	$u_man=$user_langpackage->u_man;
	$u_wen=$user_langpackage->u_wen;
	$u_sec=$user_langpackage->u_sec;
	$u_marr_n=$user_langpackage->u_marr_n;
	$u_marr_y=$user_langpackage->u_marr_y;
	$lp_u_not=$user_langpackage->u_select;
	$lp_u_year=$user_langpackage->u_year;
	$lp_u_month=$user_langpackage->u_month;
	$lp_u_day=$user_langpackage->u_day;
	$u_b_can=$user_langpackage->u_b_can;
	$u_b_con=$user_langpackage->u_b_con;
	$u_choose_type=$user_langpackage->u_choose_type;
	$u_to_you=$user_langpackage->u_to_you;
//引入公共方法
require_once("foundation/fsqlseletiem_set.php");

function update_user_ico(&$dbo,$t_table,$field_ico,$field_id,$value_id,$ico_url){
   $sql="update $t_table set $field_ico='$ico_url' where $field_id='$value_id'";
   if($dbo->exeUpdate($sql)){
      return true;
   }else{
   	  return false;
   }
}

function get_user_info_item($dbo,$select_items,$t_user,$uid)
{
	   $si_item_sql=get_select_item_sql($select_items);
	   $sql="select $si_item_sql from $t_user where user_id=$uid";
		 return $dbo->getRow($sql);
}

//获取空间主人的姓名
function get_hodler_name($holder_id){
	$holder_name=get_session($holder_id.'_holder_name');
	if($holder_name==''){
		$user_info=api_proxy("user_self_by_uid","user_name",$holder_id);
		set_session($holder_id.'_holder_name',$user_info['user_name']);
		$holder_name=$user_info['user_name'];
	}
	return $holder_name;
}

function get_user_info(&$dbo,$table,$uid)
{
		return get_user_info_item($dbo,'*',$table,$uid);
}


function get_user_online_state(&$dbo,$t_online,$uid)
{
		$sql="select hidden from $t_online where user_id=$uid";
	  return $dbo->getRow($sql);
}

function get_user_sex($sexPara){
	global $u_man;
	global $u_wen;
   if($sexPara=='0'){
	 	   return $u_wen;
   }else if($sexPara=='1'){
	 	   return $u_man;
   }else {
   	   return '';
   }
}

function get_user_marry($marryPara){
	global $u_sec;
	global $u_marr_n;
	global $u_marr_y;

	 $tmp='';
	 if($marryPara=='0'){
	 	   $tmp=$u_sec;
	 }
	 if($marryPara=='1'){
	 	   $tmp=$u_marr_n;
   }
   if($marryPara=='2'){
   	   $tmp=$u_marr_y;
   }
   return $tmp;
}

function get_user_blood($bloodPara){
	global $u_sec;
	 $tmp='';
	 if($bloodPara=='0'){
	 	   $tmp=$u_sec;
	 }else{
	 	   $tmp=$bloodPara;
	 }
   return $tmp;
}

function get_birth_date($b_year,$b_month,$b_day){
	global $setMinYear;
	global $setMaxYear;
	global $lp_u_not;
	global $lp_u_year;
	global $lp_u_month;
	global $lp_u_day;

	//出生年
	echo "
	<select id='birth_year' name='birth_year'>
		<option value=''>$lp_u_not</option>";
			for($i=$setMinYear; $i<=$setMaxYear; $i++){
				echo "<option value=\"$i\"";
				if($b_year==$i){
					echo "selected=selected";
					}
				echo ">$i</option>";
			}
	echo "</select>";
	echo $lp_u_year;

	//出生月
	echo "
	<select id='birth_month' name='birth_month'>
		<option value=''>--</option>";
			for($i=1; $i<=12; $i++){
				echo "<option value=\"$i\"";
				if($b_month==$i){
					echo "selected=selected";
				}
				echo ">$i</option>";
			}
	echo "</select>";
	echo $lp_u_month;

	//出生日
	echo "
	<select id='birth_day' name='birth_day'>
		<option value=''>--</option>";
			for($i=1; $i<=31; $i++){
				echo "<option value=\"$i\"";
				if($b_day==$i){
					echo "selected=selected";
					}
				echo ">$i</option>";
			}
	echo "</select>";
	echo $lp_u_day;
}

function get_blood($u_blood){
	global $u_sec;
	echo "
	<select id='blood' name='blood'>
		<option value='0'>$u_sec</option>";
			foreach (array('A','B','O','AB') as $value){
				echo "<option value=\"$value\"";
					if($u_blood==$value){
						echo "selected=selected";
						}
				echo ">$value</option>";
				}
	echo "</select>";
}

function pri_ques($acc_ques,$acc_answ,$holder_id){
	$q_arr=explode(',',$acc_ques);
	$an_array=explode(',',$acc_answ);
	echo '<select name="questions" size="1" style="width:165px">';
	set_session($holder_id.'homeAccessAnswers',$an_array);
	$i=0;
	foreach($q_arr as $key=>$qstr){
	if($qstr!=""){
	echo "<option value=$key>$qstr</option>";
	}
	$i++;
	}
echo '</select>';
}

function search_age_range(){
	echo '<select name="min_age">';
	for($i=10;$i<=65;$i++){
		if($i==18){
			echo "<option value=$i selected=seleced>$i</option>";
		}else{
			echo "<option value=$i>$i</option>";
			}
	}
	echo '</select>&nbsp至&nbsp';
	echo '<select name="max_age">';
	for($i=18;$i<=65;$i++){
		if($i==25){
			echo "<option value=$i selected=seleced>$i</option>";
		}else{
			echo "<option value=$i>$i</option>";
		}
	}
}

$hi_langpackage=new hilp;
$lp_hi_type_0=$hi_langpackage->hi_type_0;
$lp_hi_type_1=$hi_langpackage->hi_type_1;
$lp_hi_type_2=$hi_langpackage->hi_type_2;
$lp_hi_type_3=$hi_langpackage->hi_type_3;
$lp_hi_type_4=$hi_langpackage->hi_type_4;
$lp_hi_type_5=$hi_langpackage->hi_type_5;
$lp_hi_type_6=$hi_langpackage->hi_type_6;
$lp_hi_type_7=$hi_langpackage->hi_type_7;
$lp_hi_type_8=$hi_langpackage->hi_type_8;
$lp_hi_type_9=$hi_langpackage->hi_type_9;
$lp_hi_type_10=$hi_langpackage->hi_type_10;
$lp_hi_type_11=$hi_langpackage->hi_type_11;

function show_hi_type($hi){
	global $lp_hi_type_0;
	global $lp_hi_type_1;
	global $lp_hi_type_2;
	global $lp_hi_type_3;
	global $lp_hi_type_4;
	global $lp_hi_type_5;
	global $lp_hi_type_6;
	global $lp_hi_type_7;
	global $lp_hi_type_8;
	global $lp_hi_type_9;
	global $lp_hi_type_10;
	global $lp_hi_type_11;
	global $u_to_you;
	$hi_str=$u_to_you.${'lp_hi_type_'.$hi};
	return $hi_str;
}

function hi_window(){
	global $lp_hi_type_0;
	global $lp_hi_type_1;
	global $lp_hi_type_2;
	global $lp_hi_type_3;
	global $lp_hi_type_4;
	global $lp_hi_type_5;
	global $lp_hi_type_6;
	global $lp_hi_type_7;
	global $lp_hi_type_8;
	global $lp_hi_type_9;
	global $lp_hi_type_10;
	global $lp_hi_type_11;
	global $u_b_can;
	global $u_b_con;
	global $skinUrl;

	$str = '';
	$str .= '<div class="hi_list">';
	for($i=0; $i<12; $i++)
	{
		$str .= '<li><input type="radio" '.($i ? '' : 'checked="checked"').' name="hi_type" value="'.$i.'" /><img src="skin/'.$skinUrl.'/images/pokeact_'.$i.'.gif">'.${'lp_hi_type_'.$i}.'</li>';
	}
	$str .= '</div>';
	return $str;
}

function get_dressup($dbo,$table,$holder_id,$dress_type,$dress_name){
	global $skinUrl;
	$home_dress_url='';
	$tpl_array=explode("/",$skinUrl);
	$tpl_name=$tpl_array[0];
	$dress_url="skin/".$tpl_name."/home/";
	if(get_session($holder_id.'_dressup')==NULL){
		$sql="select dressup from $table where user_id=$holder_id";
		$user_array=$dbo->getRow($sql);
		set_session($holder_id.'_dressup',$user_array['dressup']);
	}
	if($dress_name!=NULL || get_session($holder_id.'_dressup')!='0'){
		require($dress_url."tip.php");
		if(isset($home_dressup_array[get_session($holder_id.'_dressup')][$dress_type])){
			$home_dress_url=$home_dressup_array[get_session($holder_id.'_dressup')][$dress_type];
		}
		if(isset($home_dressup_array[$dress_name][$dress_type])){
			$home_dress_url=$home_dressup_array[$dress_name][$dress_type];
			if($dress_type=='home'){
				echo '<script>dress_home("'.$dress_name.'");</script>';
			}
		}
		echo '<link rel="stylesheet" type="text/css" href="'.$dress_url.$home_dress_url.'">';
	}
}

?>
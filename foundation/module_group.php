<?php
//引入语言包
$g_langpackage=new grouplp;
$lp_no_pri=$g_langpackage->g_no_privilege;
$lp_cho=$g_langpackage->g_cho;
$lp_type_freedom=$g_langpackage->g_freedom_join;
$lp_type_check=$g_langpackage->g_check_join;
$lp_type_refuse=$g_langpackage->g_refuse_join;
$lp_join_group=$g_langpackage->g_click_join;
$lp_g_repeat=$g_langpackage->g_rep_join;
$lp_rep_reg=$g_langpackage->g_rep_reg;
$lp_creator=$g_langpackage->g_creator;
$lp_manager=$g_langpackage->g_manager;
$lp_normal=$g_langpackage->g_m_normal;

//判定权限
function pri_limit($dbo,$user_id,$group_id){
	global $tablePreStr;
	global $lp_no_pri;
	$t_groups_member=$tablePreStr."group_members";
	$sql="select `role` from $t_groups_member where group_id=$group_id and user_id=$user_id and state=1";
	$pri_limit=$dbo->getRow($sql);
	$role=$pri_limit['role'];
	return $role;
}

function get_group_members($dbo,$table,$gid,$state){
	$sql="select * from $table where group_id='$gid' and state='$state' order by role";
	return $dbo->getRs($sql);
	}

function get_new_members($dbo,$table,$gid,$state){
	$sql="select * from $table where group_id='$gid' and state='$state' order by add_time desc limit 20";
	return $dbo->getALL($sql);
}

function group_sort_list($group_sort_rs,$selectedId)
{
	global $lp_cho;
    $type_list="<select class='form_select' name='group_type_id' id='group_type' onchange=document.getElementById('group_type_name').value=this.options[selectedIndex].text;>";
         $seleStr='';
         if($selectedId==""){ $seleStr='selected'; }
         $type_list=$type_list.'<option '.$seleStr.' value="">'.$lp_cho.'</option>';
         foreach($group_sort_rs as $rs){
           if($selectedId==$rs['id']){$seleStr='selected';}else{$seleStr='';}
            $type_list=$type_list.'<option '.$seleStr.' value="'.$rs['id'].'">'.$rs['name'].'</option>';
         }
   	$type_list=$type_list.'</select>';
   	return $type_list;
}

function join_type($type){
	global $lp_type_freedom;
	global $lp_type_check;
	global $lp_type_refuse;
	if($type==0) $str=$lp_type_freedom;
	if($type==1) $str=$lp_type_check;
	if($type==2) $str=$lp_type_refuse;
	return $str;
}

function group_state($g_id,$g_type,$u_creat_group,$u_join_group,$g_req){
	$user_id=get_sess_userid();
	global $lp_join_group;
	global $lp_type_refuse;
	global $lp_g_repeat;
	global $lp_rep_reg;
	$str="<a href='javascript:void(0)' onclick=join_group($g_id);>".$lp_join_group."</a>";
	if($g_type==2){$str="<font color='red'>".$lp_type_refuse."</font>";}
	else if(strpos(",$u_join_group",",$g_id,")||strpos(",$u_creat_group",",$g_id,")){$str=$lp_g_repeat;}
	else if(preg_match("/,$user_id,/",$g_req)){$str=$lp_rep_reg;}
	echo $str;
}

function get_group_manager($manager_name){
	if($manager_name==""||"|"){
		echo "无";
	}else{
		echo $manager_name;
	}
}

function get_group_role($creator,$manager){
	global $lp_creator;
	global $lp_manager;
	global $lp_normal;
	$user_id=get_sess_userid();
	if($user_id==$creator) echo $lp_creator;
	elseif(strstr($manager,",".$user_id.",")) echo $lp_manager;
	else echo $lp_normal;
}

function get_g_role($u_role){
	global $lp_creator;
	global $lp_manager;
	global $lp_normal;
	$role='';
	if($u_role==0){$role=$lp_creator;}
	if($u_role==1){$role=$lp_manager;}
	if($u_role==2){$role=$lp_normal;}
	return $role;
}

function show_manage_act($group_creat,$u_role,$g_id){
	$b_del="content_none";$b_app="content_none";$b_rev="content_none";
	$manage_act=array("b_del" => $b_del,"b_app" => $b_app,"b_rev" => $b_rev);
	if($u_role==2){
		$b_del="";$b_app="content_none";$b_rev="content_none";
		$manage_act=array("b_del" => $b_del,"b_app" => $b_app,"b_rev" => $b_rev);
		}
	if(preg_match("/,$g_id,/",$group_creat)&&$u_role==2){
		$b_del="";$b_app="";$b_rev="content_none";
		$manage_act=array("b_del" => $b_del,"b_app" => $b_app,"b_rev" => $b_rev);
		}
	if(preg_match("/,$g_id,/",$group_creat)&&$u_role==1){
		$b_del="content_none";$b_app="content_none";$b_rev="";
		$manage_act=array("b_del" => $b_del,"b_app" => $b_app,"b_rev" => $b_rev);
		}
	return $manage_act;
}

function show_action($creator,$member_num,$manager){
	$exit_action="content_none";
	$drop_action="content_none";
	$manage_action="content_none";
	$user_id=get_sess_userid();
		if($user_id==$creator&&$member_num<=1){
			$drop_action="";
		}
		if(strstr($manager,",".$user_id.",")||$user_id==$creator){
			$manage_action="";
		}
		else{
			$exit_action="";
			}
	return $action=array("drop"=>$drop_action,"manage"=>$manage_action,"exit"=>$exit_action);
}

function is_member($g_id,$u_join,$u_creat){
	$action_show="content_none";
	if(!preg_match("/,$g_id,/",$u_join)&&!preg_match("/,$g_id,/",$u_creat)){
		$action_show="";
		}
	return $action_show;
}

function get_db_data($dbo,$table,$condition,$order_by,$type){
	$sql="select * from $table where $condition $order_by";
	$db_result=$dbo->{$type}($sql);
	return $db_result;
}
?>
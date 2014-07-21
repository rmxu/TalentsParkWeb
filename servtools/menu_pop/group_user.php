<?php
//引入公共方法
$session_power=true;
require(dirname(__FILE__)."/../../api/base_support.php");
if(get_sess_userid()){
	$group_users=api_proxy('pals_self_by_uid',"pals_id,pals_name,pals_ico,pals_sort_id,pals_sort_name",get_sess_userid());
	$data_str='var menu_pop_data=[';
	$group_length=count($group_users);
	if($group_length){
		$group_pointer=-1;
		for($i=0;$i < $group_length;$i++){
			if($group_pointer==-1||$group_pointer!=$group_users[$i]['pals_sort_id']){
				$group_pointer=$group_users[$i]['pals_sort_id'];
				if($data_str!='var menu_pop_data=['){
					$data_str=substr($data_str,0,-1);
					$data_str.=']],';
				}
				if($group_users[$i]['pals_sort_id']==0){
					$data_str.='["'.$group_users[$i]['pals_sort_id'].'","默认分组",[';
				}else{
					$data_str.='["'.$group_users[$i]['pals_sort_id'].'","'.$group_users[$i]['pals_sort_name'].'",[';
				}
			}
			$data_str.='["'.$group_users[$i]['pals_id'].'","'.$group_users[$i]['pals_name'].'","'.$group_users[$i]['pals_ico'].'"],';
		}
		if($data_str!='var menu_pop_data=['){
			$data_str=substr($data_str,0,-1);
			$data_str.=']]];';
		}
		echo $data_str;
	}else{
		echo $data_str.'];';
	}
}else{
	echo 'var menu_pop_data=0;';
}
?>
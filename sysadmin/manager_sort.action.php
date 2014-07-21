<?php
require("session_check.php");
require("../api/Check_MC.php");

	//变量区
	$sort_id=intval(get_argg('sort_id'));
	$module=short_check(get_argg('module'));
	$type=short_check(get_argg('type_value'));

	$dbo = new dbex;
	dbtarget('w',$dbServs);

	if($module=="group"){
		//表定义区
		$sort_table=$tablePreStr."group_type";
		$main_table=$tablePreStr."groups";
		$key_mt='group_sort/list/order_num/0/all_mt';
	}else if($module=="pals"){
		//表定义区
		$main_table=$tablePreStr."pals_mine";
		$t_pals_sort=$tablePreStr."pals_sort";
		$sort_table=$tablePreStr."pals_def_sort";
		$key_mt='pals_def_sort/list/order_num/0/all_mt';
	}
		switch($type){
			case "add":
			if($module=="group"){
				$is_check=check_rights("a07");
				if(!$is_check){
					echo $m_langpackage->m_no_pri;
					exit;
				}
			}else{
				$is_check=check_rights("a11");
				if(!$is_check){
					echo $m_langpackage->m_no_pri;
					exit;
				}
			}
				$sort_name=get_argp('sort_name');
				$sort_order=intval(get_argp('sort_order'));
				$sql="insert into $sort_table(name,order_num) value('$sort_name',$sort_order)";
				$dbo->exeUpdate($sql);
			break;

			case "del":
			if($module=="group"){
				$is_check=check_rights("a09");
				if(!$is_check){
					echo $m_langpackage->m_no_pri;
					exit;
				}
			}else{
				$is_check=check_rights("a13");
				if(!$is_check){
					echo $m_langpackage->m_no_pri;
					exit;
				}
			}
				$sql="delete from $sort_table where id=$sort_id";
				$dbo->exeUpdate($sql);
			break;

			case "change":
			if($module=="group"){
				$is_check=check_rights("a08");
				if(!$is_check){
					echo $m_langpackage->m_no_pri;
					exit;
				}
			}else{
				$is_check=check_rights("a12");
				if(!$is_check){
					echo $m_langpackage->m_no_pri;
					exit;
				}
			}
				$sort_name=get_argp('sort_name');
				$sort_order=get_argp('sort_order');
				$sql="update $sort_table set name='$sort_name',order_num=$sort_order where id=$sort_id";
				$dbo->exeUpdate($sql);
			break;

			default:
			echo "error";
			break;
		}
		updateCache($key_mt);
?>
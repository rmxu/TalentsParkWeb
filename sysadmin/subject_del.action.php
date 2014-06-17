<?php
	require("session_check.php");	
	require("../foundation/aintegral.php");
	$is_check=check_rights("c19");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//语言包引入
	$m_langpackage=new modulelp;
	
	//判断是否批量删除
	if(get_argp('checkany')){//批量
		$dbo = new dbex;
		dbtarget('r',$dbServs);
		dbtarget('w',$dbServs);
		$subject_ids = get_argp('checkany');
		//表定义区
		$t_group_subject=$tablePreStr."group_subject";
		$t_group_comment=$tablePreStr."group_subject_comment";
		$t_uploadfile=$tablePreStr."uploadfile";
		$t_group=$tablePreStr."groups";
		foreach($subject_ids as $rs){
			$sql="select * from $t_group_subject where subject_id=$rs";
			$subjects = $dbo->getRow($sql);
			$group_id = $subjects['group_id'];
			$sendor_id = $subjects['user_id'];
			$subject_content=$subjects['content'];
			//删除主题内部的图片
			preg_match_all("/classId=\"\d\"/",$subject_content,$match);
			$match=preg_replace("/[classId=,\"]/",'',$match[0]);
			if(!empty($match)){
				echo(empty($match));
				$match=join(",",$match);
				$sql="select file_src from $t_uploadfile where id in ($match)";
				$file_src=$dbo->getRs($sql);
				foreach($file_src as $val){
					unlink($val['file_src']);
				}
			}
			if(!empty($match)){
				$sql="delete from $t_uploadfile where id in ($match)";
				$dbo->exeUpdate($sql);
			}
			$sql="delete from $t_group_subject where subject_id=$rs";
			if($dbo->exeUpdate($sql)){
				print_r($dbo->exeUpdate($sql));
				$sql="update $t_group set subjects_num=subjects_num-1 where group_id=$group_id";
				$dbo->exeUpdate($sql);
				$sql="delete from $t_group_comment where subject_id=$rs";
				$dbo->exeUpdate($sql);
				increase_integral($dbo,$int_del_subject,$sendor_id);	
			}
		}
	}else{//单条
		//变量区
		if(empty($subject_id)){
			$subject_id=intval(intval(get_argg('subject_id')));
			$group_id=intval(intval(get_argg('group_id')));
			$sendor_id=intval(intval(get_argg('sendor_id')));
		}
		//表定义区
		$t_group_subject=$tablePreStr."group_subject";
		$t_group_comment=$tablePreStr."group_subject_comment";
		$t_uploadfile=$tablePreStr."uploadfile";
		$t_group=$tablePreStr."groups";
		
		$dbo = new dbex;
		dbtarget('r',$dbServs);
		//删除主题内部的图片
		$sql="select content,group_id from $t_group_subject where subject_id=$subject_id";
		$subject_content=$dbo->getRow($sql);
		$subject_content=$subject_content['content'];
		preg_match_all("/classId=\"\d\"/",$subject_content,$match);
		$match=preg_replace("/[classId=,\"]/",'',$match[0]);
		$group_id=$subject_content['group_id'];
		if(!empty($match)){
			$match=join(",",$match);
			$sql="select file_src from $t_uploadfile where id in ($match)";
			$file_src=$dbo->getRs($sql);
			foreach($file_src as $rs){
				unlink($rs['file_src']);
			}
		}
		$dbo = new dbex;
		dbtarget('w',$dbServs);
		if(!empty($match)){
			$sql="delete from $t_uploadfile where id in ($match)";
			$dbo->exeUpdate($sql);
		}
		$sql="delete from $t_group_subject where subject_id=$subject_id";
		if($dbo->exeUpdate($sql)){
			$sql="update $t_group set subjects_num=subjects_num-1 where group_id=$group_id";
			$dbo->exeUpdate($sql);
			
			$sql="delete from $t_group_comment where subject_id=$subject_id";
			$dbo->exeUpdate($sql);
			increase_integral($dbo,$int_del_subject,$sendor_id);
			echo $m_langpackage->m_del_suc;		
		}
	}
?>
<script language="javascript" type="text/javascript">
window.location.href='subject_list.php?order_by=subject_id&order_sc=desc';
</script>
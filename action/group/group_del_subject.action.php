<?php
//引入公共函数
	require("foundation/module_group.php");
	require("foundation/aintegral.php");
	require("api/base_support.php");

//引入语言包
	$g_langpackage=new grouplp;

//变量区
	$user_id=get_sess_userid();
	$group_id=intval(get_argg('group_id'));
	$subject_id=intval(get_argg('subject_id'));
	$u_id=intval(get_argg('user_id'));
	$sendor_id=intval(get_argg('sendor_id'));

//定义读操作
	dbtarget('r',$dbServs);
	$dbo=new dbex();

//数据表定义
	$t_group_members=$tablePreStr."group_members";
  $t_group_subject=$tablePreStr."group_subject";
  $t_group_comment=$tablePreStr."group_subject_comment";
  $t_uploadfile=$tablePreStr."uploadfile";
  $t_group=$tablePreStr."groups";

//权限判断
	$role=pri_limit($dbo,$user_id,$group_id);
	if($role==2){
		action_return(0,"$g_langpackage->g_no_privilege","-1");
	}

//删除主题内部的图片
	$subject_content=api_proxy("group_sub_by_sid","content",$subject_id);
	$subject_content=$subject_content['content'];
	preg_match_all("/classId=\"\d\"/",$subject_content,$match);
  $match=preg_replace("/[classId=,\"]/",'',$match[0]);
	if(!empty($match)){
		$match=join(",",$match);
		$sql="select file_src from $t_uploadfile where id in ($match)";
		$file_src=$dbo->getRs($sql);
		foreach($file_src as $rs){
			unlink($rs['file_src']);
		}
	}
//定义写操作
    dbtarget('w',$dbServs);
    if(!empty($match)){
    $sql="delete from $t_uploadfile where id in ($match)";
    $dbo->exeUpdate($sql);
  }

    $sql="delete from $t_group_subject where subject_id=$subject_id";
    $dbo->exeUpdate($sql);

 		$sql="update $t_group set subjects_num=subjects_num-1 where group_id=$group_id";
		$dbo->exeUpdate($sql);

    $sql="delete from $t_group_comment where subject_id=$subject_id";
    $dbo->exeUpdate($sql);

    $jump="modules.php?app=group_space&group_id=$group_id&user_id=".$u_id;

    increase_integral($dbo,$int_del_subject,$sendor_id);

   action_return(1,"",$jump);

?>


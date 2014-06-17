<?php
	//引入公共方法
	require("foundation/aintegral.php");
	require("foundation/module_affair.php");
	require("api/base_support.php");
	//引入语言包
	$b_langpackage=new bloglp;

	//变量取得
  $ulog_id=intval(get_argg("id"));
	$user_id=get_sess_userid();

	//数据表定义区
	$t_blog=$tablePreStr."blog";
	$t_uploadfile=$tablePreStr."uploadfile";
	$t_blog_comments=$tablePreStr."blog_comment";

	$dbo = new dbex;

	//读写分离定义函数
	dbtarget('r',$dbServs);

	//删除blog内部的图片
	$blog_content=api_proxy("blog_self_by_bid","log_content",$ulog_id);
	$blog_content=$blog_content['log_content'];
	preg_match_all("/classId=\"\d\"/",$blog_content,$match);
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

	$sql = "delete from $t_blog where log_id=$ulog_id and user_id=$user_id";

	if($dbo->exeUpdate($sql)&&mysql_affected_rows()>0){
		$sql="delete from $t_blog_comments where log_id=$ulog_id";
		$dbo->exeUpdate($sql);
    	increase_integral($dbo,$int_del_blog,$user_id);
    	del_affair($dbo,0,$ulog_id);
		action_return(1,'','modules.php?app=blog_list');
	}else{
		action_return(0,$b_langpackage->b_del_false);
	}
?>
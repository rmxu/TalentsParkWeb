<?php
	//引入语言包
	$b_langpackage=new bloglp;

	//变量取得
  $ulog_id=intval(get_argg("id"));
  $privacy=short_check(get_argp("privacy"));
  $ulog_title=short_check(get_argp("blog_title"));
  if(get_argp("blog_sort_list")){
  	$ulog_sort=short_check(get_argp("blog_sort_list"));
  }else{
  	$ulog_sort=0;
  }
  $ulog_txt=big_check(get_argp("CONTENT"));
  $blog_sort_name=short_check(get_argp('blog_sort_name'));
	$user_id=get_sess_userid();
	$user_name=get_sess_username();
	
	//数据表定义区
	$t_blog=$tablePreStr."blog";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);

	$sql= "update $t_blog set log_title='$ulog_title',privacy='$privacy',log_sort='$ulog_sort',log_content='$ulog_txt',edit_time=NOW(),log_sort_name='$blog_sort_name' where user_id=$user_id and log_id=$ulog_id";
 	if($dbo->exeUpdate($sql)){
	    action_return(1,'','modules.php?app=blog&id='.$ulog_id);
	}else{
		  action_return(0,'error','-1');
	}

?>

<?php
 //引入模块公共方法文件
 require("foundation/module_blog.php");
 require("api/base_support.php");

	//引入语言包
	$b_langpackage=new bloglp;

	//变量取得
  $ulog_sort=short_check(get_argp("new_sort"));
	$user_id=get_sess_userid();
	$user_name=get_sess_username();

	//数据表定义区
	$t_blog_sort=$tablePreStr."blog_sort";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);

	$sql = "insert into $t_blog_sort (name,user_id) value('$ulog_sort',$user_id)";

	if($dbo->exeUpdate($sql)){
		$sel_sort_id=mysql_insert_id();
		$blog_sort_rs = api_proxy("blog_sort_by_uid",$user_id);
		blog_sort_list($blog_sort_rs,$sel_sort_id);
	}else{
		echo $b_langpackage->b_add_fal;
	}

?>

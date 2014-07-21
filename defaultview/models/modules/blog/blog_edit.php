<?php
	//必须登录才能浏览该页面
	require("foundation/auser_mustlogin.php");
	require("api/base_support.php");

	//限制时间段访问站点
	limit_time($limit_action_time);

	//引入模块公共方法文件
	require("foundation/module_blog.php");
	require("foundation/module_album.php");
	require("foundation/fplugin.php");

	//语言包引入
	$b_langpackage=new bloglp;

	//变量定义
	$user_id=get_sess_userid();

	//数据表定义
	$t_blog_sort=$tablePreStr."blog_sort";
	$t_blog=$tablePreStr."blog";
	$t_album=$tablePreStr."album";

	$ulog_id=intval(get_argg('id'));
	$titleStr=$b_langpackage->b_creat;
	$goBackUrl='modules.php?app=blog_list';
	$ulogTitle='';
	$usubPara='';
	$ulogTxt='';
	$album_id='';
	$form_action="do.php?act=blog_add";
	$blog_sort_name='';
  $privacy='';
  $privacy_str='';
  $tag='';
	//判断是否编辑blog内容
	if($ulog_id!=""){
		$titleStr=$b_langpackage->b_edit;
		$goBackUrl='modules.php?app=blog&id='.$ulog_id;
		$result=api_proxy("blog_self_by_bid","*",$ulog_id);
		$ulogTitle=$result['log_title'];
		$usubPara=$result['log_sort'];
		$ulogTxt=$result['log_content'];
		$blog_sort_name=$result['log_sort_name'];
    $form_action="do.php?act=blog_edit&id=".$ulog_id;
    $privacy=$result['privacy'];
    $privacy_str="$t_blog:$ulog_id:$privacy";
    $tag=$result['tag'];
	}
	$album_rs = api_proxy("album_self_by_uid","album_id,album_name",$user_id);
	$blog_sort_rs = api_proxy("blog_sort_by_uid",$user_id);
?>
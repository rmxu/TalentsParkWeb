<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/blog/blog_list.html
 * 如果您的模型要进行修改，请修改 models/modules/blog/blog_list.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
  //引入公共模块
  require("foundation/fpages_bar.php");
	require("foundation/module_users.php");
	require("foundation/module_blog.php");
	require("foundation/fcontent_format.php");
	require("foundation/module_mypals.php");
	require("servtools/menu_pop/trans_pri.php");
	require("api/base_support.php");

  //语言包引入
  $b_langpackage=new bloglp;

  //变量区
	$url_uid=intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();
	$sort_name=get_argg('sort_name') ? urldecode(get_argg('sort_name')):"-1";
	$sort_id=get_argg('sort_id');
	if($sort_id===0){
		$sort_name=0;
	}

  //数据表定义
  $t_blog=$tablePreStr."blog";
  $t_users=$tablePreStr."users";

	dbtarget('r',$dbServs);
	$dbo=new dbex;
	$page_num=intval(get_argg('page'));
	$is_friend='';
	$no_data_text=$b_langpackage->b_no_fri_blog;

  //引入模块公共权限过程文件
	$is_self_mode='partLimit';
	$is_login_mode='';
	require("foundation/auser_validate.php");

	if($is_self=='Y'){
		$str_title=$b_langpackage->b_mine;
		$no_data_text=$b_langpackage->b_none;
		$url_userid='';
	}else{
		$holder_name=get_hodler_name($url_uid);
		$str_title=str_replace("{holder}",$holder_name,$b_langpackage->b_his_blog);
		$url_userid="&user_id=".$userid;
	}

	$blog_rs=array();
	$blog_rs=api_proxy("blog_self_by_uid","*",$userid,$sort_id);

	//控制数据显示
	$content_data_none="content_none";
	$isNull=0;
	if(empty($blog_rs)){
		$isNull=1;
		$content_data_none="";
	}
	?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<link rel="stylesheet" type="text/css" href="servtools/menu_pop/menu_pop.css">
<script type='text/javascript' src='servtools/ajax_client/ajax.js'></script>
<?php echo $is_self=='Y' ? "<script type='text/javascript' src='servtools/menu_pop/group_user.php'></script>" : "";?>
<script type='text/javascript' src="servtools/menu_pop/menu_pop.js"></script>
</head>
<body id="iframecontent" oncontextmenu='return false'>
<?php if($is_self=='Y'){?>
<div class="create_button"><a href="modules.php?app=blog_edit"><?php echo $b_langpackage->b_creat;?></a></div>
<?php }?>
<h2 class="app_blog"><?php echo $str_title;?></h2>
<?php if($is_self=='Y'){?>
<div class="tabs">
	<ul class="menu">
	  <li class="active"><a href="modules.php?app=blog_list" hidefocus="true"><?php echo $b_langpackage->b_mine;?></a></li>
	  <li><a href="modules.php?app=blog_friend" hidefocus="true"><?php echo $b_langpackage->b_friend;?></a></li>
  </ul>
</div>
<?php }?>
<?php foreach($blog_rs as $rs){?>
<?php $is_pri=check_pri($rs["user_id"],$rs["privacy"]);?>
<dl class="log_list" <?php if($is_self=='Y'){?>title="<?php echo $b_langpackage->b_right_set_competence;?>" onmouseDown="menu_pop_show(event,this);" id='<?php echo $t_blog;?>:<?php echo $rs['log_id'];?>:<?php echo $rs["privacy"];?>'<?php }?>>
	<dt>
		<strong><a href='<?php echo $is_pri ? rewrite_fun("modules.php?app=blog&id=".$rs['log_id'].$url_userid):"javascript:void(0)";?>'><?php echo $is_pri ? filt_word($rs["log_title"]): $b_langpackage->b_limit_blog;?></a></strong>
		<?php if(!$is_pri){?>
		<img src='skin/<?php echo $skinUrl;?>/images/user_privacye.gif' />
		<?php }?>
		<br /><span><?php echo $b_langpackage->b_sort;?>：<a href="modules.php?app=blog_list&sort_id=<?php echo $rs['log_sort'];?>&sort_name=<?php echo urlencode($rs['log_sort_name']);?><?php echo $url_userid;?>" title="<?php echo $b_langpackage->b_same_sort;?>"><?php echo empty($rs['log_sort_name']) ? $b_langpackage->b_default_sort :filt_word($rs['log_sort_name']);?></a></span><span><?php echo $rs["add_time"];?></span>
	</dt>
	<dd class="log_list_content"><?php echo $is_pri ? sub_str(strip_tags($rs["log_content"]),180):$b_langpackage->b_limit_blog;?></dd>
	<dd>
		<?php if($rs['tag']){?><span><?php echo $b_langpackage->b_label;?>：<?php echo $rs['tag'];?></span><?php }?>
		<span><?php echo $b_langpackage->b_limit;?>：<?php echo show_pri($rs["privacy"]);?></span>
		<span><?php echo str_replace("{b_com_num}",$rs['comments'],$b_langpackage->b_com_num);?></span><span>|</span><span><?php echo str_replace("{b_read_num}",$rs['hits'],$b_langpackage->b_read_num);?></span>
	</dd>
</dl>
<?php }?>
<?php page_show($isNull,$page_num,$page_total);?>
  <div class="guide_info <?php echo $content_data_none;?>">
		<?php echo $no_data_text;?>
  </div>
</body>
</html>
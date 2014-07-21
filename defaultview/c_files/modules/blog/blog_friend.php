<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/blog/blog_friend.html
 * 如果您的模型要进行修改，请修改 models/modules/blog/blog_friend.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入公共模块
	require("foundation/auser_mustlogin.php");
	require("foundation/fpages_bar.php");
	require("foundation/module_blog.php");
	require("foundation/fcontent_format.php");
	require("api/base_support.php");
	require("servtools/menu_pop/trans_pri.php");
	
	//语言包引入
	$b_langpackage=new bloglp;
	$rf_langpackage=new recaffairlp;
	
	//变量定义
	$user_id=get_sess_userid();
	$user_mypals=get_sess_mypals();
	
	//当前页面参数
	$page_num=trim(get_argg('page'));
	
	$blog_rs=array();
	$page_total='';
	if($user_mypals!=''){
		$blog_rs=api_proxy("blog_self_by_uid","*",$user_mypals);
  }
  	//控制数据显示
		$content_data_none="content_none";
		$content_data_set="";
		$isNull=0;
	if(empty($blog_rs)){
		$isNull=1;
		$content_data_none="";
		$content_data_set="content_none";
	}  
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<link rel="stylesheet" href="skin/<?php echo $skinUrl;?>/css/layout.css" />
</head>
<body id="iframecontent">
<div class="create_button"><a href="modules.php?app=blog_edit"><?php echo $b_langpackage->b_creat;?></a></div><h2 id="page_title" class="app_blog"><?php echo $b_langpackage->b_blog;?></h2>

<div class="tabs">
	<ul class="menu">
  	<li><a href="modules.php?app=blog_list" hidefocus="true"><?php echo $b_langpackage->b_mine;?></a></li>
  	<li class="active"><a href="modules.php?app=blog_friend" hidefocus="true"><?php echo $b_langpackage->b_friend;?></a></li>
  </ul>
</div>
	<?php foreach($blog_rs as $rs){?>
	<?php $is_pri=check_pri($rs["user_id"],$rs["privacy"]);?>
	<dl class="log_list friend">
		<div class="avatar"><a href="home.php?h=<?php echo $rs["user_id"];?>" target="_blank" title="<?php echo $rf_langpackage->rf_v_home;?>"><img src="<?php echo $rs["user_ico"];?>" /></a></div>
		<dt>
			<strong><a href='<?php echo $is_pri ? "modules.php?app=blog&id=".$rs['log_id']."&is_friend=1":"javascript:void(0)";?>'><?php echo $is_pri ? filt_word($rs["log_title"]): $b_langpackage->b_limit_blog;?></a></strong>
			<?php if(!$is_pri){?>
			<img src='skin/<?php echo $skinUrl;?>/images/user_privacye.gif' />
			<?php }?>
			<br /><span><?php echo $b_langpackage->b_sort;?>：<?php echo get_blog_sort(filt_word($rs['log_sort_name']));?></span><span><a href="home.php?h=<?php echo $rs["user_id"];?>" target="_blank"><?php echo filt_word($rs['user_name']);?></a></span><span><?php echo $rs["add_time"];?></span></dt>
			<dd class="log_list_content"><?php echo $is_pri ? get_short_txt($rs["log_content"]):$b_langpackage->b_limit_blog;?></dd>
			<dd>
        <span><?php echo $b_langpackage->b_label;?>：<?php echo $rs['tag'];?></span>
        <span><?php echo str_replace("{b_com_num}",$rs['comments'],$b_langpackage->b_com_num);?></span><span>|</span><span><?php echo str_replace("{b_read_num}",$rs['hits'],$b_langpackage->b_read_num);?></span><span>|</span><?php if($is_pri){?><span><a href="javascript:void(0);" onclick="parent.show_share(0,<?php echo $rs['log_id'];?>,'<?php echo $rs['log_title'];?>','');"><?php echo $b_langpackage->b_share;?></a></span><span>|</span><?php }?><span><a href="javascript:void(0);" onclick="parent.report(0,<?php echo $rs['user_id'];?>,<?php echo $rs['log_id'];?>);"><?php echo $b_langpackage->b_report;?></a></span></dd>
</dl>
	<?php }?>
<?php page_show($isNull,$page_num,$page_total);?>
<div class='guide_info <?php echo $content_data_none;?>'><?php echo $b_langpackage->b_no_fri_blog;?></div>
</body>
</html>
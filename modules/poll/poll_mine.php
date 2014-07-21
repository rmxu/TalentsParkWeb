<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/poll/poll_mine.html
 * 如果您的模型要进行修改，请修改 models/modules/poll/poll_mine.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入公共模块
	require("foundation/module_poll.php");
	require("foundation/fpages_bar.php");
	require("api/base_support.php");
	require("foundation/module_users.php");
	
	//引入语言包
	$pol_langpackage=new polllp;
	
	//变量声明区
	$ses_uid=get_sess_userid();
	$url_uid=intval(get_argg('user_id'));

  //引入模块公共权限过程文件
	$is_self_mode='partLimit';
	$is_login_mode='';
	require("foundation/auser_validate.php");
	$poll_rs=array();
	
	//当前页面参数
	$page_num=trim(get_argg('page'));

	$poll_rs=api_proxy("poll_self_by_uid","*",$userid);
	$title_str=$pol_langpackage->pol_mine;

	if($is_self=='N'){
		$holder_name=get_hodler_name($url_uid);
		$title_str=str_replace("{name}",$holder_name,$pol_langpackage->pol_who_page);
	}	

	//数据显示
	$none_data="content_none";	
	$isNull=0;
	if(empty($poll_rs)){
		$isNull=1;
		$none_data="";
	}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
</head>
<body id="iframecontent">
<?php if($is_self=='Y'){?>
<div class="create_button"><a href="javascript:location.href='modules.php?app=poll_send';"><?php echo $pol_langpackage->pol_send;?></a></div>
<?php }?>
<h2 class="app_vote"><?php echo $title_str;?></h2>
<?php if($is_self=='Y'){?>
<div class="tabs">
	<ul class="menu">
  	<li><a href="modules.php?app=poll_list&m=new" hidefocus="true"><?php echo $pol_langpackage->pol_new;?></a></li>
  	<li><a href="modules.php?app=poll_list&m=hot" hidefocus="true"><?php echo $pol_langpackage->pol_hot;?></a></li>
  	<li><a href="modules.php?app=poll_list&m=reward" hidefocus="true"><?php echo $pol_langpackage->pol_reward;?></a></li>
  	<li class="active"><a href="modules.php?app=poll_mine" hidefocus="true"><?php echo $pol_langpackage->pol_mine;?></a></li>
  </ul>
</div>
<?php }?>
<?php foreach($poll_rs as $rs){?>
<dl class="poll_list">
  <dt>
		<strong><a href="modules.php?app=poll&p_id=<?php echo $rs["p_id"];?>&user_id=<?php echo $userid;?>"><?php echo $rs['subject'];?></a></strong>
  <br /><label><?php echo $pol_langpackage->pol_this_vote_by;?> <a href="home.php?h=<?php echo $rs['user_id'];?>" target="_blank"><?php echo $rs['username'];?></a><?php echo $pol_langpackage->pol_launch;?></label><span><?php echo $rs['dateline'];?></span> </dt>
  <dd class="poll_list_content">
		<p><input type=<?php echo choo_type($rs['multiple']);?> disabled /><?php echo choo_option(filt_word($rs['option']),0);?></p>
		<p><input type=<?php echo choo_type($rs['multiple']);?> disabled /><?php echo choo_option(filt_word($rs['option']),1);?></p>
  </dd>
  <dd><span><?php echo $pol_langpackage->pol_whether_reward;?>：<?php echo $rs['percredit']?$pol_langpackage->pol_yes:$pol_langpackage->pol_no;?></span><span>|</span><span><?php echo $pol_langpackage->pol_status;?>：<?php if(strtotime($rs['expiration']) <= time()){?><?php echo $pol_langpackage->pol_expired;?><?php }?><?php if(strtotime($rs['expiration']) >= time()){?><a href="modules.php?app=poll&p_id=<?php echo $rs['p_id'];?><?php echo $url_uid?'&user_id=$url_uid':'';?>&m=<?php echo $mod;?>"><?php echo $pol_langpackage->pol_immediately_involved;?></a><?php }?></span><span>|</span><span><?php echo $pol_langpackage->pol_comment;?>(<?php echo $rs['comments'];?>)</span></dd>
  <div class="poll_status"><h4><strong><?php echo $rs['voternum'];?></strong><?php echo $pol_langpackage->pol_join;?></h4><a href="modules.php?app=poll&p_id=<?php echo $rs['p_id'];?>&user_id=<?php echo $userid;?>"><?php echo $pol_langpackage->pol_go_see;?></a></div>
</dl>
<?php }?>

<?php echo page_show($isNull,$page_num,$page_total);?>

<div class="guide_info <?php echo $none_data;?>">
	<?php echo $pol_langpackage->pol_data_none;?>
</div>

</body>
</html>
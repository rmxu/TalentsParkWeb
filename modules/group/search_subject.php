<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/group/search_subject.html
 * 如果您的模型要进行修改，请修改 models/modules/group/search_subject.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入公共函数
	require("foundation/module_group.php");
	require("foundation/fpages_bar.php");
	require("api/base_support.php");
	
	//引入语言包
	$g_langpackage=new grouplp;

	//变量区
	$role='';
	$user_id=get_sess_userid();
	$group_id=intval(get_argg('group_id'));
	$url_uid=intval(get_argg('user_id'));
	
	//链接地址变更
	$main_URL="content_none";
	$home_URL="";
	$is_admin=get_sess_admin();
	if($is_admin==''){
		$main_URL="";
		$home_URL="content_none";
	}
	$page_num=trim(get_argg('page')); 
	$key_word = short_check(get_argp('key_word'));
	
	//数据表定义
	$t_users=$tablePreStr."users";
	$t_groups=$tablePreStr."groups";
	$t_group_members=$tablePreStr."group_members";
	$t_group_subject=$tablePreStr."group_subject";
	$t_group_subject_comment=$tablePreStr."group_subject_comment";
	
	//定义读操作
	dbtarget('r',$dbServs);	
	$dbo=new dbex;
	$show_action=0;
	//权限判断
	$role=api_proxy("group_member_by_role",$group_id,$user_id);
	$role=$role[0];
	if(($role==0||$role==1) && isset($role)){
		$show_action=1;
	}
	
	$condition="group_id=$group_id and title like '%$key_word%'";
	$order_by="order by add_time desc";
	$type="getRs";
	$dbo->setPages(20,$page_num);//设置分页	
	$subject_rs=get_db_data($dbo,$t_group_subject,$condition,$order_by,$type);
	$page_total=$dbo->totalPage;//分页总数	
	
	//显示控制
	$isset_data="";
	$none_data="content_none";
	$isNull=0;
	if(empty($subject_rs)){
		$isNull=1;
		$isset_data="content_none";
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
	<div class="create_button"><a href="javascript:location.href='modules.php?app=group_space&group_id=<?php echo $group_id;?>&user_id=<?php echo $url_uid;?>'"><?php echo $g_langpackage->g_re_space;?></a></div>
	<h2 class="app_friend"><?php echo $g_langpackage->g_subject;?></h2>
	<div class="tabs">
    <ul class="menu">
      <li class="active"><a href="javascript:;"><?php echo $g_langpackage->g_search_result;?></a></li>
    </ul>
	</div>
	
<?php foreach($subject_rs as $rs){?>
<div class="poll_list_box <?php echo $isset_data;?>">
	<div class="poll_user">
		<a class="avatar" href="home.php?h=<?php echo $rs['user_id'];?>" target="_blank"><img src=<?php echo $rs['user_ico'];?> alt='<?php echo $rs['user_name'];?>' title='<?php echo $rs['user_name'];?>' /></a>
		<a href="home.php?h=<?php echo $rs['url_uid'];?>" target="_blank"><?php echo sub_str($rs['user_name'],6,true);?></a>
	</div>
	<div class="subject_content">
		<dl>
			<dt><a href="modules.php?app=group_sub_show&subject_id=<?php echo $rs['subject_id'];?>&group_id=<?php echo $group_id;?>&user_id=<?php echo $url_uid;?>"><?php echo filt_word($rs['title']);?></a></dt>
			<dd><a href="home.php?h=<?php echo $rs['user_id'];?>" target="_blank"><?php echo filt_word($rs['user_name']);?></a> <span class='gray' style='padding-left:15px'><?php echo str_replace("{date}",$rs['add_time'],$g_langpackage->g_send_time);?></span></dd>
			<dd><span class='gray'><?php echo $g_langpackage->g_read;?>(<?php echo $rs['hits'];?>)</span> <span class='gray' style='padding-left:15px;'><?php echo $g_langpackage->g_re;?>(<?php echo $rs['comments'];?>)</span></dd>
		</dl>
	</div>
	<div class="subject_status">
		<a href="modules.php?app=group_sub_show&subject_id=<?php echo $rs['subject_id'];?>&group_id=<?php echo $group_id;?>&user_id=<?php echo $url_uid;?>"><?php echo $g_langpackage->g_examine;?></a>
	</div>
</div>
<?php }?>
<?php echo page_show($isNull,$page_num,$page_total);?>
<div class="guide_info <?php echo $none_data;?>">
	<?php echo $g_langpackage->g_s_none_sub;?>,<a href='modules.php?app=group_space&group_id=<?php echo $group_id;?>&user_id=<?php echo $url_uid;?>'><?php echo $g_langpackage->g_re_search;?></a>
</div>

</body>
</html>
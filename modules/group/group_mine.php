<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/group/group_mine.html
 * 如果您的模型要进行修改，请修改 models/modules/group/group_mine.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入语言包
	$g_langpackage=new grouplp;

	$url_uid= intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();

	//引入模块公共权限过程文件
	$is_self_mode='partLimit';
	$is_login_mode='';
	require("foundation/auser_validate.php");
	require("foundation/module_users.php");
	require("foundation/module_group.php");
	require("api/base_support.php");

	$group_rs=array();

	//按钮控制
	$button=0;
	$button_show="content_none";
	$button_hidden="";

	if($is_self=='Y'){
		$group_title=$g_langpackage->g_mine;
		$no_data=$g_langpackage->g_none_group;
		$button=1;
		$button_show="";
		$button_hidden="content_none";
		$show_mine="";
		$show_his="content_none";
	}else{
		$show_mine="content_none";
		$show_his="";
		$holder_name=get_hodler_name($url_uid);
		$group_title=str_replace("{holder}",$holder_name,$g_langpackage->g_his_group);
		$no_data=$g_langpackage->g_none;
	}

	$group_rs = api_proxy("group_self_by_uid","*",$userid,'getRs');

	//数据显示控制
	$list_show="";
	$list_hidden="content_none";
	if(empty($group_rs)){
		$list_show="content_none";
		$list_hidden="";
	}

	$g_join_num=$g_langpackage->g_join_num;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
</head>
<body id="iframecontent">
  <?php if($is_self=='Y'){?>
  <div class="create_button"><a href="modules.php?app=group_select" hidefocus="true"><?php echo $g_langpackage->g_search_group;?></a></div>
  <div class="create_button"><a href="modules.php?app=group_creat"><?php echo $g_langpackage->g_creat;?></a></div>
  <?php }?>
  <h2 class="app_group"><?php echo $group_title;?></h2>
  <?php if($is_self=='Y'){?>
  <div class="tabs">
      <ul class="menu">
          <li class="active"><a href="modules.php?app=group" hidefocus="true"><?php echo $g_langpackage->g_mine;?></a></li>
          <li><a href="modules.php?app=group_hot" hidefocus="true"><?php echo $g_langpackage->g_hot;?></a></li>
      </ul>
  </div>
  <?php }?>
	<?php foreach($group_rs as $rs){?>
		<div class="group_box" onmouseover="this.className = 'group_box_active';" onmouseout="this.className='group_box';">
			<div class="group_box_content">
				<div class="group_control">
          <a href='modules.php?app=group_space&group_id=<?php echo $rs["group_id"];?>&user_id=<?php echo $url_uid;?>'><?php echo $g_langpackage->g_en_space;?></a>
          <?php $action=show_action($rs['add_userid'],$rs['member_count'],$rs['group_manager_id']);?>
          <a class="<?php echo $action['drop'];?> <?php echo $show_mine;?>" href="do.php?act=group_drop&group_id=<?php echo $rs['group_id'];?>" onclick="return confirm('<?php echo $g_langpackage->g_a_drop;?>');"><?php echo $g_langpackage->g_drop;?></a>
          <a class="<?php echo $action['manage'];?> <?php echo $show_mine;?>" href="modules.php?app=group_manager&group_id=<?php echo $rs['group_id'];?>"><?php echo $g_langpackage->g_manage;?></a>
          <a class="<?php echo $action['exit'];?> <?php echo $show_mine;?>" href='do.php?act=group_exit&group_id=<?php echo $rs["group_id"];?>' onclick="return confirm('<?php echo $g_langpackage->g_exit;?>'); "><?php echo $g_langpackage->g_exit;?></a>
				</div>
        <div class="group_photo"><a href='modules.php?app=group_space&group_id=<?php echo $rs["group_id"];?>&user_id=<?php echo $url_uid;?>'><img src="<?php echo $rs['group_logo'] ? $rs['group_logo'] : 'uploadfiles/group_logo/default_group_logo.jpg';?>" width='100px' height='100px' alt="<?php echo $rs['group_name'];?>" onerror="parent.pic_error(this)" /></a></div>
				<dl class="group_list">
					<dt><a href='modules.php?app=group_space&group_id=<?php echo $rs["group_id"];?>&user_id=<?php echo $url_uid;?>'><?php echo filt_word($rs['group_name']);?></a></dt>
					<dd><?php echo $g_langpackage->g_type;?>：<?php echo $rs['group_type'];?></dd>
					<dd><?php echo str_replace('{num}','<span>'.$rs['member_count'].'</span>',$g_join_num);?></dd>
					<dd class="<?php echo $show_his;?>"></dd>
					<dd class="<?php echo $show_mine;?>"><?php echo $g_langpackage->g_iam;?><span><?php echo get_group_role($rs['add_userid'],$rs['group_manager_id']);?></span><label>(<?php echo join_type($rs['group_join_type']);?>)</label></dd>
				</dl>
			</div>
		</div>
	<?php }?>
	<div class="guide_info <?php echo $list_hidden;?>">
		<?php echo $no_data;?>
	</div>
</body>
</html>
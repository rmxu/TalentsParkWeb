<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/group/group_member_manager.html
 * 如果您的模型要进行修改，请修改 models/modules/group/group_member_manager.php
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

	//引入公共模块
	require("foundation/module_group.php");
	require("foundation/module_users.php");
	require("foundation/fpages_bar.php");
	require("api/base_support.php");

	//变量区
	$role='';
	$group_creat=get_sess_cgroup();
	$user_id=get_sess_userid();
	$group_id=intval(get_argg('group_id'));

	//当前页面参数
	$page_num=trim(get_argg('page'));

	//权限判断
	$role=api_proxy("group_member_by_role",$group_id,$user_id);
	$role=$role[0];
	if(!isset($role)||$role>=2){
		echo "<script type='text/javascript'>alert(\"$g_langpackage->g_no_privilege\");window.history.go(-1);</script>";exit();
	}

	$member_rs=array();

	$req_member_rs=array();

	//取得未审核的会员
	$req_member_rs=api_proxy("group_member_by_gid","*",$group_id,0);

	//取得已经审核的会员
	$member_rs=api_proxy("group_member_by_gid","*",$group_id,1);

	//显示控制
	$req_data="";
	$isNull=1;
	if(empty($req_member_rs)){
		$isNull=0;
		$req_data="content_none";
	}
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
    <div class="create_button"><a href="modules.php?app=group"><?php echo $g_langpackage->g_return;?></a></div>
    <h2 class="app_group"><?php echo $g_langpackage->g_manage;?></h2>
    <div class="tabs">
        <ul class="menu">
            <li><a href="modules.php?app=group_manager&group_id=<?php echo $group_id;?>" title="<?php echo $g_langpackage->g_info;?>" class="nowbutl"><?php echo $g_langpackage->g_info;?></a></li>
            <li><a href="modules.php?app=group_info_manager&group_id=<?php echo $group_id;?>" title="<?php echo $g_langpackage->g_info_change;?>"><?php echo $g_langpackage->g_info_change;?></a></li>
            <li class="active"><a href="modules.php?app=group_member_manager&group_id=<?php echo $group_id;?>" title="<?php echo $g_langpackage->g_manage_member;?>"><?php echo $g_langpackage->g_manage_member;?></a></li>
            <li><a href="modules.php?app=group_space&group_id=<?php echo $group_id;?>" title="<?php echo $g_langpackage->g_en_space;?>"><?php echo $g_langpackage->g_en_space;?></a></li>
        </ul>
    </div>
<div class="clear mt20"></div>
<table class="msg_inbox" cellspacing="1" cellpadding="1">
	<thead>
		<tr>
			<td><?php echo $g_langpackage->g_m_name;?></td>
			<td><?php echo $g_langpackage->g_sex;?></td>
			<td><?php echo $g_langpackage->g_role;?></td>
			<td><?php echo $g_langpackage->g_ctrl;?></td>
		</tr>
    </thead>
	<?php foreach($member_rs as $rs){?>

			<?php $act_show=show_manage_act($group_creat,$rs['role'],$group_id);?>

    <tr>
    	<td><?php echo $rs['user_name'];?></td>
    	<td><?php echo get_user_sex($rs['user_sex']);?></td>
    	<td><?php echo get_g_role($rs['role']);?></td>
    	<td>
    		<a href="home.php?h=<?php echo $rs['user_id'];?>" target="_blank"><?php echo $g_langpackage->g_examine;?></a>
				<span class="<?php echo $act_show['b_del'];?>">|&nbsp<a href="do.php?act=group_del_member&userid=<?php echo $rs['user_id'];?>&group_id=<?php echo $group_id;?>" onclick='return confirm("<?php echo $g_langpackage->g_del_member;?>")'><?php echo $g_langpackage->g_del;?></a></span>
				<span class="<?php echo $act_show['b_app'];?>">|&nbsp<a href="do.php?act=group_appoint&userid=<?php echo $rs['user_id'];?>&group_id=<?php echo $group_id;?>"><?php echo $g_langpackage->g_set_manager;?></a> </span>
				<span class="<?php echo $act_show['b_rev'];?>">|&nbsp<a href="do.php?act=group_revoke&userid=<?php echo $rs['user_id'];?>&group_id=<?php echo $group_id;?>"><?php echo $g_langpackage->g_revoke_manager;?></a></span>
    	</td>
    </tr>

  <?php }?>

</table>
<div class="clear mt20"></div>
<div class="rs_head"><?php echo $g_langpackage->g_req_member;?></div>

<table class="msg_inbox <?php echo $req_data;?>" cellspacing="1" cellpadding="1">
	<?php foreach($req_member_rs as $rs){?>
	<tr>
		<td><?php echo $rs['user_name'];?></td>
		<td><?php echo get_user_sex($rs['user_sex']);?></td>
		<td><?php echo $g_langpackage->g_not_pass;?></td>
		<td><?php echo $rs['add_time'];?></td>
		<td>
			<a href="do.php?act=group_approve&userid=<?php echo $rs['user_id'];?>&group_id=<?php echo $group_id;?>"><?php echo $g_langpackage->g_check;?></a>&nbsp|&nbsp
			<a href="do.php?act=group_del_req&userid=<?php echo $rs['user_id'];?>&group_id=<?php echo $group_id;?>"><?php echo $g_langpackage->g_del;?></a>&nbsp|&nbsp
			<a href="home.php?h=<?php echo $rs['user_id'];?>" target="_blank"><?php echo $g_langpackage->g_examine;?></a>
		</td>
	</tr>
	<?php }?>
</table>
<?php echo page_show($isNull,$page_num,$page_total);?>
</body>
</html>
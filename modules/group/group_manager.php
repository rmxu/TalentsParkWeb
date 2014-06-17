<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/group/group_manager.html
 * 如果您的模型要进行修改，请修改 models/modules/group/group_manager.php
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
	require("api/base_support.php");
	
	$role='';
	$user_id=get_sess_userid();
	$group_id=intval(get_argg('group_id'));

	$text_join_type='';
	
	//权限判断
	$role=api_proxy("group_member_by_role",$group_id,$user_id);
	$role=$role[0];
	if(!isset($role)||$role>=2){
		echo "<script type='text/javascript'>alert(\"$g_langpackage->g_no_privilege\");window.history.go(-1);</script>";exit();
	}
	
	$group_row=api_proxy("group_self_by_gid","*",$group_id);
	
	//群组加入方式预订
  $text_join_type=join_type($group_row['group_join_type']);

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
            <li class="active"><a href="modules.php?app=group_manager&group_id=<?php echo $group_id;?>" title="<?php echo $g_langpackage->g_info;?>" class="nowbutl"><?php echo $g_langpackage->g_info;?></a></li>
            <li><a href="modules.php?app=group_info_manager&group_id=<?php echo $group_id;?>" title="<?php echo $g_langpackage->g_info_change;?>"><?php echo $g_langpackage->g_info_change;?></a></li>
            <li><a href="modules.php?app=group_member_manager&group_id=<?php echo $group_id;?>" title="<?php echo $g_langpackage->g_manage_member;?>"><?php echo $g_langpackage->g_manage_member;?></a></li>
            <li><a href="modules.php?app=group_space&group_id=<?php echo $group_id;?>" title="<?php echo $g_langpackage->g_en_space;?>"><?php echo $g_langpackage->g_en_space;?></a></li>
        </ul>
    </div>
	
<table class='form_table <?php echo $content_list;?>'>		
			<tr>
				<th width="16%"><?php echo $g_langpackage->g_name;?></th>
				<td width="*"><?php echo $group_row['group_name'];?></td>
			</tr>
			
			<tr>
				<th><?php echo $g_langpackage->g_type;?></th>
				<td><?php echo $group_row['group_type'];?></td>
			</tr>
			
			<tr>
				<th><?php echo $g_langpackage->g_m_num;?></th>
				<td><?php echo $group_row['member_count'];?></td>
			</tr>
			
			<tr>
				<th><?php echo $g_langpackage->g_creator;?></th>
				<td><?php echo $group_row['group_creat_name'];?></td>
			</tr>
			
			<tr>
				<th><?php echo $g_langpackage->g_time;?></th>
				<td><?php echo $group_row['group_time'];?></td>
			</tr>
			
			<tr>
				<th><?php echo $g_langpackage->g_manager;?></th>
				<td>
					
					<?php echo get_group_manager($group_row['group_manager_name']);?>
					
				</td>
			</tr>
			
			<tr>
				<th><?php echo $g_langpackage->g_join_type;?></th>
				<td><?php echo $text_join_type;?></td>
		 	</tr>
		 	
		  <tr>
		  	<th><?php echo $g_langpackage->g_tag;?></th>
		  	<td><?php echo $group_row['tag'];?></td>
		  </tr>
		  
		  <tr>
		  	<th><?php echo $g_langpackage->g_resume;?></th>
		  	<td><?php echo $group_row['group_resume'];?></td>
		  </tr>
		  
		  <tr>
		  	<th><?php echo $g_langpackage->g_gonggao;?></th>
		  	<td><?php echo $group_row['affiche'];?></td>
		  </tr>
</table>
</body>
</html>
<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/group/group_hot.html
 * 如果您的模型要进行修改，请修改 models/modules/group/group_hot.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入公共模块
	require("foundation/module_users.php");
	require("foundation/module_group.php");
	require("api/base_support.php");

	//引入语言包
	$g_langpackage=new grouplp;

	//变量区
	$user_id=get_sess_userid();
	$url_uid = intval(get_argg('user_id'));

	$group_hot_rs=array();
	$my_group=api_proxy("user_self_by_uid","join_group,creat_group",$user_id);
	$user_join_group=$my_group['join_group'];
	$user_creat_group=$my_group['creat_group'];

	//缓存功能区
	$group_hot_rs = api_proxy("group_self_by_memberCount","*");

	//数据显示控制
	$list_display="";
	$list_none="content_none";
	if(empty($group_hot_rs)){
		$list_display="content_none";
		$list_none="";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<SCRIPT language=JavaScript src="servtools/ajax_client/ajax.js"></SCRIPT>
<script language="JavaScript">
function join_group(group_id){
	var join_group=new Ajax();
	join_group.getInfo("do.php","GET","app","act=group_join&group_id="+group_id,function(c){parent.Dialog.alert(c);});
}
</script>

<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
</head>
<body id="iframecontent">
	<div class="create_button"><a href="modules.php?app=group_select" hidefocus="true"><?php echo $g_langpackage->g_search_group;?></a></div>
    <div class="create_button"><a href="modules.php?app=group_creat"><?php echo $g_langpackage->g_creat;?></a></div>
    <h2 class="app_group"><?php echo $g_langpackage->g_group;?></h2>
    <div class="tabs">
      <ul class="menu">
        <li><a href="modules.php?app=group" hidefocus="true"><?php echo $g_langpackage->g_mine;?></a></li>
        <li class="active"><a href="modules.php?app=group_hot" hidefocus="true"><?php echo $g_langpackage->g_hot;?></a></li>
      </ul>
    </div>

	<?php foreach($group_hot_rs as $rs){?>
		<div class="group_box" onmouseover="this.className = 'group_box_active';" onmouseout="this.className='group_box';">
			<div class="group_box_content">
                <div class="group_control">
                    <?php echo group_state($rs['group_id'],$rs['group_join_type'],$user_creat_group,$user_join_group,$rs['group_req_id']);?>
                    <a href='modules.php?app=group_space&group_id=<?php echo $rs["group_id"];?>&user_id=<?php echo $url_uid;?>'><?php echo $g_langpackage->g_en_space;?></a>
                </div>
				<div class="group_photo">
					<a href='modules.php?app=group_space&group_id=<?php echo $rs["group_id"];?>&user_id=<?php echo $url_uid;?>'><img src='<?php echo $rs['group_logo'];?>' width='100px' height='100px' alt="<?php echo $rs['group_name'];?>" onerror="parent.pic_error(this)" /></a>
				</div>
				<dl class="group_list">
					<dt><a href='modules.php?app=group_space&group_id=<?php echo $rs["group_id"];?>&user_id=<?php echo $url_uid;?>'><?php echo filt_word($rs['group_name']);?></a></dt>
					<dd><?php echo $g_langpackage->g_type;?>：<?php echo $rs['group_type'];?></dd>
					<dd><?php echo $g_langpackage->g_founder;?>：<a href='home.php?h=<?php echo $rs["add_userid"];?>' target="_blank"><?php echo filt_word($rs['group_creat_name']);?></a></dd>
					<dd><?php echo join_type($rs['group_join_type']);?><label>(<?php echo str_replace('{num}',$rs['member_count'],$g_langpackage->g_join_num);?>)</label></dd>
				</dl>
			</div>
		</div>
	<?php }?>

		<div class="guide_info <?php echo $list_none;?>">
			<?php echo $g_langpackage->g_no_group;?>
		</div>

</body>
</html>
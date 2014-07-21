<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/group/group_space.html
 * 如果您的模型要进行修改，请修改 models/modules/group/group_space.php
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
	require("foundation/module_group.php");
	require("foundation/fcontent_format.php");
	require("api/base_support.php");

	//引入语言包
	$g_langpackage=new grouplp;
	$mn_langpackage=new menulp;

	//变量区
	$role='';
	$url_uid= intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();
	$creat_group=get_sess_cgroup();
	$group_id=intval(get_argg('group_id'));

	//引入模块公共权限过程文件
	$is_self_mode='partLimit';
	$is_login_mode='';
	require("foundation/auser_validate.php");

	$button_url="modules.php?app=group&user_id=$userid";
	$button_title=$g_langpackage->g_return;

	$button_mine="content_none";
	$button_his="";

	if($is_self=='Y'){
		$button_mine="";
		$button_his="content_none";
		$button_url="modules.php?app=group_select";
		$button_title=$g_langpackage->g_search;
	}

	//当前页面参数
	$page_num=trim(get_argg('page'));

	$subject_rs=array();
	$group_members=array();
	$group_row=array();
	$role='';
	$join_js='';
	$subject_rs=api_proxy("group_sub_by_gid","*",$group_id);
	//取得权限
	if($ses_uid){
		$role=api_proxy("group_member_by_role",$group_id,$ses_uid);
		$role=$role[0];
	}
	//取得群组信息
	$group_row=api_proxy("group_self_by_gid","*",$group_id);

	//取得组员
	$group_members=api_proxy("group_member_by_gid","*",$group_id,1);
	$join_js="join_group_var(group_id)";
	if(!isset($ses_uid)){
		$join_js='parent.goLogin()';
	}

	//数据显示控制
	$show_data="";
	$show_error="content_none";
	$is_show=1;
	$isNull=0;
	$error_str="";
	if(empty($subject_rs)){
		$isNull=1;
	}
	$is_admin=get_sess_admin();
	if($is_admin==''){
		if($group_row['is_pass']==0){
			$is_show=0;
			$show_error="";
			$show_data="content_none";
			$error_str=$g_langpackage->g_lock_group;
			}
		}

	if(empty($group_row)){
		$show_data="content_none";
		$show_error="";
		$is_show=0;
		$error_str=$g_langpackage->g_data_none;
	}

	//操作控制
		$action_ctrl="content_none";
		if($ses_uid!=''){
			if(preg_match("/,$ses_uid,/",$group_row['group_manager_id'])||preg_match("/$ses_uid/",$group_row['add_userid'])){
				$action_ctrl="";
			}
		}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<!--[if lt IE 7]>
<style type="text/css">
.uico_photo_small span { behavior: url(skin/<?php echo $skinUrl;?>/css/ie_png_fix.htc);}
</style>
<![endif]-->
<script type='text/javascript' src="skin/default/js/jooyea.js"></script>
<script type="text/javascript" src="servtools/imgfix.js"></script>
<SCRIPT language=JavaScript src="servtools/ajax_client/ajax.js"></SCRIPT>
<script Language="JavaScript">
function check_form(){
	if(trim(document.getElementById("key_word").value)==""){
		parent.Dialog.alert("<?php echo $g_langpackage->g_not_null;?>");
		return false;
	}
}
function join_group(group_id){
	<?php echo $join_js;?>;
}
function join_group_var(group_id){
	var join_group=new Ajax();
	join_group.getInfo("do.php","GET","app","act=group_join&group_id="+group_id,function(c){parent.Dialog.alert(c);});
}
</script>
</head>

<body id="iframecontent">
	<?php if($ses_uid){?>
  <div class="create_button"><a href="<?php echo $button_url;?>" hidefocus="true"><?php echo $g_langpackage->g_search_group;?></a></div>
  <div class="create_button"><a href="javascript:;" hidefocus="true" onclick="join_group(<?php echo $group_row['group_id'];?>);"><?php echo $g_langpackage->g_click_join;?></a></div>
  <?php }?>
  <h2 class="app_group"><?php echo $g_langpackage->g_space;?></h2>
  <?php if($is_self=='Y'){?>
  <div class="tabs">
    <ul class="menu">
      <li class="active"><a href="modules.php?app=group" hidefocus="true"><?php echo $g_langpackage->g_mine;?></a></li>
      <li><a href="modules.php?app=group_hot" hidefocus="true"><?php echo $g_langpackage->g_hot;?></a></li>
    </ul>
  </div>
  <?php }?>
<?php if($is_show==1){?>
	<?php if($ses_uid!=$group_row['add_userid']&&$ses_uid){?>
		<div class="rs_head"><span class="right">
			<a href="javascript:void(0);" onclick="parent.show_share(1,<?php echo $group_row['group_id'];?>,'<?php echo $group_row['group_name'];?>','','');"><?php echo $mn_langpackage->mn_share;?></a>
			<a href="javascript:void(0);" onclick="parent.report(1,<?php echo $group_row['add_userid'];?>,<?php echo $group_row['group_id'];?>);"><?php echo $mn_langpackage->mn_report;?></a></span><?php echo filt_word($group_row['group_name']);?>
		</div>
  <?php }?>
<table class="group_intro <?php echo $show_data;?>">
	<tr>
      <th rowspan="2" style="width:140px; background:#fff;">
      	<img onerror="parent.pic_error(this)" src="<?php echo $group_row['group_logo']?$group_row['group_logo']:'uploadfiles/group_logo/default_group_logo.jpg';?>" width='120px' height='120px' class='user_ico' />
      </th>
      <th><strong><?php echo $g_langpackage->g_intro;?></strong></th>
      <th style="width:10px; background:#fff;"></th>
      <th><strong><?php echo $g_langpackage->g_gonggao;?></strong></th>
	</tr>
	<tr>
		<td height="92" valign="top"><?php echo filt_word($group_row['group_resume']);?></td>
        <td style="width:10px; background:#fff;"></td>
		<td height="92" valign="top"><?php echo filt_word($group_row['affiche']);?></td>
	</tr>
</table>
<div class="rs_head <?php echo $show_data;?>"><?php echo $g_langpackage->g_members;?></div>
	<?php foreach($group_members as $rs){?>
			<div class="group_user_list">
				<a class="avatar" href="home.php?h=<?php echo $rs['user_id'];?>" target="_blank">
					<img src=<?php echo $rs['user_ico'];?> onerror="parent.pic_error(this)" width="50px" height="50px" title="<?php echo $rs['user_name'];?>" alt="<?php echo $rs['user_name'];?>" />
				</a>
			  <div><a href="home.php?h=<?php echo $rs['user_id'];?>" target="_blank"><?php echo sub_str($rs['user_name'],5,true);?></a></div>
			</div>
	<?php }?>
	<div class="rs_head  <?php echo $show_data;?>">
		<div class="subject_search">
			<?php if(isset($role)&&$ses_uid!=''){?>
				<div class="right"><a href='modules.php?app=group_subject&group_id=<?php echo $group_id;?>&user_id=<?php echo $userid;?>'><?php echo $g_langpackage->g_send;?></a></div>
			<?php }?>
			<form class="right mr10" onsubmit='return check_form();' method="post" action='modules.php?app=search_subject&group_id=<?php echo $group_id;?>&user_id=<?php echo $userid;?>'>
				<input type='text' name='key_word' id='key_word' maxlength='10' size='20' style="height: 21px;border:1px #ccc solid" autocomplete='off' />
				<input type='submit' value='<?php echo $g_langpackage->g_search;?>' class="small-btn" />
			</form>
		</div>
		<?php echo $g_langpackage->g_bbs;?>
		<span><?php echo str_replace("{t_num}",$group_row['subjects_num'],$g_langpackage->g_topic_num);?></span>
	</div>

		<?php foreach($subject_rs as $rs){?>
		<div class="poll_list_box">
			<div class="poll_user"><a class="avatar" href="home.php?h=<?php echo $rs['user_id'];?>" target="_blank"><img src=<?php echo $rs['user_ico'];?> alt='<?php echo $rs['user_name'];?>' title='<?php echo $rs['user_name'];?>' /></a></div>
			<div class="subject_content">
				<dl>
					<dt><a href="modules.php?app=group_sub_show&subject_id=<?php echo $rs['subject_id'];?>&group_id=<?php echo $group_id;?>&user_id=<?php echo $userid;?>"><?php echo filt_word($rs['title']);?></a></dt>
					<dd><a href="home.php?h=<?php echo $rs['user_id'];?>" target="_blank"><?php echo filt_word($rs['user_name']);?></a> <span class='gray'><?php echo str_replace("{date}",$rs['add_time'],$g_langpackage->g_send_time);?></span></dd>
					<dd><span class="gray"><?php echo $g_langpackage->g_read;?>(<?php echo $rs['hits'];?>)</span> <span class="gray"><?php echo $g_langpackage->g_re;?>(<?php echo $rs['comments'];?>)</span></dd>
				</dl>
			</div>
			<div class="subject_status">
				<a href="modules.php?app=group_sub_show&subject_id=<?php echo $rs['subject_id'];?>&group_id=<?php echo $group_id;?>&user_id=<?php echo $userid;?>"><?php echo $g_langpackage->g_examine;?></a>
				<a href='do.php?act=group_del_sub&group_id=<?php echo $group_id;?>&subject_id=<?php echo $rs["subject_id"];?>&user_id=<?php echo $userid;?>&sendor_id=<?php echo $rs["user_id"];?>' class="<?php echo $action_ctrl;?>" onclick='return confirm("<?php echo $g_langpackage->g_del_subject;?>");'><?php echo $g_langpackage->g_del;?></a>
			</div>
		</div>
		<?php }?>
	<?php echo page_show($isNull,$page_num,$page_total);?>
<?php }?>

<!--错误控制-->
	<div class="guide_info <?php echo $show_error;?>">
		<?php echo $error_str;?>
	</div>

</body>
</html>
<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/album/album_list.html
 * 如果您的模型要进行修改，请修改 models/modules/album/album_list.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入语言包
	$a_langpackage=new albumlp;

	require("foundation/module_users.php");
	require("foundation/fpages_bar.php");
	require("foundation/module_album.php");
	require("api/base_support.php");
	require("servtools/menu_pop/trans_pri.php");
	//变量取得
	$album_id=intval(get_argg('album_id'));
	$url_uid=intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();
	$page_num=intval(get_argg('page'));

	//引入模块公共权限过程文件
	$is_self_mode='partLimit';
	$is_login_mode='';
	require("foundation/auser_validate.php");

	//数据表定义区
	$t_album = $tablePreStr."album";
	$t_photo = $tablePreStr."photo";
	$t_users = $tablePreStr."users";

	$album_rs=array();
	$album_rs=api_proxy("album_self_by_uid","*",$userid);

	if($is_self=='Y'){
		$a_who=$a_langpackage->a_mine;
		$guide_txt=$a_langpackage->a_no_alb.",<a href='modules.php?app=album_edit'>".$a_langpackage->a_crt_alb."</a>,<a href='modules.php?app=album_friend'>".$a_langpackage->a_fri_alb."</a>";
		$button="";
	}else{
		$holder_name=get_hodler_name($url_uid);
		$a_who=str_replace('{holder}',filt_word($holder_name),$a_langpackage->a_holder);
		$guide_txt=$a_langpackage->a_no_fri;
		$button="content_none";
	}

	$isNull=0;//不为空则设置为零
	$a_main="";
	$guide="content_none";
	if(empty($album_rs)){
		$isNull=1;//判断结果集是否为空
		$a_main="content_none";
		$guide="";
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
<script type='text/javascript' src="skin/default/js/jooyea.js"></script>
<script type='text/javascript' src="servtools/menu_pop/menu_pop.js"></script>
</head>
<body id="iframecontent" oncontextmenu='return false'>
<?php if($is_self=='Y'){?>
<div class="create_button"><a href="modules.php?app=album_edit"><?php echo $a_langpackage->a_creat;?></a></div>
<div class="create_button"><a href="modules.php?app=photo_upload"><?php echo $a_langpackage->a_upload;?></a></div>
<?php }?>
<h2 class="app_album"><?php echo $a_who;?></h2>
<?php if($is_self=='Y'){?>
<div class="tabs">
	<ul class="menu">
    <li class="active"><a href="modules.php?app=album" hidefocus="true"><?php echo $a_langpackage->a_mine;?></a></li>
    <li><a href="modules.php?app=album_friend" hidefocus="true"><?php echo $a_langpackage->a_friend;?></a></li>
  </ul>
</div>
<?php }?>
<div class="album_holder">
	<?php foreach($album_rs as $val){?>
	<?php $is_pri=check_pri($val['user_id'],$val['privacy']);?>
	  <dl class="list_album" onmouseover="this.className += ' list_album_active';" onmouseout="this.className='list_album';" <?php if($is_self=='Y'){?>title="<?php echo $a_langpackage->a_tip_pri;?>" onmouseDown="menu_pop_show(event,this);" id='<?php echo $t_album;?>:<?php echo $val['album_id'];?>:<?php echo $val["privacy"];?>'<?php }?>>
		  <dt><a href=<?php echo $is_pri ? "modules.php?app=photo_list&album_id=".$val['album_id']."&user_id=".$val['user_id'] : "javascript:void(0)";?>><img onerror="parent.pic_error(this)" src=<?php echo $is_pri ? $val['album_skin'] : "skin/$skinUrl/images/errorpage.gif";?>></a></dt>
		  <dd><strong><a href="<?php echo $is_pri ? "modules.php?app=photo_list&album_id=".$val['album_id']."&user_id=".$val['user_id'] : "javascript:void(0)";?>"><?php echo filt_word($val['album_name']);?></a></strong></dd>
		  <dd><?php echo $a_langpackage->a_label;?>：<?php echo $val['tag'];?></dd>
		  <dd><label><?php echo str_replace('{holder}',$val['photo_num'],$a_langpackage->a_num);?></label></dd>
		  <dd><?php echo $a_langpackage->a_update_in;?><?php echo $val['update_time'];?></dd>
          <dd><?php echo $a_langpackage->a_crt_time;?><?php echo $val['add_time'];?></dd>
		  <?php if($is_self=='Y'){?>
		  <dd class="album_conf">
				<a class="album_edit" href='modules.php?app=album_edit&album_id=<?php echo $val['album_id'];?>'><?php echo $a_langpackage->a_edit;?></a>
				<a class="album_del" href='do.php?act=album_del&album_id=<?php echo $val['album_id'];?>' onclick="return confirm('<?php echo $a_langpackage->a_del_asc;?>')";><?php echo $a_langpackage->a_del;?></a>
			<?php }?>
	  </dl>
	<?php }?>
    <div class="clear"></div>
	<?php page_show($isNull,$page_num,$page_total);?>
</div>

<div class="guide_info <?php echo $guide;?>">
	<?php echo $guide_txt;?>
</div>

</body>
</html>
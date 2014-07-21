<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/album/photo_list.html
 * 如果您的模型要进行修改，请修改 models/modules/album/photo_list.php
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
	$mn_langpackage=new menulp;

	//变量取得
	$album_id = intval(get_argg('album_id'));
	$url_uid= intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();
	$page_num=intval(get_argg('page'));

	//引入模块公共权限过程文件
	$is_login_mode='';
	$is_self_mode='partLimit';
	require("foundation/auser_validate.php");
	require("foundation/fcontent_format.php");
	require("foundation/fpages_bar.php");
	require("api/base_support.php");
	require("servtools/menu_pop/trans_pri.php");
	require("foundation/module_users.php");

	//数据表定义区
	$t_album = $tablePreStr."album";
	$t_photo = $tablePreStr."photo";
	$t_album_comment = $tablePreStr."album_comment";

	$album_row=array();

	$album_row=api_proxy("album_self_by_aid","*",$album_id);
	$photo_rs=api_proxy("album_photo_by_aid","*",$album_id);

	$host_id=$album_row['user_id'];
	$album_information = $album_row['album_info'];

	if($ses_uid==$host_id){
		$no_pht = $a_langpackage->a_no_upl.",<a href='modules.php?app=photo_upload&album_id=".$album_id."'>".$a_langpackage->a_upl_pht."</a>";
	}else{
		$no_pht = $a_langpackage->a_f_no_pht;
	}

	if($is_self=='Y'){
		$a_who=$a_langpackage->a_mine;
		$user_id=$ses_uid;
		$button="";
	}else{
		$holder_name=get_hodler_name($url_uid);
		$a_who=str_replace('{holder}',filt_word($holder_name),$a_langpackage->a_holder)."</div>";
		$user_id=$album_row['user_id'];
		$button="content_none";
	}

	//数据显示控制
	$isNull=0;
	$show_data=1;
	$none_photo="content_none";

	if(empty($photo_rs)){
		$isNull=1;//判断结果集是否为空
		$show_data=0;
		$none_photo="";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<body id="iframecontent" oncontextmenu="return false;">
<?php if($is_self=='Y'){?>
<div class="create_button"><a href="modules.php?app=album_edit"><?php echo $a_langpackage->a_creat;?></a></div>
<div class="create_button"><a href="modules.php?app=photo_upload"><?php echo $a_langpackage->a_upload;?></a></div>
<?php }?>
<h2 class="app_album"><?php echo $a_who;?></h2>
<?php if($is_self=='Y'){?>
<div class="tabs <?php echo $button;?>">
	<ul class="menu">
        <li class="active"><a href="modules.php?app=album" hidefocus="true"><?php echo $a_langpackage->a_mine;?></a></li>
        <li><a href="modules.php?app=album_friend" hidefocus="true"><?php echo $a_langpackage->a_friend;?></a></li>
    </ul>
</div>
<?php }?>
<?php if($show_data){?>
<?php if($is_self=='N'&&$ses_uid){?>
<div class="rs_head">
	<span class="right">
		<a href="javascript:void(0);" onclick="parent.show_share(2,<?php echo $album_row['album_id'];?>,'<?php echo $album_row['album_name'];?>','','');"><?php echo $mn_langpackage->mn_share;?></a>&nbsp;
		<a href="javascript:void(0);" onclick="parent.report(2,<?php echo $album_row['user_id'];?>,<?php echo $album_row['album_id'];?>);"><?php echo $mn_langpackage->mn_report;?></a>
	</span>
	<?php echo filt_word($album_row['album_name']);?>
</div>
<?php }?>
<?php foreach($photo_rs as $val){?>
<?php newline('4');$is_pri=check_pri($val['user_id'],$val['privacy']);?>
<div class="album_photo_box">
  <a href='<?php echo $is_pri ? rewrite_fun("modules.php?app=photo&photo_id=".$val['photo_id']."&album_id=".$album_id."&user_id=".$user_id) : "javascript:void(0)";?>'>
  	<img <?php if($is_self=='Y'){?>title="<?php echo $a_langpackage->a_tip_pri;?>" onmouseDown="menu_pop_show(event,this);" id='<?php echo $t_photo;?>:<?php echo $val['photo_id'];?>:<?php echo $val['privacy'];?>' <?php }?> src=<?php echo $is_pri ? $val['photo_thumb_src'] : "skin/$skinUrl/images/error.gif";?> width="100px"  class="user_ico" />
  </a>
</div>
<?php }?>
<div class="clear"></div>
<?php page_show($isNull,$page_num,$page_total);?>
<div class="album_info">
	<div class="album_info_content">
		<div class="album_title"><?php echo $a_langpackage->a_name;?><?php echo filt_word($album_row['album_name']);?></div>
		<div class="album_summary"><?php echo $a_langpackage->a_inf;?><?php echo filt_word($album_information);?></div>
	</div>
</div>
<div class="tleft ml20">
	<div class="comment">
    <div id='show_2_<?php echo $album_row["album_id"];?>'>
    	<script type='text/javascript'>parent.get_mod_com(2,<?php echo $album_row['album_id'];?>,0,20);</script>
    </div>
    <?php if($ses_uid!=''){?>
		<div class="reply">
			<img class="figure" src="<?php echo get_sess_userico();?>" />
			<p><textarea type="text" maxlength="150" onkeyup="return isMaxLen(this)" id="reply_2_<?php echo $album_row['album_id'];?>_input"></textarea></p>
			<div class="replybt">
				<input class="left button" onclick="parent.restore_com(<?php echo $album_row['user_id'];?>,2,<?php echo $album_row['album_id'];?>);show('face_list_menu',200);" type="submit" name="button" id="button" value="<?php echo $a_langpackage->a_b_com;?>" />
				<a id="reply_a_<?php echo $blog_row['log_id'];?>_input" class="right" href="javascript: void(0);" onclick="showFace(this,'face_list_menu','reply_2_<?php echo $album_row['album_id'];?>_input');"><?php echo $a_langpackage->a_face;?></a>
			</div>
			<div class="clear"></div>
		</div>
		<?php }?>
	</div>
</div>
<?php }?>
	<div id="face_list_menu" class="emBg" style="display:none;z-index:100;"></div>
	<div class="guide_info <?php echo $none_photo;?>"><?php echo $no_pht;?></div>
</body>
</html>
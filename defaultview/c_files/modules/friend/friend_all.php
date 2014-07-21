<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/friend/friend_all.html
 * 如果您的模型要进行修改，请修改 models/modules/friend/friend_all.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入语言包
	$f_langpackage=new friendlp;
	
	//引入公共模块
	require("foundation/module_users.php");
	require("foundation/fcontent_format.php");
	require("foundation/fpages_bar.php");
	require("api/base_support.php");
	
	//变量取得
	$url_uid= intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();
	
	//当前页面参数
	$page_num=intval(get_argg('page'));

	//引入模块公共权限过程文件
	$is_login_mode='';
	$is_self_mode='partLimit';
	require("foundation/auser_validate.php");

	//数据表定义区
	$t_users=$tablePreStr."users";

	$mypals_rs = api_proxy("pals_self_by_uid","*",$userid);
	
	if($is_self=='Y'){
		$friend_title=$f_langpackage->f_friend;
	}else{
		$holder_name=get_hodler_name($url_uid);
		$friend_title=str_replace("{holder}",$holder_name,$f_langpackage->f_visitor);
	}
	
	$text_no_friend="content_none";
	$text_friend="";
	$isNull=0;
	if(empty($mypals_rs)){
		$isNull=1;
		$text_no_friend="";
		$text_friend="content_none";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<!--[if lt IE 7]>
<style type="text/css">
.uico_photo_small span { behavior: url(skin/<?php echo $skinUrl;?>/css/ie_png_fix.htc);}
</style>
<![endif]-->
</head>

<body id="iframecontent">
    <h2 class="app_friend"><?php echo $friend_title;?></h2>

<div class="photo_view_box <?php echo $text_friend;?>">
	<div class="friend_list <?php echo $show_data;?>">
	<ul class="user_list">
	<?php foreach($mypals_rs as $val){?>
		<!--<div class="friend_infobox" onmouseover="this.className = 'friend_infobox_active';"  onmouseout="this.className = 'friend_infobox';">
				<div class="friend_infobox_photo">
				<a href="home.php?h=<?php echo $val['pals_id'];?>" target="_blank" title="<?php echo $f_langpackage->f_fri;?>"><img src="<?php echo $val['pals_ico'];?>" width="43px" height="43px" /></a>
				</div>
				<div class="friend_infobox_info">
					<dl>
						<dt><a href="home.php?h=<?php echo $val["pals_id"];?>" target="_blank" title="<?php echo $f_langpackage->f_fri;?>"> <?php echo sub_str(filt_word($val['pals_name']),6,true);?></a></dt>
						<dd><?php echo $val['pals_sort_name'];?></dd>
					</dl>
				</div>
			</div>-->
			<li>
				<div class="photo">
				<a class="avatar" href="home.php?h=<?php echo $val['pals_id'];?>" target="_blank" title="<?php echo $f_langpackage->f_fri;?>"><img src="<?php echo $val['pals_ico'];?>" /></a>
				</div>
					<dl>
						<dt><a href="home.php?h=<?php echo $val["pals_id"];?>" target="_blank" title="<?php echo $f_langpackage->f_fri;?>"> <?php echo sub_str(filt_word($val['pals_name']),6,true);?></a></dt>
						<dd><?php echo $val['pals_sort_name'];?></dd>
					</dl>
			</li>
	<?php }?>		
	</ul>
	</div>
</div>		
<div style='clear:both'><?php echo page_show($isNull,$page_num,$page_total);?></div>
<div class="guide_info <?php echo $text_no_friend;?>"><?php echo $f_langpackage->f_no;?></div>

</body>
</html>

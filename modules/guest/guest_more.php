<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/guest/guest_more.html
 * 如果您的模型要进行修改，请修改 models/modules/guest/guest_more.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入语言包
	$gu_langpackage=new guestlp;

	//引入公共模块
	require("foundation/module_users.php");
	require("foundation/module_mypals.php");
	require("foundation/fcontent_format.php");
	require("api/base_support.php");

	//变量取得
	$url_uid= intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();

	//引入模块公共权限过程文件
	$is_login_mode='';
	$is_self_mode='partLimit';
	require("foundation/auser_validate.php");

	$guest_rs = api_proxy("guest_self_by_uid","*",$userid,20);

	if($is_self=='Y'){
		$guest_title=$gu_langpackage->gu_title;
	}else{
		$holder_name=get_hodler_name($url_uid);
		$guest_title=str_replace("{holder}",filt_word($holder_name),$gu_langpackage->gu_who);
	}
	$none_data="content_none";
	$show_data="";
	if(empty($guest_rs)){
		$none_data="";
		$show_data="content_none";
	}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
</head>

<body id="iframecontent">
    <h2 class="app_friend"><?php echo $guest_title;?></h2>
	<div class="friend_list <?php echo $show_data;?>">
     <ul class="user_list">
<?php foreach($guest_rs as $val){?>
		 <li>
				<div class="photo">
				<a class="avatar" href="home.php?h=<?php echo $val['guest_user_id'];?>" target="_blank" title="<?php echo $gu_langpackage->gu_see;?>"><img src="<?php echo $val['guest_user_ico'];?>" /></a>
				</div>
					<dl>
						<dt><a href="home.php?h=<?php echo $val["guest_user_id"];?>" target="_blank" title="<?php echo $gu_langpackage->gu_see;?>"> <?php echo sub_str(filt_word($val['guest_user_name']),6,true);?></a></dt>
						<dd><?php echo format_datetime_short($val['add_time']);?></dd>
					</dl>
			</li>
	<?php }?>		
	</div>		
<div class="guide_info <?php echo $none_data;?>"><?php echo $gu_langpackage->gu_none_data;?></div>
</body>
</html>
<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/friend/friend.html
 * 如果您的模型要进行修改，请修改 models/modules/friend/friend.php
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
	require("foundation/fcontent_format.php");
	require("api/base_support.php");

	//变量取得
	$url_uid= intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();
	
	//加为好友 打招呼
	$show_add_friend="";
	$add_friend="mypalsAddInit";
	$send_hi="hi_action";
	if(!$ses_uid){
		$add_friend='goLogin';
		$send_hi='goLogin';
	}

	//引入模块公共权限过程文件
	$is_login_mode='';
	$is_self_mode='partLimit';
	require("foundation/auser_validate.php");
	if($is_self=='Y'){
		$show_add_friend="content_none";
	}
	$mypals_rs = api_proxy("pals_self_by_uid","*",$userid,5);
?><?php foreach($mypals_rs as $val){?>
	<li>
		<a class="avatar" href="home.php?h=<?php echo $val["pals_id"];?>" target="_blank" title="<?php echo $f_langpackage->f_fri;?>">
			<img src="<?php echo $val['pals_ico'];?>" />
		</a>
		<span class="name"><a href="home.php?h=<?php echo $val["pals_id"];?>" target="_blank" title="<?php echo $f_langpackage->f_fri;?>"><?php echo sub_str(filt_word($val['pals_name']),6,true);?></a></span>
		<span>
			<img style="cursor:pointer;" onclick="<?php echo $send_hi;?>(<?php echo $val["pals_id"];?>)" src="skin/<?php echo $skinUrl;?>/images/hi.gif" title="<?php echo $f_langpackage->f_greet;?>" />&nbsp;&nbsp;
			<img class="<?php echo $show_add_friend;?>" style="cursor:pointer;" onclick="<?php echo $add_friend;?>(<?php echo $val["pals_id"];?>)" src="skin/<?php echo $skinUrl;?>/images/add.gif"  title="<?php echo $f_langpackage->f_add_friend;?>" />
		</span>
	</li>
<?php }?>
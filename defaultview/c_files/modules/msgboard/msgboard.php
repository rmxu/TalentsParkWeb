<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/msgboard/msgboard.html
 * 如果您的模型要进行修改，请修改 models/modules/msgboard/msgboard.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入语言包
	$mb_langpackage=new msgboardlp;

	//变量取得
	$url_uid= intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();

	//引入模块公共权限过程文件
	$is_self_mode='partLimit';
	$is_login_mode='';
	require("foundation/auser_validate.php");
	require("foundation/fcontent_format.php");
	require("api/base_support.php");
	$mes_rs=array();
	$mes_rs=api_proxy("msgboard_self_by_uid","*",$userid,'',5);
?><?php if($mes_rs){?>
<?php foreach($mes_rs as $val){?>
<dl class="msg_list">
	<div class="avatar"><a target="_blank" href='home.php?h=<?php echo $val['from_user_id'];?>'><img src="<?php echo $val['from_user_ico'];?>" /></a></div>
	<dt><span><a target="_blank" href='home.php?h=<?php echo $val['from_user_id'];?>'><?php echo filt_word($val['from_user_name']);?></a></span><span>[<?php echo $val['add_time'];?>]</span></dt>
	<dd class="msg_list_content"><?php echo filt_word(get_face($val['message']));?></dd>
	<dd>
		<span><a href="javascript:;"  onclick=parent.leave_message("<?php echo $val['from_user_name'];?>","<?php echo $val['from_user_id'];?>");><?php echo $mb_langpackage->mb_res;?></a></span>
		<?php if($is_self=='Y'){?>
		<span>|</span>
		<span><a href="do.php?act=msgboard_del&user_id=<?php echo $userid;?>&mess_id=<?php echo $val['mess_id'];?>" onclick='return confirm("<?php echo $mb_langpackage->mb_delete_remaining;?>？");'><?php echo $mb_langpackage->mb_del;?></a></span>
		<?php }?>
	</dd>
</dl>
<?php }?>
<div class="more"><a href="modules.php?app=msgboard_more&user_id=<?php echo $userid;?>" class="highlight" title="<?php echo $mb_langpackage->mb_all_my_message;?>" href=""><?php echo $mb_langpackage->mb_full_message;?> »</a></div>
<?php }?>
<?php if(empty($mes_rs)){?>
<div class="guide_info"><?php echo $mb_langpackage->mb_message_content_empty;?></div>
<?php }?>
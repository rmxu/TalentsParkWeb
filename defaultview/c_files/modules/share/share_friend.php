<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/share/share_friend.html
 * 如果您的模型要进行修改，请修改 models/modules/share/share_friend.php
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
	require("foundation/module_users.php");
	
	require("foundation/module_share.php");
	
	//语言包引入
	$s_langpackage=new sharelp;
	$rf_langpackage=new recaffairlp;
	$mn_langpackage=new menulp;
	
	//变量区
	$ses_uid=get_sess_userid();
	
	//当前页面参数
	$page_num=trim(get_argg('page'));
	
	//数据表定义
	$t_share=$tablePreStr."share";
	$t_users=$tablePreStr."users";
	dbtarget('r',$dbServs);
	$dbo=new dbex;
	
	$share_rs=array();
	$pals_id=get_sess_mypals();
	$id_cols="user_id in ($pals_id.0) and is_pass=1 ";
	$order_by="order by add_time desc";
	$type='getRs';
	$dbo->setPages(20,$page_num);//设置分页	
	$share_rs=get_db_data($dbo,$t_share,$id_cols,$order_by,$type);
	$page_total=$dbo->totalPage;//分页总数
	
	//控制数据显示
	$content_data_none="content_none";
	$content_data_set="";
	$isNull=0;
	if(empty($share_rs)){
	$isNull=1;
	$content_data_none="";
	$content_data_set="content_none";
	}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<SCRIPT type='text/javascript' src="servtools/ajax_client/ajax.js"></SCRIPT>
<SCRIPT language=JavaScript src="skin/default/js/jooyea.js"></SCRIPT>
<script type='text/javascript'>
	function fixImage(i,w,h){
		return true;
	}

	function share_com(share_id,mod_id,type_id,start_num,end_num,ref_type){
		var obj_share=$('content_share_'+share_id);
		if(obj_share.style.display=='none'){
			obj_share.style.display='';
			if($("show_"+type_id+"_"+mod_id).innerHTML==''){
				parent.get_mod_com(type_id,mod_id,start_num,end_num);
			}
		}else{
			obj_share.style.display='none';
		}
	}
</script>

</head>
<body id="iframecontent">
<h2 class="app_share"><?php echo $s_langpackage->s_share;?></h2>
<div class="tabs">
	<ul class="menu">
    <li><a href="modules.php?app=share_list" hidefocus="true"><?php echo $s_langpackage->s_mine;?></a></li>
    <li class="active"><a href="modules.php?app=share_friend" hidefocus="true"><?php echo $s_langpackage->s_friend;?></a></li>
  </ul>
</div>
<?php foreach($share_rs as $rs){?>
<dl class="share_list">
  <div class="avatar" style="float:left;">
  	<a href="home.php?h=<?php echo $rs["user_id"];?>" target="_blank"><img src="<?php echo $rs["user_ico"];?>" /></a>
  </div>
  <dt>
  	<strong><a href="home.php?h=<?php echo $rs["user_id"];?>" target="_blank"><?php echo filt_word($rs['user_name']);?></a></strong><?php echo filt_word($rs['s_title']);?>
  	<br /><span><?php echo $rs['add_time'];?></span>
  </dt>
  <dd class="share_list_content"><?php echo filt_word($rs['content']);?></dd>
  <dd>
  	<span><?php echo $s_langpackage->s_label;?>：<?php echo $rs['tag'];?></span><span>|</span>
  	<span><a href='javascript:share_com(<?php echo $rs["s_id"];?>,<?php echo $rs["s_id"];?>,5,0,10,0);'><?php echo $s_langpackage->s_com;?>(<label id='num_5_<?php echo $rs['s_id'];?>'><?php echo $rs['comments'];?></label>)</a></span><span>|</span>
  	<?php if($rs['user_id']!=$ses_uid&&$ses_uid){?>
  	<span><a href="javascript:void(0);" onclick='parent.report(8,<?php echo $rs["user_id"];?>,<?php echo $rs["s_id"];?>);'>举报</a></span>
  	<?php }?>
  </dd>
</dl>
<div class="tleft" style="display:none;" id='content_share_<?php echo $rs["s_id"];?>'>
	<div class="comment">
		<div id='show_5_<?php echo $rs["s_id"];?>'></div>
		<?php if($ses_uid!=''){?>
		<div class="reply">
				<img class="figure" src='<?php echo get_sess_userico();?>' />
				<p><textarea type="text" maxlength="150" onkeyup="return isMaxLen(this)"  id="reply_5_<?php echo $rs['s_id'];?>_input"></textarea></p>
				<div class="replybt">
					<input class="left button" onclick="parent.restore_com(<?php echo $rs['user_id'];?>,5,<?php echo $rs['s_id'];?>);show('face_list_menu',200)" type="button" name="button" id="button" value="<?php echo $s_langpackage->s_reply;?>" />
					<a id="reply_a_<?php echo $rs['s_id'];?>_input" class="right" href="javascript:void(0);" onclick=" showFace(this,'face_list_menu','reply_5_<?php echo $rs['s_id'];?>_input');"><?php echo $s_langpackage->s_face;?></a>
				</div>
				<div class="clear"></div>
		</div>
		<?php }?>
	</div>
</div>
<?php }?>

<div id="face_list_menu" class="emBg" style="display:none;z-index:100;"></div>
<?php echo page_show($isNull,$page_num,$page_total);?>
<div class="guide_info <?php echo $content_data_none;?>"><?php echo $s_langpackage->s_his_none;?></div>
<?php if(isset($_GET['remind'])){?>
	<script type='text/javascript'>
		share_com(<?php echo $_GET['mod'];?>,<?php echo $_GET['mod'];?>,5,0,10);
	</script>
<?php }?>

</body>
</html>
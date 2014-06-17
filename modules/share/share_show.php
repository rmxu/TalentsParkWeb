<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/share/share_show.html
 * 如果您的模型要进行修改，请修改 models/modules/share/share_show.php
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
	require("foundation/module_share.php");
	require("api/base_support.php");

	//语言包引入
	$s_langpackage=new sharelp;
	$rf_langpackage=new recaffairlp;
	$g_langpackage=new grouplp;
	$mn_langpackage=new menulp;

	//变量区
	$url_uid=intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();
	$share_id=intval(get_argg('s_id'));
	$is_admin=get_sess_admin();

	//引入模块公共权限过程文件
	$is_login_mode='';
	$is_self_mode='partLimit';
	require("foundation/auser_validate.php");

	//当前页面参数
	$page_num=intval(get_argg('page'));

	//数据表定义
	$t_share=$tablePreStr."share";
	$t_share_com=$tablePreStr."share_comment";
	$t_users=$tablePreStr."users";

	dbtarget('r',$dbServs);
	$dbo=new dbex;
	$error_str='';
	$share_row='';
	$id_cols="s_id = $share_id";
	$order_by='';
	$type='getRow';
	if($share_id){
		$share_row=get_db_data($dbo,$t_share,$id_cols,$order_by,$type);
		if($share_row['type_id']==6){
			$link_re=$share_row['out_link'];
		}else{
			$link_re=$share_row['movie_link'];
		}
		//操作显示控制
		$action_ctrl='content_none';
		if($share_row['user_id']==$ses_uid){
			$action_ctrl='';
		}
		$share_com_rs=array();
		$type="getRs";
		$dbo->setPages(20,$page_num);//设置分页
		$comment_rs=get_db_data($dbo,$t_share_com,$id_cols,$order_by,$type);
		$page_total=$dbo->totalPage;//分页总数
		
		$isNull=0;
		$show_com='';
		$show_error='content_none';
		$show_out_link='';
	
		//锁定控制
		if($share_row['is_pass']==0 && $is_admin==''){
			$show_error='';
			$error_str=$s_langpackage->s_lock;
		}		
	}

	//控制数据显示
	if(empty($comment_rs)){
		$isNull=1;
		$show_com='content_none';
	}

	if(empty($share_row)){
		$show_error='';
		$error_str=$s_langpackage->s_none;
		$show_com='content_none';
		$show_out_link='content_none';
		$isNull=1;
	}

	$holder_name=get_hodler_name($url_uid);
	?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<script type='text/javascript' src="skin/default/js/jooyea.js"></script>
<script type='text/javascript'>
	function CheckForm(){
		if(parent.trim(document.getElementById('share_com').value)==""){
			parent.Dialog.alert("<?php echo $g_langpackage->g_not_null;?>");
			return (false);
		}
			return (true);
	}
</script>
</head>
<body id="iframecontent">
<h2 class="app_share"><?php echo $s_langpackage->s_add_out;?></h2>
<?php if($is_self=='Y'){?>
<div class="tabs">
	<ul class="menu">
    <li class="active"><a href="modules.php?app=share_list" hidefocus="true"><?php echo $s_langpackage->s_mine;?></a></li>
    <li><a href="modules.php?app=share_friend" hidefocus="true"><?php echo $s_langpackage->s_friend;?></a></li>
  </ul>
</div>
<?php }?>
<?php if($share_row && ($share_row['is_pass']==1 || $is_admin!='')){?>
<dl class="share_list">
	<dt><strong><a href="home.php?h=<?php echo $share_row["user_id"];?>" target="_blank"><?php echo $holder_name;?></a></strong>
    <?php echo $share_row['s_title'];?>
    <br /><span><?php echo $share_row['add_time'];?></span>
  	</dt>
    
    <?php if($share_row['type_id']==7){?>
    <dd class="share_list_content" style="padding:25px 0 5px; text-align:center"><embed src="<?php echo $link_re;?>"  menu="menu" wmode="transparent" quality="1" type="application/x-shockwave-flash" width="450px" height="350px"></embed></dd>
    <?php }?>

    <?php if($share_row['type_id']==6){?>
    <dd class="share_list_content">
    <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="350" height="50" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"><param name="quality" value="high" /><param name="wmode" value="transparent" /><param name="src" value="skin/<?php echo $skinUrl;?>/images/player.swf?soundFile=<?php echo $link_re;?>&amp;
    bg=0xecfbff&amp;
    leftbg=0x2cb8e3&amp;
    lefticon=0xF2F2F2&amp;
    rightbg=0x2cb8e3&amp;
    rightbghover=0x0296c3&amp;
    righticon=0xF2F2F2&amp;
    righticonhover=0xFFFFFF&amp;
    text=0x0296c3&amp;
    slider=0x2cb8e3&amp;
    track=0xFFFFFF&amp;
    border=0xFFFFFF&amp;
    loader=0x2cb8e3&amp;
    autostart=no&amp;
    loop=no"/>
    <embed type="application/x-shockwave-flash" width="350" height="50" src="skin/<?php echo $skinUrl;?>/images/player.swf?soundFile=<?php echo $link_re;?>&amp;
    bg=0xecfbff&amp;
    leftbg=0x2cb8e3&amp;
    lefticon=0xF2F2F2&amp;
    rightbg=0x2cb8e3&amp;
    rightbghover=0x0296c3&amp;
    righticon=0xF2F2F2&amp;
    righticonhover=0xFFFFFF&amp;
    text=0x0296c3&amp;
    slider=0x2cb8e3&amp;
    track=0xFFFFFF&amp;
    border=0xFFFFFF&amp;
    loader=0x2cb8e3&amp;
    autostart=no&amp;
    loop=no" wmode="transparent" quality="high"></embed></object>
    </dd>
    <?php }?>
    <dd class="ml20"><span><?php echo $s_langpackage->s_label;?>：<?php echo $share_row['tag'];?></span><span>|</span><span><?php echo $s_langpackage->s_com;?>（<label id="num_5_<?php echo $share_row['s_id'];?>"><?php echo $share_row['comments'];?></label>）</span>
      <?php if($share_row['user_id']!=$ses_uid&&$ses_uid){?>
			<span><a href='javascript:void(0)' onclick="parent.show_share(<?php echo $share_row['type_id'];?>,<?php echo $share_row['s_id'];?>,'','<?php echo $share_row['out_link'];?>');"> <?php echo $mn_langpackage->mn_share;?></a></span>
      <span>|</span>
      <span><a href="javascript:void(0);" onclick='parent.report(8,<?php echo $share_row["user_id"];?>,<?php echo $share_row["s_id"];?>);'><?php echo $mn_langpackage->mn_report;?></a></span>
      <?php }?>
    </dd>
</dl>

<div class="tleft ml20" id='content_share_<?php echo $share_row["s_id"];?>'>
<div class="comment mt8">
    <div id='show_5_<?php echo $share_row["s_id"];?>'>
        <script type='text/javascript'>parent.get_mod_com(5,<?php echo $share_row['s_id'];?>,0,20);</script>
    </div>
  <?php if($ses_uid!=''){?>  
	<div class="reply">
			<img class="figure" src='<?php echo get_sess_userico();?>' />
			<p><textarea type="text" maxlength="150" onkeyup="return isMaxLen(this)"  id="reply_5_<?php echo $share_row['s_id'];?>_input"></textarea></p>
			<div class="replybt">
				<input class="left button" onclick="parent.restore_com(<?php echo $share_row['user_id'];?>,5,<?php echo $share_row['s_id'];?>);show('face_list_menu',200)" type="button" name="button" id="button" value="<?php echo $s_langpackage->s_reply;?>" />
				<a id="reply_a_<?php echo $share_row['s_id'];?>_input" class="right" href="javascript:void(0);" onclick=" showFace(this,'face_list_menu','reply_5_<?php echo $share_row['s_id'];?>_input');"><?php echo $s_langpackage->s_face;?></a>
			</div>
			<div class="clear"></div>
	</div>
	<?php }?>
</div>
</div>
<?php }?>
	<!-- face begin -->
	<div id="face_list_menu" class="emBg" style="display:none;z-index:100;"></div>
	<!-- face end -->
	<div class="guide_info <?php echo $show_error;?>"> <?php echo $error_str;?> </div>
</body>
</html>
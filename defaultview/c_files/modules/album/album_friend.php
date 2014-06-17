<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/album/album_friend.html
 * 如果您的模型要进行修改，请修改 models/modules/album/album_friend.php
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
	require("foundation/auser_mustlogin.php");
	require("foundation/fpages_bar.php");
	require("api/base_support.php");
	require("servtools/menu_pop/trans_pri.php");
	
	//变量取得
	$album_id=intval(get_argg('album_id'));
	$user_id=get_sess_userid();
	$pals_id_str=get_sess_mypals();

	//数据表定义区
	$t_album = $tablePreStr."album";
	
	$dbo=new dbex;
	dbtarget('r',$dbServs);

	$page_num=intval(get_argg('page'));
	$page_total='';
	$album_rs=array();
	if($pals_id_str){
		$album_rs = api_proxy("album_self_by_uid","*",$pals_id_str);
	}
	
	$isNull=0;//不为空则设置为零
	$a_friend="";
	$t_fri="content_none";
	if(empty($album_rs)){
		$isNull=1;//判断结果集是否为空
		$a_friend="content_none";
		$t_fri="";		 	
	}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<script type='text/javascript' src="skin/default/js/jooyea.js"></script>
</head>
<body id="iframecontent">
<div class="create_button"><a href="modules.php?app=album_edit"><?php echo $a_langpackage->a_creat;?></a></div>
<div class="create_button"><a href="modules.php?app=photo_upload"><?php echo $a_langpackage->a_upload;?></a></div>
<h2 class="app_album"><?php echo $a_langpackage->a_title;?></h2>
<div class="tabs">
	<ul class="menu">
        <li><a href="modules.php?app=album" hidefocus="true"><?php echo $a_langpackage->a_mine;?></a></li>
        <li class="active"><a href="modules.php?app=album_friend" hidefocus="true"><?php echo $a_langpackage->a_friend;?></a></li>
    </ul>
</div>
<div class="album_holder">
  <?php foreach($album_rs as $val){?>
  <?php $is_pri=check_pri($val['user_id'],$val['privacy']);?>
		<dl class="list_album" onmouseover="this.className += ' list_album_active';" onmouseout="this.className='list_album';" >
			<dt><a href='<?php echo $is_pri ? "modules.php?app=photo_list&album_id=".$val['album_id']."&user_id=".$val['user_id']:"javascript:void(0)";?>'><img src=<?php echo $is_pri ? $val['album_skin'] : "skin/$skinUrl/images/errorpage.gif";?> onerror="parent.pic_error(this)" class='user_ico'></a></dt>
			<dd><strong><a href="<?php echo $is_pri ? "modules.php?app=photo_list&album_id=".$val['album_id']."&user_id=".$val['user_id']:"javascript:void(0)";?>"><?php echo filt_word($val['album_name']);?></a></strong></dd>
			<dd><?php echo $val['user_name'];?><?php echo $a_langpackage->a_of_album;?><label>(<?php echo str_replace('{holder}',$val['photo_num'],$a_langpackage->a_num);?>)</label></dd>
			<dd><?php echo $a_langpackage->a_label;?>：<?php echo $val['tag'];?></dd>
            <dd><?php echo $a_langpackage->a_update_in;?><?php echo $val['update_time'];?></dd>
			<dd><?php echo $a_langpackage->a_crt_time;?><?php echo $val['add_time'];?></dd>
			<dd>
				<?php if($is_pri){?>
				<span><a href="javascript:void(0);" onclick="parent.show_share(2,<?php echo $val['album_id'];?>,'<?php echo $val['album_name'];?>','','');"><?php echo $a_langpackage->a_share;?></a></span>
				<?php }?>
				<span><a href="javascript:void(0);" onclick="parent.report(2,<?php echo $val['user_id'];?>,<?php echo $val['album_id'];?>);"><?php echo $a_langpackage->a_report;?></a></span>
			</dd>
		</dl>
	<?php }?>
	<div class="blank"></div>
	<?php page_show($isNull,$page_num,$page_total);?>
</div>
	
<div class="guide_info <?php echo $t_fri;?>">
	<?php echo $a_langpackage->a_no_fri;?>
</div>	
</body>
</html>
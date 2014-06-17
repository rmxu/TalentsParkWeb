<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/uiparts/remind_message.html
 * 如果您的模型要进行修改，请修改 models/modules/uiparts/remind_message.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
//引入语言包
$rf_langpackage=new recaffairlp;
//引入提醒模块公共函数
require("api/base_support.php");
$remind_rs=api_proxy("message_get","remind",1,"*");//取得空间提醒
$content_data_none="content_none";
$isNull=0;
if(empty($remind_rs)){
	$isNull=1;
	$content_data_none="";
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<script language=JavaScript src="skin/default/js/jooyea.js"></script>
<SCRIPT language=JavaScript src="servtools/ajax_client/ajax.js"></SCRIPT>
<title></title>
<base href='<?php echo $siteDomain;?>' />
<script type='text/javascript'>
function clear_remind(remind_id){
	var ajax_remind=new Ajax();
	ajax_remind.getInfo("do.php","GET","app","act=message_del&id="+remind_id,function(c){$("remind_"+remind_id).style.display='none';}); 
}
</script>
</head>
<body id="iframecontent">
<h2 class="app_remind"><?php echo $rf_langpackage->rf_space;?></h2>
<div class="tabs">
	<ul class="menu">
	  <li class="active"><a href="modules.php?app=remind_message"><?php echo $rf_langpackage->rf_space;?></a></li>
  </ul>
</div>
	<div class="remind_box mt20">
		<ul class="remind_list">
			<?php foreach($remind_rs as $rs){?>
				<li id='remind_<?php echo $rs['id'];?>'>
          <div class="remind_del"><a href="javascript:clear_remind(<?php echo $rs['id'];?>)"></a></div>
					<div class="photo"><a href="home.php?h=<?php echo $rs['from_uid'];?>" target="_blank"><img src="<?php echo $rs['from_uico'];?>" width="20px" height="20px" alt="" target="_blank" /></a></div>
					<div class="remind_content">
						<a href="home.php?h=<?php echo $rs['from_uid'];?>" target="_blank"><?php echo $rs['from_uname'];?></a>
						<?php echo str_replace(array("{link}","{js}"),array($rs['link'],"clear_remind(".$rs['id'].")"),filt_word($rs['content']));?>
						<?php echo $rs['count']>=2 ? "(".$rs['count']."次)":'';?>
					</div>
          <div class="clear"></div>
				</li>
			<?php }?>
		</ul>
	</div>
</form>
<div class="guide_info <?php echo $content_data_none;?>">
	<?php echo $rf_langpackage->rf_mess_none;?>
</div>
</body>
</html>

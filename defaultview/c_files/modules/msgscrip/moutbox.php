<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/msgscrip/moutbox.html
 * 如果您的模型要进行修改，请修改 models/modules/msgscrip/moutbox.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入模块公共权限过程文件
	require("foundation/fpages_bar.php");
	require("api/base_support.php");
	
	//引入语言包
	$m_langpackage=new msglp;
	
	//变量获得
	$user_id=get_sess_userid();
	
	//当前页面参数
	$page_num=trim(get_argg('page'));

	$msg_outbox_rs=api_proxy("scrip_outbox_get_mine","*");
	
	$isNull=0;
	$show_data="";
	$content_data_none="content_none";
	if(empty($msg_outbox_rs)){
		$isNull=1;
		$show_data="content_none";
		$content_data_none="";		
	}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<script language=JavaScript src="skin/default/js/jooyea.js"></script>
<script type='text/javascript'>
	function check_form(){
		var mail_array=document.getElementsByName('attach[]');
		var num=mail_array.length;
		var check_num=0;
		for(array_length=0;array_length<num;array_length++){
			if(mail_array[array_length].checked==true){
				check_num++;
			}
		}
		if(check_num==0){
			parent.Dialog.alert('<?php echo $m_langpackage->m_none_wrong;?>');
		}else{
			parent.Dialog.confirm('<?php echo $m_langpackage->m_del_ask;?>',function(){document.forms[0].submit();});
		}
	}

</script>
</head>
<body id="iframecontent">
    <div class="create_button"><a href="modules.php?app=msg_creator"><?php echo $m_langpackage->m_creat;?></a></div>
    <h2 class="app_msgscrip"><?php echo $m_langpackage->m_title;?></h2>
    <div class="tabs">
        <ul class="menu">
            <li><a href="modules.php?app=msg_minbox" title="<?php echo $m_langpackage->m_in;?>" hidefocus="true"><?php echo $m_langpackage->m_in;?></a></li>
            <li class="active"><a href="javascript:void(0)" title="<?php echo $m_langpackage->m_out;?>" hidefocus="true"><?php echo $m_langpackage->m_out;?></a></li>
            <li><a href="modules.php?app=msg_notice" title="<?php echo $m_langpackage->m_notice;?>" hidefocus="true"><?php echo $m_langpackage->m_notice;?></a></li>
        </ul>
    </div>
<form action='do.php?act=msg_del&t=1' method='post' onsubmit='check_form()'>
<div class="rs_head <?php echo $show_data;?>">
	<span class="right"><?php echo str_replace("{num}",count($msg_outbox_rs),$m_langpackage->m_num_mail);?></span>
	<span><?php echo $m_langpackage->m_choose;?>
    	<a href="javascript:select_attach(1);"><?php echo $m_langpackage->m_all;?></a> - 
		<a href="javascript:select_attach(0);"><?php echo $m_langpackage->m_b_can;?></a>
    </span>
    <span><a href="javascript:document.forms[0].onsubmit();"><?php echo $m_langpackage->m_del;?></a></span>
</div>
<table class="msg_inbox <?php echo $show_data;?>">
<?php foreach($msg_outbox_rs as $key => $val){?>
	 <tr>
	   <td width="25"><input name="attach[]" type="checkbox" value="<?php echo $val["mess_id"];?>" /></td>
		 <td width="30"><img title="<?php echo $val["state"]?$m_langpackage->m_sed:$m_langpackage->m_have_access;?>" src='skin/<?php echo $skinUrl;?>/images/messend_<?php echo $val["state"];?>.gif' /></td>
		 <td width="70"><div class="avatar"><a target="_blank" href='home.php?h=<?php echo $val['to_user_id'];?>'><img src="<?php echo $val["to_user_ico"];?>"/></a></div></td>
		 <td width="135"><?php echo $m_langpackage->m_given;?>：<a target="_blank" href='home.php?h=<?php echo $val['to_user_id'];?>'><?php echo $val["to_user"];?></a>
         <br /><span class="gray"><?php echo $val["add_time"];?></span></td>
		 <td><a title='<?php echo $val["mess_title"];?>' href='modules.php?app=msg_rpshow&id=<?php echo $val["mess_id"];?>&t=1'><?php echo $val["mess_title"];?></a></td>
		 <td width="20"><a href='do.php?act=msg_del&id=<?php echo $val["mess_id"];?>&t=1' onclick='return confirm("<?php echo $m_langpackage->m_del_ask;?>");'><img title="<?php echo $m_langpackage->m_del;?>" src="skin/<?php echo $skinUrl;?>/images/del.png" /></a></td>
	 </tr>
<?php }?>
</table>
<div class="rs_head <?php echo $show_data;?>">
	<span class="right"></span>
    <span><?php echo $m_langpackage->m_choose;?><a href="javascript:select_attach(1);"><?php echo $m_langpackage->m_all;?></a> - <a href="javascript:select_attach(0);"><?php echo $m_langpackage->m_b_can;?></a> &nbsp <a href="javascript:document.forms[0].onsubmit();"><?php echo $m_langpackage->m_del;?></a></span>
</div>
</form>
<?php echo page_show($isNull,$page_num,$page_total);?>
<div class="guide_info <?php echo $content_data_none;?>">
  <?php echo $m_langpackage->m_out_none;?>
</div>
</body>
</html>
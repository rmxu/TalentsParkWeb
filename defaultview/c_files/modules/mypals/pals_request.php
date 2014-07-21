<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/mypals/pals_request.html
 * 如果您的模型要进行修改，请修改 models/modules/mypals/pals_request.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php 
	//必须登录才能浏览该页面
	require("foundation/auser_mustlogin.php");
	
	//引入语言包
	$mp_langpackage=new mypalslp;
	
	//引入公共模块
	require("foundation/module_mypals.php");
		
	//变量区
	$user_id=get_sess_userid();
	
	//数据表定义区
	$t_pals_request=$tablePreStr."pals_request";
	
	$dbo=new dbex;
	dbtarget('r',$dbServs);
	
	$request_rs=array();
	
	$sql="select * from $t_pals_request where user_id='$user_id'";
	$request_rs=$dbo->getRs($sql);
	
	//控制显示
	$isNull=0;
	$isset_data="";
	$none_data="content_none";
	if(empty($request_rs)){
		$isset_data="content_none";
		$none_data="";
		$isNull=1;
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
</head>
<body id="iframecontent">
    <div class="create_button"><a href="modules.php?app=mypals_search"><?php echo $mp_langpackage->mp_add;?></a></div>
    <h2 class="app_friend"><?php echo $mp_langpackage->mp_mypals;?></h2>
    <div class="tabs">
        <ul class="menu">
          <li><a href="modules.php?app=mypals" title="<?php echo $mp_langpackage->mp_list;?>"><?php echo $mp_langpackage->mp_list;?></a></li>
          <li class="active"><a href="modules.php?app=mypals_request" title="<?php echo $mp_langpackage->mp_req;?>"><?php echo $mp_langpackage->mp_req;?></a></li>
          <li><a href="modules.php?app=mypals_invite" title="<?php echo $mp_langpackage->mp_invite;?>"><?php echo $mp_langpackage->mp_invite;?></a></li>
          <li><a href="modules.php?app=mypals_sort" title="<?php echo $mp_langpackage->mp_m_sort;?>"><?php echo $mp_langpackage->mp_m_sort;?></a></li>
        </ul>
    </div>
<div class="clear mt20"></div>
<table class="msg_inbox <?php echo $isset_data;?>">
<?php foreach($request_rs as $rs){?>
	<tr>
		<td width="70" align="center">
			<div class="avatar"><a href="home.php?h=<?php echo $rs['req_id'];?>" target="_blank"><img src='<?php echo $rs["req_ico"];?>' height='43px' width='43px' onerror="parent.pic_error(this)" /></a>
			</div>
		</td>
		<td width="180">
			<a href="home.php?h=<?php echo $rs['req_id'];?>" target="_blank"><?php echo $rs["req_name"];?></a><?php echo $mp_langpackage->mp_add_you;?>
      <br/><label class="gray"><?php echo $rs["add_time"];?></label>
    </td>
		<td>
			<div id='request_<?php echo $rs['req_id'];?>'>
			<span>
				<a href='do.php?act=confirm_both&req_id=<?php echo $rs["id"];?>&req_user_id=<?php echo $rs["req_id"];?>' target='request_<?php echo $rs['req_id'];?>' name='ajax'><?php echo $mp_langpackage->mp_both_pal;?></a>
			</span>
      <span>|</span> 
      <span><a href='do.php?act=confirm_other&req_id=<?php echo $rs["id"];?>&req_user_id=<?php echo $rs["req_id"];?>' target='request_<?php echo $rs['req_id'];?>' name='ajax'><?php echo $mp_langpackage->mp_other_pal;?></a></span>
      <span>|</span>
      <span><a href='do.php?act=refuse_req&req_id=<?php echo $rs["id"];?>&req_user_id=<?php echo $rs["req_id"];?>' target='request_<?php echo $rs['req_id'];?>' name='ajax'><?php echo $mp_langpackage->mp_ref_pal;?></a></span>
			</div>
		</td>
    <td width="20">
       <a href='do.php?act=del_req&req_id=<?php echo $rs['id'];?>&req_uid=<?php echo $rs['req_id'];?>' onclick='return confirm("<?php echo $mp_langpackage->mp_con_del_req;?>")';><img title="<?php echo $mp_langpackage->mp_del_req;?>" src="skin/<?php echo $skinUrl;?>/images/del.png" /></a>
    </td>
	</tr>
<?php }?>
</table>

	<div class='guide_info <?php echo $none_data;?>'>
		<?php echo $mp_langpackage->mp_no_req;?>
	</div>

<script type='text/javascript' src="servtools/ajax_client/ajax.js"></script>
<script type='text/javascript' src="servtools/ajax_client/auto_ajax.js"></script>
</body>
</html>
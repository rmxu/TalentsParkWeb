<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/users/user_dressup.html
 * 如果您的模型要进行修改，请修改 models/modules/users/user_dressup.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//语言包引入
	$u_langpackage=new userslp;
	$user_id =get_sess_userid();
	
	//引入模块公共方法文件 
	require("foundation/module_users.php");
	$tpl_array=explode("/",$skinUrl);
	$tpl_name=$tpl_array[0];
	$dress_url="skin/".$tpl_name."/home/";
	
	//引入装扮数据
	require($dress_url."tip.php");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<script type='text/javascript' src="skin/default/js/jooyea.js"></script>
<script type='text/javascript'>
function mouseOver(id){
	var Element = document.getElementById(id+'_hover');
	var Element2 = document.getElementById(id+'_origin');
	var parentNode = Element.parentNode;
	Element2.style.display = 'none';
	Element.style.display = '';
	Element.style.top = parentNode.offsetTop + 'px';
	Element.style.left = parentNode.offsetLeft + 'px';
	Element.onmouseover = function(){Element.style.display = '';}
}
function mouseOut(id){
	var Element = document.getElementById(id+'_hover');
	var Element2 = document.getElementById(id+'_origin');
	Element2.style.display = '';
	Element.style.display = 'none';
}
</script>
</head>

<body id="iframecontent">
<h2 class="app_user"><?php echo $u_langpackage->u_conf;?></h2>
<div class="tabs">
	<ul class="menu">
        <li><a href="modules.php?app=user_info" title="<?php echo $u_langpackage->u_info;?>"><?php echo $u_langpackage->u_info;?></a></li>
        <li><a href="modules.php?app=user_ico" title="<?php echo $u_langpackage->u_icon;?>"><?php echo $u_langpackage->u_icon;?></a></li>
        <li><a href="modules.php?app=user_pw_change" title="<?php echo $u_langpackage->u_pw;?>"><?php echo $u_langpackage->u_pw;?></a></li>
        <li class="active"><a href="modules.php?app=user_dressup" title="<?php echo $u_langpackage->u_dressup;?>"><?php echo $u_langpackage->u_dressup;?></a></li>
        <li><a href="modules.php?app=user_affair" title="<?php echo $u_langpackage->u_set_affair;?>"><?php echo $u_langpackage->u_set_affair;?></a></li>
    </ul>
</div>

<div class="rs_head"><?php echo $u_langpackage->u_select_dressup;?></div>
<div class="dress_box">
	<ul class="dress_list">
		
		<?php foreach($home_dressup_array as	$key => $rs){?>
		
			<li id="skin_<?php echo $key;?>">

				<div id="skin_<?php echo $key;?>_origin" onmouseover="mouseOver('skin_<?php echo $key;?>');" onmouseout="mouseOut('skin_<?php echo $key;?>');" class="origin_box">
					<div class="img_box">
						<div class="img_c_box">
							<img width="152" alt="<?php echo $rs['title'];?>" src="<?php echo $dress_url;?>/<?php echo $rs['thumb'];?>" />
						</div>
					</div>	
					<h3><?php echo $rs['title'];?></h3>
				</div>
				
				<div id="skin_<?php echo $key;?>_hover"  onmouseover="mouseOver('skin_<?php echo $key;?>');" onmouseout="mouseOut('skin_<?php echo $key;?>');" class="hover_box" style="display:none;">
					<div class="img_box">
						<div class="img_c_box">
							<a href='javascript:void(0)' onclick=top.location.href="home.php?h=<?php echo $user_id;?>&dress_name=<?php echo $key;?>"><img width="152" alt="<?php echo $rs['title'];?>" src="<?php echo $dress_url;?>/<?php echo $rs['thumb'];?>" /></a>
						</div>
					</div>	
					<h3><?php echo $rs['title'];?></h3>
					<div class="operate">
						<input onclick='top.location.href="home.php?h=<?php echo $user_id;?>&dress_name=<?php echo $key;?>"' class="small-btn mr10" type="button" value="<?php echo $u_langpackage->u_scanf;?>" />
						<input onclick='top.location.href="do.php?act=user_dress_change&dress_name=<?php echo $key;?>"' class="small-btn" type="button" value="<?php echo $u_langpackage->u_use;?>" />
					</div>
				</div>
				
			</li>
			
			<?php }?>

		<div class="clear"></div>
	</ul>
</div>
</body>
</html>

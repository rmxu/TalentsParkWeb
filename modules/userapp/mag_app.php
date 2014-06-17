<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/userapp/mag_app.html
 * 如果您的模型要进行修改，请修改 models/modules/userapp/mag_app.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
require("api/base_support.php");
//引入语言包
$pl_langpackage=new pluginslp;
$app_rs=api_proxy("plugins_get_mine");
$def_small_image="skin/".$skinUrl."/images/plu_def_small.gif";
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
</head>
<body id="iframecontent">
    <div class="create_button"><a href="modules.php?app=all_app"><?php echo $pl_langpackage->pl_add_app;?></a></div>
    <h2 class="app_set"><?php echo $pl_langpackage->pl_manage;?></h2>
    <div class="tabs">
        <ul class="menu">
            <li class="active"><a href="modules.php?app=mag_app" ><?php echo $pl_langpackage->pl_set_app;?></a></li>	
        </ul>
    </div>
	<?php if($app_rs){?>
	
      <div class="iframe_contentbox">
		<ul class="app_list">
			<?php foreach($app_rs as $rs){?>		<?php $small_url=$rs['image']?preg_replace("/(.*)(\.\w+)/","$1_small$2","plugins/".$rs['name']."/".$rs['image']):$def_small_image;?>
			<li onmouseover="this.className += ' list_app_active';" onmouseout="this.className='app_list';">
				<div class="figure_box">
					<a href="modules.php?app=add_app&id=<?php echo $rs["id"];?>"><img src="<?php echo $small_url;?>" /></a>
				</div>
				<h3 class="name">
					<a href="modules.php?app=add_app&id=<?php echo $rs["id"];?>"><?php echo $rs["title"];?></a>
				</h3>
				<p class="description"><?php echo $rs["info"];?></p>
                <div class="statistics">
					<span><?php echo str_replace("{num}",$rs["use_num"],$pl_langpackage->pl_total_num);?></span>
					<!--|&nbsp;<span><?php echo $pl_langpackage->pl_comment;?>(<?php echo $rs["comment_num"];?>)</span>!-->
				</div>
				<div class="app_control">
                    <a href="do.php?act=del_app&id=<?php echo $rs["id"];?>" onclick="return confirm('<?php echo $pl_langpackage->pl_ask_del;?>')";><?php echo $pl_langpackage->pl_del;?>
</a>
				</div>
			</li>
			<?php }?>
		</ul>
	</div>

	<?php }?>
	<?php if(empty($app_rs)){?>
	<div class="guide_info">
		<?php echo $pl_langpackage->pl_mine_none;?>
	</div>
	<?php }?>
</body>
</html>

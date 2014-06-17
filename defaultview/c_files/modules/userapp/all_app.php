<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/userapp/all_app.html
 * 如果您的模型要进行修改，请修改 models/modules/userapp/all_app.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?>﻿<?php
$pl_langpackage=new pluginslp;
require("api/base_support.php");
require("foundation/fpages_bar.php");
$search_app=get_argp('search_app');
$def_image="skin/".$skinUrl."/images/plu_def.jpg";
$page_num=intval(get_argg('page'));
$app_rs=array();
if($search_app){
	$page_total='';
	$dbo=new dbex;
	dbtarget('r',$dbServs);
	$t_plugins = $tablePreStr."plugins";
	$search_app=short_check(get_argp('search_app'));
	$sql="select * from $t_plugins where `title` like '%$search_app%'";
	$app_rs=$dbo->getRs($sql);
	$isNull=1;
	$error_str=$pl_langpackage->pl_search_none;
}else{
	$app_rs=api_proxy("plugins_get_all");
	$isNull=0;
	if(empty($app_rs)){
		$isNull=1;
	}
	$error_str=$pl_langpackage->pl_none;
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<script type='text/javascript'>
	function check_form(){
		var search_app=document.getElementById('search_app').value;
		if(search_app.replace(/(^\s*)|(\s*$)|(　*)/g,"")==''){
			parent.Dialog.alert('<?php echo $pl_langpackage->pl_search_wrong;?>');return false;
		}else{
			document.forms[0].submit();
		}
	}
</script>
</head>
<body id="iframecontent">
	<div class="share_box right">
	    <form method='post'>
	    	<input class="small-text" type='text' id='search_app' name='search_app' AUTOCOMPLETE='off' />
			<span class="share_button" onclick="check_form()"><?php echo $pl_langpackage->pl_search;?></span>
	  	</form>
		</div>
    <h2 class="app_set"><?php echo $pl_langpackage->pl_add_app;?></h2>
    <div class="tabs">
        <ul class="menu">
            <li class="active"><a href="modules.php?app=all_app" class="nowbutl"><?php echo $pl_langpackage->pl_app_list;?></a></li>	  	
        </ul>
    </div>

	<?php if($app_rs){?>
	<div class="iframe_contentbox">
		<ul class="app_list">
			<?php foreach($app_rs as $rs){?>
			<?php $image_url=$rs['image']?"plugins/".$rs['name']."/".$rs['image']:$def_image;?>
			<li onmouseover="this.className += ' list_app_active';" onmouseout="this.className='app_list';">
				<div class="figure_box">
					<a href="modules.php?app=add_app&id=<?php echo $rs["id"];?>"><img src="<?php echo $image_url;?>" title="" alt=""></a>
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
            <a href="modules.php?app=add_app&id=<?php echo $rs["id"];?>"><?php echo $pl_langpackage->pl_add;?></a>
				</div>
			</li>
			<?php }?>
		</ul>
	</div>
	<?php }?>
	<?php echo page_show($isNull,$page_num,$page_total);?>
	<?php if(empty($app_rs)){?>
	<div class="guide_info">
		<?php echo $error_str;?>
	</div>
	<?php }?>
</body>
</html>
<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/group/group_select.html
 * 如果您的模型要进行修改，请修改 models/modules/group/group_select.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入语言包
	$g_langpackage=new grouplp;
	
	//引入公共模块
	require("foundation/module_group.php");
	require("api/base_support.php");
	
	//缓存功能区
	$group_sort_rs=api_proxy("group_sort_by_self");
	$group_type=group_sort_list($group_sort_rs,"");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
</head>
<body id="iframecontent">
    <div class="create_button"><a href="modules.php?app=group_hot"><?php echo $g_langpackage->g_return_hot;?></a></div>
    <h2 class="app_group"><?php echo $g_langpackage->g_find_group;?></h2>
    <div class="tabs">
        <ul class="menu">
            <li class="active"><a href="javascript:;" title="<?php echo $g_langpackage->g_find_group;?>" class="nowbutl"><?php echo $g_langpackage->g_find_group;?></a></li>
        </ul>
    </div>
</div>
<table class='form_table'>
	<form name="form1" onSubmit='return check_form("group_name");' method="get">
		<tr>
			<th style="width:180px;">
                <?php echo $g_langpackage->g_f_name;?>：</th>
			<td><input class="small-text2 mr10" type="text" name="group_name" id="group_name"  maxlength="30" autocomplete='off'></td>
		</tr>
		<tr>
			<th style="width:180px;">
                <?php echo $g_langpackage->g_f_tag;?>：</th>
			<td><input class="small-text2 mr10" type="text" name="tag" id="tag" maxlength="30" autocomplete='off'></td>	
		</tr>
		<tr>
			<th style="width:180px;">
              	<input type="hidden" name="group_type_name" id="group_type_name" />
                <?php echo $g_langpackage->g_f_type;?>：</th> 
			<td><div class="form_select_div"><?php echo $group_type;?></div></td>	
		</tr>
		<tr><th></th><td><input type="hidden" name="app" id="app" value="search_group"><input class="regular-btn" name="submit" type="submit" value="<?php echo $g_langpackage->g_seek;?>"></td></tr>
	</form>
</table>
</body>
</html>
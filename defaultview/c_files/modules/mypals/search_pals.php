<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/mypals/search_pals.html
 * 如果您的模型要进行修改，请修改 models/modules/mypals/search_pals.php
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
	$mp_langpackage=new mypalslp;
	$l_langpackage=new loginlp;
	$pu_langpackage=new publiclp;
	
	
	
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<script src="servtools/area.js" type="text/javascript"></script>
<script src="skin/default/js/jooyea.js" type="text/javascript"></script>
</head>
<body id="iframecontent">
    <div class="create_button"><a href="modules.php?app=mypals"><?php echo $mp_langpackage->mp_re_list;?></a></div>
    <h2 class="app_friend"><?php echo $mp_langpackage->mp_mypals;?></h2>
    <div class="tabs">
        <ul class="menu">
            <li class="active"><a href="modules.php?app=mypals_search" hidefocus="true"><?php echo $mp_langpackage->mp_find;?></a></li>
        </ul>
    </div>

<div class="iframe_contentbox">
	<div class="search_box">
		<div class="search_box_ct">
			<form action='modules.php'>
				<input type='hidden' name='app' value='mypals_search_list' />
				<table cellpadding="0" cellspacing="0" class="form_table">
					<tr><th><?php echo $mp_langpackage->mp_name;?>：</th><td><input name="memName" value="" size="12" class="small-text" type="text"></td></tr>
					<tr><th><?php echo $mp_langpackage->mp_birth;?>：</th><td><select id="s1" name='q_province'></select><select name='q_city' id="s2" ></select><script type="text/javascript">
				setup();
				</script></td></tr>
					<tr><th><?php echo $mp_langpackage->mp_reside;?>：</th><td><select id="r1" name='s_province'></select><select name='s_city' id="r2"></select><script type="text/javascript">
				setup2();
				</script></td></tr>
					<tr><th><?php echo $mp_langpackage->mp_age;?>：</th><td><select name='age' ><option value=''><?php echo $mp_langpackage->mp_none_limit;?></option><option value='16|22'>16-22<?php echo $mp_langpackage->mp_years;?></option><option value='23|30'>23-30<?php echo $mp_langpackage->mp_years;?></option><option value='31|40'>31-40<?php echo $mp_langpackage->mp_years;?></option><option value='40|100'>40<?php echo $mp_langpackage->mp_years;?><?php echo $mp_langpackage->mp_over;?></option></select></select></td></tr>
					<tr><th><?php echo $mp_langpackage->mp_sex;?>：</th><td><input class="input_radio" type="radio" name='sex' value='' checked /><?php echo $mp_langpackage->mp_none_limit;?> <input class="input_radio" type="radio" name='sex' value='1' /><?php echo $mp_langpackage->mp_man;?> <input class="input_radio" type="radio" name='sex' value='0' /><?php echo $mp_langpackage->mp_woman;?></td></tr>
					<tr><th><?php echo $mp_langpackage->mp_online;?>：</th><td><input type="checkbox" name='online' value=1 /></td></tr>
					<tr><th></th><td><input value="<?php echo $mp_langpackage->mp_search;?>" class="regular-btn" type="submit"></td></tr>
				</table>
			</form>
		</div>
	</div>
</div>
</body>
</html>
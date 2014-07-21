<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/uiparts/footor.html
 * 如果您的模型要进行修改，请修改 models/uiparts/footor.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
require("foundation/module_lang.php");
?><script type="text/javascript">
	function set_cookie_lp(lp_str){
		document.cookie = "lp_name=" + escape(lp_str);
		window.location.reload();
	}
</script>
<div class="foot">
	<a href="about/about.html"><?php echo $pu_langpackage->pu_about_us;?></a>
	<a href="about/privacy.html"><?php echo $pu_langpackage->pu_privacy;?></a>
	<a href="http://tech.jooyea.com/bbs/"><?php echo $pu_langpackage->pu_bbs;?></a>
	<a href="mailto:<?php echo $adminEmail;?>"><?php echo $pu_langpackage->pu_email;?></a>Powered by <strong>iWeb<b>SNS</b>1.0</strong> &copy; 2009-2010 Jooyea.net</div>
<div style="display: none;" class="emBg" id="face_list_menu"></div>
<div id="append_parent"></div>
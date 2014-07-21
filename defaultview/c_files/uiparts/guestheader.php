<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/uiparts/guestheader.html
 * 如果您的模型要进行修改，请修改 models/uiparts/guestheader.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
require("foundation/fdnurl_aget.php");
//���԰�����
$l_langpackage=new loginlp;
$re_langpackage=new reglp;
$pu_langpackage=new publiclp;
$u_langpackage=new userslp;
$ah_langpackage=new arrayhomelp;
?>
<script type="text/javascript" language="javascript" src="skin/default/js/jy.js"></script>
<script language="javascript">
function addBookMark()
{
    var nome_sito = "<?php echo $siteName;?>";
    var url_sito = "<?php echo get_site_domain();?>";
    if ((navigator.appName == "Microsoft Internet Explorer") && (parseInt
        (navigator.appVersion) >= 4))
        window.external.AddFavorite(url_sito, nome_sito);
    else if (navigator.appName == "Netscape")
        window.sidebar.addPanel(nome_sito, url_sito, '');
    else
        parent.Dialog.alert("<?php echo $pu_langpackage->pu_house_wrong;?>");
}

function setMyHomepage(url){   //  设置首页 
		 if(!!(window.attachEvent && !window.opera)){
				document.body.style.behavior = 'url(#default#homepage)';
				document.body.setHomePage(url);
		}else{
			if(window.clipboardData && clipboardData.setData){
		        clipboardData.setData('text', url);
		    }else{
		         parent.Dialog.alert('<?php echo $ah_langpackage->ah_browser_clipboard;?>');
		    }
		}
}
</script>
<?php if(basename($_SERVER['SCRIPT_FILENAME'])!='home.php'){?>
<div class="head">
    <h1><a href="index.php"><img alt="jooyea" src="skin/<?php echo $skinUrl;?>/images/snslogo.gif"></a></h1>
    <div class="search">
        <div class="schbox">
           <form class="search_box" action="index.php" target="_blank" onsubmit="clear_def(this,'<?php echo $ah_langpackage->ah_enter_name;?>');">
            <input id="searchtop_input" maxlength='20' class="inpt" type="text" name='memName' value="<?php echo $ah_langpackage->ah_enter_name;?>" onblur="inputTxt(this,'set');" onfocus="inputTxt(this,'clean');" />
            <input class="btn" type="submit" value="" />
        	<input type='hidden' name='tg' value='search_pals_list' />
        </form>
        </div>
        <a href="javascript:goLogin();"><?php echo $ah_langpackage->ah_advanced_search;?></a>
    </div>
</div>
<div class="clear"></div>
<?php }?>
<div class="top_bg">
	<div class="nav">
	  <span class="left">
	    <a href="index.php"><?php echo $ah_langpackage->ah_homepage;?></a>
	    <a href="index.php?tg=search_pals_list&online=1"><?php echo $ah_langpackage->ah_see_who_online;?></a>
	    <a href="help/help.html">帮助</a>
		</span>
	  <span class="right">
		  <a href="javascript:addBookMark();"><?php echo $pu_langpackage->pu_collection;?></a>
		  <a href="javascript:setMyHomepage('<?php echo get_site_domain();?>');"><?php echo $pu_langpackage->pu_index_set;?></a>
		  <a href="modules.php?app=user_reg"><?php echo $pu_langpackage->pu_register;?></a>
		  <a href="index.php"><?php echo $pu_langpackage->pu_logon;?></a>
	  </span>
	</div>
</div>
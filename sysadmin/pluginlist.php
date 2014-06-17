<?php
require("session_check.php");
$is_check=check_rights("d01");
if(!$is_check){
	echo $m_langpackage->m_no_pri;
	exit;
}
$pl_langpackage=new pluginslp;
$ad_langpackage=new adminmenulp;
$bp_langpackage=new back_publiclp;

$plugin_dir=dir(dirname(__file__)."/../plugins");
chdir(dirname(__file__)."/../plugins");
function getInfo($path){
	global $pl_langpackage;
	global $dir;
	$p_status=$pl_langpackage->pl_unset;
	$p_change=$pl_langpackage->pl_install;
	$p_update="Update(\"update_plugin.php?path=$path\")";
	$p_operator="Install";
	$p_index="install_plugin_1.php";
	$pluginxml=dirname(__file__)."/../plugins/$path/plugin.xml";
	if(file_exists($pluginxml))
	{
		$p_update="noinstall()";
	}
	else
	{
		$pluginxml=dirname(__file__)."/../plugins/$path/_plugin.xml";
		$p_status=$pl_langpackage->pl_isset;
		$p_change=$pl_langpackage->pl_unload;
		$p_index="unload_plugin.php";
		$p_operator="Unloadfirm";
	}
	if(file_exists($pluginxml))
	{
		$dom = new DOMDocument();
		$dom->load($pluginxml);
		$plugin=$dom->getElementsByTagName("plugin");
		$infos=$plugin->item(0)->childNodes;
		$plugin_id="";
		$auto_checked="";
		$valid_checked="";
		$back_index="javascript:void(0)";
		foreach($infos as $info)
		{
			if($info->nodeName!="#text")
			{
				$tem=$info->nodeName;
				if($tem!="urls")$$tem=$info->nodeValue;
				else{
					$url_node=$info->getElementsByTagName('url');
					foreach($url_node as $rs){
						if($rs->attributes->item(0)->value=='plugin_back'){
							$back_index='../plugins/'.$path.'/'.$rs->nodeValue;break;
						}
					}
				}
			}

		}
		if(isset($autoorder))$auto_checked="checked='checked'";
		if(isset($valid))$valid_checked="checked='checked'";
		echo "<tr><td><a href='javascript:void(0)' onclick='$p_update'>$title</a></td><span id='span_$path'></span></td><td style=text-align:center><a href='$back_index'>".$pl_langpackage->pl_management_entrance."</a></td><td style=text-align:center>$p_status</td><td style=text-align:center><input class='regular-button' type='submit' onclick=\"$p_operator('$p_index?path=$path')\" value='$p_change'/></td></tr>";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
<script type="text/javascript" src="js/plugin.js"></script>
<script type='text/javascript'>
var install_submit="";
var install_end=false;
var unload_end=false;
var url="";

var diag_install = new parent.Dialog();
function Install(url){
	diag_install.Width = 600;
	diag_install.Height = 900;
	diag_install.Title = "<?php echo $pl_langpackage->pl_guide;?>";
	diag_install.URL = url;
	diag_install.ShowMessageRow = true;
	diag_install.MessageTitle = "<?php echo $pl_langpackage->pl_install_info;?>";
	diag_install.Message = "<?php echo $pl_langpackage->pl_install_worning;?>";
	diag_install.OKEvent = installNext;//点击确定后调用的方法
	diag_install.show();
	diag_install.okButton.value = '下一步';
}

function installNext()
{
	if(install_submit!="")
	{
		diag_install.innerFrame.contentWindow.document.setplugin.submit();
		diag_install.okButton.value = '完成';
		diag_install.URL = url;
		install_submit="";
	}else
	{
		diag_install.innerFrame.src = diag_install.URL;
	}
	if(install_end)
	{
		parent.window.frames["right"].location.reload();
		diag_install.close();
		next();
	}
}

var diag_unload = new parent.Dialog();
function Unload(url){
	diag_unload.Width = 600;
	diag_unload.Height = 500;
	diag_unload.Title = "<?php echo $pl_langpackage->pl_guide;?>";
	diag_unload.URL = url;
	diag_unload.Ok="<?php echo $pl_langpackage->pl_sure;?>";
	diag_unload.ShowMessageRow = true;
	diag_unload.MessageTitle = "<?php echo $pl_langpackage->pl_unload_info;?>";
	diag_unload.Message = "<?php echo $pl_langpackage->pl_unload_remind;?>";
	diag_unload.OKEvent = unloadNext;//点击确定后调用的方法
	diag_unload.show();
}

function unloadNext()
{
	diag_unload.close();
	next();
}

var diag_update = new parent.Dialog();
function Update(url){
	diag_update.Width = 600;
	diag_update.Height = 500;
	diag_update.Title = "<?php echo $pl_langpackage->pl_manage_str;?>";
	diag_update.URL=url;
	diag_update.ShowMessageRow = true;
	diag_update.MessageTitle = "<?php echo $pl_langpackage->pl_manage;?>";
	diag_update.Message = "<?php echo $pl_langpackage->pl_set_update;?>";
	diag_update.OKEvent = updateform;
	diag_update.show();
}
function updateform()
{
	diag_update.innerFrame.contentWindow.document.uploadform.submit();
}
function next()
{
	location.replace('pluginlist.php');
}
function Unloadfirm(url){
	parent.Dialog.confirm('<?php echo $pl_langpackage->pl_worning;?>',function(){Unload(url);});
}
function noinstall()
{
	parent.Dialog.alert('<?php echo $pl_langpackage->pl_unset_state;?>');
}
</script>
<title></title>
</head>
<body>
<div id="maincontent">
  <div class="wrap">
    <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_manage_mod;?></a> &gt;&gt; <a href="pluginlist.php"><?php echo $ad_langpackage->ad_manage_plug;?></a></div>
    <hr />
    <div class="infobox">
      <h3><?php echo $pl_langpackage->pl_list;?></h3>
      <div class="content">
        <table class="list_table">
            <thead><tr>
            	
                    <th><?php echo $pl_langpackage->pl_name;?></th>
                    <th style="text-align:center"><?php echo $pl_langpackage->pl_back_link_address;?></th>
                    <th width="100" style="text-align:center"><?php echo $pl_langpackage->pl_state;?></th>
                    <th width="100" style="text-align:center"><?php echo $pl_langpackage->pl_ctrl;?></th>
                
            </tr></thead>
            <?php
            while (false !== ($dir = $plugin_dir->read())) {
                if(is_dir($dir)&&$dir!='.' &&$dir!='..')getInfo($dir);
            }
            $plugin_dir->close();
            ?>
        </table>
			</div>
		</div>
	</div>
</div>
</body>
</html>
<?php
require("session_check.php");
include(dirname(__file__)."/../plugins/plugin_layout.php");
$is_check=check_rights("d02");
if(!$is_check){
	echo $m_langpackage->m_no_pri;
	exit;
}

$step=1;
$plugin_name="";
$path=get_args('path');

$next="install_plugin_2.php?path=$path";
$plugin_dir=dir(dirname(__file__)."/../plugins");
chdir(dirname(__file__)."/../plugins");

//根据位置获取插件信息
$pluginxml=dirname(__file__)."/../plugins/$path/plugin.xml";
if(file_exists($pluginxml)){
	$dom = new DOMDocument();
	$dom->load($pluginxml);
	$plugin=$dom->getElementsByTagName("plugin");
	$infos=$plugin->item(0)->childNodes;
	$plugin_id="";
	$auto_checked="";
	$valid_checked="";
	$url_list='';
	
	foreach($infos as $info)
	{
		if($info->nodeName!="#text")
		{
			if($info->nodeName!="urls")
			{
				$tem=$info->nodeName;
				$$tem=$info->nodeValue;
			}
			else
			{
				$urls=$info->getElementsByTagName("url");
				foreach($urls as $url)
				{
					if($url->nodeName!="#text")
					{
						 $url_list.="<tr><td bgcolor='#FFFFFF'>".$plugin_layout[$url->getAttributeNode("id")->value]."</td><td>".$url->nodeValue;
					}
				}
				
			}
		}
	}
	
	if(!isset($show))$show=0;
	$next.="&type=$show";
	if(isset($sqlpath)){
		$next.="&sql=$sqlpath";
	}else{
		$sqlpath="";
	}
	if(isset($show)){
		$show=intval($show);
		if($show==0)$show="Widget插件";
		else if($show==1) $show="APP应用";
		else $show="Widget+APP";
	}else{
		$show="";
	}
	if(!isset($image))$image='';

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script>
function switchTag(tag,content,num){
	for(i=1; i <num; i++){
	   if("tag"+i==tag){
	    document.getElementById(tag).getElementsByTagName("a")[0].className="selectli"+i;
	    document.getElementById(tag).getElementsByTagName("a")[0].getElementsByTagName("span")[0].className="selectspan"+i;
	   }else{
	    document.getElementById("tag"+i).getElementsByTagName("a")[0].className="";
	    document.getElementById("tag"+i).getElementsByTagName("a")[0].getElementsByTagName("span")[0].className="";
	   }
	   if("content"+i==content){
	    document.getElementById(content).className="";
	   }else{
	    document.getElementById("content"+i).className="hidecontent";
	   }
	   document.getElementById("content").className=content;
	}
}
</script>
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
<title></title>
</head>

<body background="#fff" >
<h1 style="line-height:24px; padding-top:8px;">IWebSNS插件安装向导</h1>
<hr />
<?php if(isset($title) && isset($author) && isset($version)){?>
<div id="container">
<div id="content" class="content1">
<div id="content1">
<table class="form-table" background="#ccc" width="100%" border="0" cellpadding="0" cellspacing="1" >
      <tr>
        <td colspan="2" bgcolor="#FFFFFF"><strong>插件基本信息</strong></a></td>
        </tr>
      <tr>
        <td width="23%" bgcolor="#FFFFFF">插件名称：</td>
        <td width="60%" bgcolor="#FFFFFF"><?php echo $title;?></td>
        </tr>
      <tr>
        <td bgcolor="#FFFFFF">版本号：</td>
        <td bgcolor="#FFFFFF"><?php echo $version;?></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF">作者：</td>
        <td bgcolor="#FFFFFF"><?php echo $author;?></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF">开发者主页：</td>
        <td bgcolor="#FFFFFF"><?php echo $website;?></td>
      </tr>
      <tr>
        <td colspan="3" bgcolor="#FFFFFF">插件描述：<br/><?php echo $description;?></td>
      </tr>
    </table>
</div>
<div id="content2" class="hidecontent">
<table class="form-table" style="margin-top:20px;" bordercolor="#ccc" width="100%" border="0" cellpadding="0" cellspacing="1">
  <tr>
    <td colspan="2" bgcolor="#FFFFFF"><strong>详细说明</strong></td>
    </tr>
  <tr>
    <td width="23%" bgcolor="#FFFFFF">展示位置</td>
    <td bgcolor="#FFFFFF">入口文件</td>
  </tr>
    <?php echo $url_list;?>
	<tr>
      <td colspan="2" bgcolor="#FFFFFF">其它信息：</td>
    </tr>
    <tr>
      <td bgcolor="#FFFFFF">数据数据库文件</td>
      <td bgcolor="#FFFFFF"><?php echo $sqlpath;?></td>
    </tr>
  </table>
</div>
</div>
</div>
<script>parent.right.diag_install.URL='<?php echo $next?>';</script>
<?php }else{?>
插件配制信息不全，不具备安装条件！
<?php }?>
</body>
</html>
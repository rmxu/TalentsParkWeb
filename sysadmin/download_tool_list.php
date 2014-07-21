<?php
require("session_check.php");
require("proxy/proxy.php");
$is_check=check_rights("f02");
if(!$is_check){
	echo $m_langpackage->m_no_pri;
	exit;
}
//语言包引入
$t_langpackage=new toollp;
$er_langpackage=new errorlp;
$ad_langpackage=new adminmenulp;

$xmlDom=new DomDocument;
$serv_url=list_substitue("tools");
$client_url="toolsBox/tool.xml";
$is_show=0;
$serv_content=file_get_contents($serv_url);//取得代理返回数据
if($serv_content){
	if(preg_match("/error\_\d/",$serv_content)){
		$error_str=$er_langpackage->{"er_".$serv_content};//代理程序返回错误
	}else{
		$xml_server=new DOMDocument;
		$xml_server->loadXML($serv_content);//解析服务器的xml
		$serve_root=$xml_server->getElementsByTagName('toolbox')->item(0);
		$serve_node=$xml_server->getElementsByTagName('tool_item');
		$client_content=file_get_contents($client_url);
		$xml_client=new DOMDocument;
		$xml_client->loadXML($client_content);//解析客户端的xml
		$client_node=$xml_client->getElementsByTagName('tool_item');
		$xpath=new DOMXpath($xml_server);
		foreach($client_node as $val){
			$id=$val->getAttribute('id');
			$element = $xpath->query("//tool_item[@id='$id']");
			if($element->length > 0){
				$serve_root->removeChild($element->item(0));
			}
		}
		if(trim($xml_server->getElementsByTagName('toolbox')->item(0)->nodeValue)==''){//是否有可以下载的组件
			$error_str=$t_langpackage->t_download_none;
		}else{
			$is_show=1;
			$tool_item=$xml_server->getElementsByTagName('tool_item');
		}
	}
}else{
	$error_str=$t_langpackage->t_get_false;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
</head>
<script type='text/javascript'>
	function download_action(tool_name){
		var diag = new parent.Dialog();
		diag.Width = 300;
		diag.Height = 150;
		diag.Modal = false;
		diag.Title = "工具下载";
		diag.InnerHtml="<img src='images/loading.gif' style='vertical-align:middle;margin:5px;' />正在下载...";
		diag.show();
		window.location.href="download_tool.action.php?id="+tool_name;
	}
</script>
<body>
<div id="maincontent">
  <div class="wrap">
    <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_tools;?></a> &gt;&gt; <a href="download_tool_list.php"><?php echo $ad_langpackage->ad_tools_download;?></a></div>
    <hr />
<div class="infobox">
    <h3><?php echo $t_langpackage->t_list;?></h3>
    <div class="content">
<?php if($is_show==1){?>
	<table class="list_table">
		<thead><tr>
        	
                <th><?php echo $t_langpackage->t_name;?></th>
                <th><?php echo $t_langpackage->t_code_num;?></th>
                <th><?php echo $t_langpackage->t_time;?></th>
                <th><?php echo $t_langpackage->t_author;?></th>
                <th><?php echo $t_langpackage->t_ctrl;?></th>
            
		</tr></thead>
	<?php
	foreach($tool_item as $rs){
	?>
		<tr>
			<td><?php echo $rs->getElementsByTagName('toolName')->item(0)->nodeValue;?></td>
			<td><?php echo $rs->getAttribute("id");?></td>
			<td><?php echo $rs->getElementsByTagName('date')->item(0)->nodeValue;?></td>
			<td><?php echo $rs->getElementsByTagName('author')->item(0)->nodeValue;?></td>
			<td><a href='javascript:download_action("<?php echo $rs->getAttribute("id");?>");' onclick='return confirm("<?php echo $t_langpackage->t_ask_download;?>")'><?php echo $t_langpackage->t_download;?></a></td>
		</tr>
	<?php }?>
	</table>
	
<?php }else{?>

	<table class="list_table">
		<tr>
			<td><?php echo $error_str;?></td>
		</tr>
	</table>

<?php }?>
</div>
</div>

<div class="infobox">
    <h3><?php echo $t_langpackage->t_tip_inf;?></h3>
    <div class="content">
	<table class="list_table">
		<tr>
			<td><?php echo $t_langpackage->t_download_pro_1;?></td>
		</tr>
		<tr>
			<td><?php echo $t_langpackage->t_download_pro_2;?></td>
		</tr>
		<tr>
			<td><?php echo $t_langpackage->t_download_pro_3;?></td>
		</tr>		
	</table>
	</div>
</div>
</body>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<style>
*{font-size:12px;}
h3{margin:6px 0 0 0;padding:0}
</style>
</head>
<body>
<?php
require_once(dirname(__file__)."/../../foundation/fgetandpost.php");
require_once(dirname(__file__)."/../../foundation/cxmloperator.class.php");
$xmlpath="resources/front_resources.xml";
$xml=new XMLOperator($xmlpath);
if(get_args('addgroup'))
{
	$id=get_args('id');
	$value=get_args('value');
	if($id && $value && !$xml->query("//group[@id='$id']"))
	{
		$xml->addNode("/resources","group","","id=$id;value=$value");
	}
	$xml->save($xmlpath);
}
else if(get_args('updgroup'))
{
	$id=get_args('id');
	$value=get_args('value');	
	$xml->updAttr("//group[@id='$id']","value",$value);
	$xml->save($xmlpath);
}
$groups=$xml->query("//group");
if(get_args('submit'))
{
	$group_id=get_args('group');
	$id=get_args('id');
	$value=get_args('value');	
	if(get_args('op')=='upd')
	{
		if(!$xml->query("//group[@id='$group_id']/resource[@id='$id']"))
		{
			$xml->delNode("//resource[@id='$id']");
			$xml->addNode("//group[@id='$group_id']","resource","","id=$id;value=$value");
		}else
		{
			$xml->updAttr("//resource[@id='$id']","value",$value);
		}
		$xml->save($xmlpath);
	}
	else
	{
		if($id && $value && strlen($id)==3)
		{
			if(!$xml->query("//resource[@id='$id']"))
			{
				if($xml->addNode("//group[@id='$group_id']","resource","","id=$id;value=$value"))$xml->save($xmlpath);
			}
			else
			{
				echo "资源ID已经存在！";
			}
		}
		else
		{
			echo "资源ID和资源名称不能为空，且资源ID长度为3";
		}
	}
}

$select="<select id='group' name='group'>";
$show="";
if($groups)
{
	foreach($groups as $group)
	{
		$id=$group->getAttributeNode("id")->value;
		$value=$group->getAttributeNode("value")->value;
		$show.="<h3>{$value}[<a href='#' onclick='add(\"$id\")'>添加</a> <a href='#' onclick='updgroup(\"$id\",\"$value\")'>修改</a>]</h3><hr/>";
		$select.="<option value='$id'>$value</option>";
		$resources=$xml->query("/resources/group[@id='$id']/resource");	
		if($resources && $resources->length>0)
		{
			foreach($resources as $resource)
			{
				$rid=$resource->getAttributeNode("id")->value;
				$rvalue=$resource->getAttributeNode("value")->value;
				$show.= "$rvalue <a href='#' onclick='update(\"$rid\",\"$rvalue\",\"$id\")'>修改</a> ";
			}
		}
	}
}
$select.="</select>";
echo "<form id='form' method='post'>权限分组:{$select}<a href='#'onclick='addgroup()'>添加分组</a><span id='resource'>权限ID：<input name='id' value=''>权限名称：<input name='value'/><input type='submit' name='submit' value='添加'/></span></form> $show";
?>
<script>
function update(id,value,gid)
{
	document.getElementById("resource").innerHTML="权限ID：<input name='id' value='"+id+"' readonly='readonly' />权限名称：<input name='value' value='"+value+"'/><input type='hidden' name='op' value='upd'/><input type='submit' name='submit' value='修改'/>";
	SelectItem(document.getElementById('group'),gid);
}
function add(gid)
{
	document.getElementById("resource").innerHTML="权限ID：<input name='id' value='' />权限名称：<input name='value' value=''/><input type='submit' name='submit' value='添加'/>";
	SelectItem(document.getElementById('group'),gid);
}
function addgroup()
{
	document.getElementById("resource").innerHTML="组ID：<input name='id' value='' />组名称：<input name='value' value=''/><input type='submit' name='addgroup' value='添加'/>";
}
function updgroup(id,value)
{
	document.getElementById("resource").innerHTML="组ID：<input name='id' value='"+id+"'  readonly='readonly'/>组名称：<input name='value' value='"+value+"'/><input type='submit' name='updgroup' value='修改'/>";
}
function SelectItem(objSelect, objItemText)
{
	var isExit = false;
	for (var i = 0; i < objSelect.options.length; i++)
	{
		if (objSelect.options[i].value == objItemText || objSelect.options[i].text == objItemText)
		{
			objSelect.options[i].selected = true;
			break;
		}
	}
}
</script>
</body>
</html>

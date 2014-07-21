<?php
require("session_check.php");
$is_check=check_rights("f03");
if(!$is_check){
	echo $m_langpackage->m_no_pri;
	exit;
}
//语言包引入
$t_langpackage=new toollp;
$tool_id=get_argg('id');
$client_url="toolsBox/tool.xml";
if($tool_id==''){
	echo $t_langpackage->t_code_wrong;exit;
}
$xml_client=new DOMDocument;
$xml_client->load("$client_url");
$xpath=new DOMXpath($xml_client);
$element = $xpath->query("//tool_item[@id='$tool_id']");
$file_list=$element->item(0)->getElementsByTagName("programList");

foreach($file_list as $rs){
	$file_url=$rs->nodeValue;
	unlink('toolsBox/'.$tool_id."/".$file_url);
}
rmdir('toolsBox/'.$tool_id);
$xml_client->getElementsByTagName('toolbox')->item(0)->removeChild($element->item(0));
$write_num=$xml_client->save($client_url);
if($write_num>0){
	echo '<script type="text/javascript">alert("'.$t_langpackage->t_unload_sucess.'");window.history.go(-1);</script>';
}else{
	echo '<script type="text/javascript">alert("'.$t_langpackage->t_unload_false.'");window.history.go(-1);</script>';
}
?>

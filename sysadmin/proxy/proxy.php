<?php
//授权码
$authorization_code=file_get_contents("authorization/code.txt");
//查看代理
$list_substitue="http://tech.jooyea.com/check_code/show_list.php?prod=sns";
//更新代理
$action_substitue="http://tech.jooyea.com/check_code/check_id.php?prod=sns";

function list_substitue($type,$que_str=''){
	global $list_substitue;
	global $authorization_code;
	return $list_substitue."&empower_id=".$authorization_code."&show_mod=".$type.$que_str;
}

function act_substitue($type,$que_str=''){
	global $action_substitue;
	global $authorization_code;
	return $action_substitue."&empower_id=".$authorization_code."&update_mod=".$type.$que_str;
}

?>
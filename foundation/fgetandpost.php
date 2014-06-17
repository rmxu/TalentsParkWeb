<?php
//对表单的统一处理
function get_args($name)
{
	if(isset($_POST[$name]))return $_POST[$name];
	if(isset($_GET[$name]))return $_GET[$name];
	return null;
}

//get参数处理
function get_argg($name){
	if(isset($_GET[$name]))return $_GET[$name];
	return null;
}

//post参数处理
function get_argp($name){
	if(isset($_POST[$name]))return $_POST[$name];
	return null;
}
?>

<?php
function Root()
{
	$base=$_SERVER['DOCUMENT_ROOT'];
	$root=strtr(dirname(__file__),"\\","/");
	$website=str_replace($base,"",$root);
	return $website;
}
?>
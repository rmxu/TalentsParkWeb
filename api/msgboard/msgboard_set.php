<?php
include(dirname(__file__)."/../includes.php");

function msgboard_set($to_user_id)
{
	global $tablePreStr;
	$dbo=new dbex;
	dbplugin('w');
	$t_message=$tablePreStr."msgboard";
	$sql="update $t_message set readed=1 where to_user_id='$to_user_id'";
	$dbo->exeUpdate($sql);
}
?>
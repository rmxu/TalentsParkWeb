<?php
require("session_check.php");
require("toolsBox/clear_test/ftool_clearTestData.php");
$uid=intval(get_argg('user_id'));
$t_langpackage=new toollp;
$dbo = new dbex;
dbtarget('w',$dbServs);
if($uid){
	echo del_user($dbo,$uid);
}
?>
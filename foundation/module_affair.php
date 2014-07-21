<?php
function del_affair($dbo,$mod_type,$mod_id){
	global $tablePreStr;
	$t_affair_table=$tablePreStr."recent_affair";
	$sql="delete from $t_affair_table where mod_type=$mod_type and for_content_id=$mod_id";
	$dbo->exeUpdate($sql);
}
?>
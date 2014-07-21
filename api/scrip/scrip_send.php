<?php
include(dirname(__file__)."/../includes.php");
function scrip_send($sender,$title,$content,$to_id,$scrip_id=''){
	global $tablePreStr;
	$uid=get_sess_userid();
	$uico=get_sess_userico();
	$t_scrip=$tablePreStr."msg_inbox";
	$dbo=new dbex;
  dbplugin('w');
	$sql="insert into $t_scrip (mess_title,mess_content,from_user,from_user_ico,user_id,add_time,from_user_id,mesinit_id)"
	                    ."value('$title','$content','$sender','$uico',$to_id,NOW(),$uid,'$scrip_id')";
  return $dbo->exeUpdate($sql);
}
?>
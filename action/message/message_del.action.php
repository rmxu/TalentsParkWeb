<?php
require("api/base_support.php");
//变量定义区
$mess_id=intval(get_argg('id'));
//数据表定义
api_proxy("message_set_del","remind",$mess_id);
$remind_rs=api_proxy("message_get","remind",1,5);

foreach($remind_rs as $rs){
echo "<li id='remind_".$rs['id']."'>
				<div class='photo'><a href='home.php?h=".$rs['from_uid']."' target='_blank'><img src='".$rs['from_uico']."' width='20px' height='20px' alt='' target='_blank' /></a></div>
					<div class='remind_content'>
						<a href='home.php?h=".$rs['from_uid']."' target='_blank'>".$rs['from_uname']."</a>";
		echo	str_replace(array('{link}','{js}'),array($rs['link'],"clear_remind(".$rs['id'].")"),filt_word($rs['content']));
		echo "</div>
				<div class='remind_del'><a href='javascript:clear_remind(".$rs['id'].")'></a></div>
			</li>";
}
?>
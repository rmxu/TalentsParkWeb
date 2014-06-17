<?php
$ua_langpackage = new userapplp;
require("api/base_support.php");
$id=intval(get_argg('id'));
$is_affair=intval(get_argp('is_affair'));
$app_rs=api_proxy("plugins_get_pid",$id);
$def_small_image="skin/".$skinUrl."/images/plu_def_small.gif";
$small_url=$app_rs['image']?preg_replace("/([^\.]+)(\.\w+)/","$1_small$2","plugins/".$app_rs['name']."/".$app_rs['image']):$def_small_image;
if($id){
	api_proxy("plugins_set_mine",$id);
	if($is_affair==1){
		$title=$ua_langpackage->ua_add_new_app;
		$content='<a href="main.php?app=add_app&id='.$app_rs['id'].'" target="_blank">'.$app_rs['title'].'</a>';
		api_proxy("message_set",0,$title,$content,0,8);
	}
}
action_return(1,'',-1);
?>
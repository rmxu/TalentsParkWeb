<?php
require("api/base_support.php");
$id=intval(get_argg('id'));
if($id){
	api_proxy("plugins_set_mine",$id,1);
}
action_return(1,'',-1);
?>
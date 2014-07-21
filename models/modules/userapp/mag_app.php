<?php
require("api/base_support.php");
//引入语言包
$pl_langpackage=new pluginslp;
$app_rs=api_proxy("plugins_get_mine");
$def_small_image="skin/".$skinUrl."/images/plu_def_small.gif";
?>
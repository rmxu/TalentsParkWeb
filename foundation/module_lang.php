<?php
function show_index_lp($def_lp){
echo "<select onchange='set_cookie_lp(this.value)' style='margin-top:5px;margin-left:10px;'>";
$res=opendir("langpackage");
while($lp_dir=readdir($res)){
	if(!preg_match("/^\./",$lp_dir)){
		$l_selected='';
		if($lp_dir==$def_lp){$l_selected="selected";}
		$lp_tip=trim(file_get_contents("langpackage"."/".$lp_dir."/"."tip.php"));
		if($lp_tip!=''){
			echo "<option value='".$lp_dir."' ".$l_selected.">".$lp_tip."</option>";
		}
	}
}
echo "</select>";	
}

function show_back_lp($def_lp){
echo "<select name='change_lp'>";
$res=opendir("../langpackage");
while($lp_dir=readdir($res)){
	if(!preg_match("/^\./",$lp_dir)){
		$l_selected='';
		if($lp_dir==$def_lp){$l_selected="selected";}
		$lp_tip=trim(file_get_contents("../langpackage"."/".$lp_dir."/"."tip.php"));
		if($lp_tip!=''){
			echo "<option value='".$lp_dir."' ".$l_selected.">".$lp_tip."</option>";
		}
	}
}
echo "</select>";	
}
?>
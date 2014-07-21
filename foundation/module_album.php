<?php
	//引入语言包
	$alb_langpackage=new albumlp;
	$a_cho=$alb_langpackage->a_cho;
	$a_all=$alb_langpackage->a_all;
	$a_fri=$alb_langpackage->a_fri;
	$a_myself=$alb_langpackage->a_myself;

function album_name($album_rs,$album_id){
	global $a_cho;
    echo '<select name="album_name" id="album_name" onchange=document.getElementById("album_ufor").value=this.options[selectedIndex].text;>'
            .'<option value="">--'.$a_cho.'--</option>';
				foreach($album_rs as $val){
					echo"<option value=".$val['album_id'];
					if($val['album_id']==$album_id){
						echo " selected ";
					}
					echo ">$val[album_name]</option>";
				}
   	echo '</select>';
}

function holder_name($is_self,$user_id,$holder_name){
	if($is_self=='N'){
		echo "<a target='_blank' href='home.php?h=".$user_id."'>".$holder_name."</a><br>";
	}
}

function upd_del($is_self,$album_id,$a_edit,$del_asc,$a_del){
	if($is_self=='Y'){
		echo "<a href='modules.php?app=album_edit&album_id=".$album_id."'>".$a_edit."</a>
		        <a href='do.php?act=album_del&album_id=".$album_id."' onclick=\"return confirm('.$del_asc.')\";>".$a_del."</a>";
	}
}

function src_wh($size0,$size1,$photo_thumb_src){

	if($size0>$size1){
		echo "<img src='".$photo_thumb_src."' width='90' >";
	}else{
		echo "<img src='".$photo_thumb_src."' height='90' >";
	}
}

function do_url($type,$photo_src,$user_id){
	global $siteDomain;
	$do_url="javascript:window.location.href='modules.php?app=user_ico_cut&photo_url=".$photo_src."'";
	if($type!='' && $type=="blog_photo"){
		$do_url="javascript:AddContentImg('".$photo_src."',0);";
	}
	if($type!='' && $type=="index_pic"){
		$do_url="javascript:window.location.href='".$siteDomain."sysadmin/pic_cut.php?photo_url=".$photo_src."&user_id=".$user_id."'";
	}
	return $do_url;
}
?>

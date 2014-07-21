<?php
//$lim_message='';//可重定义限制访问返回消息
//$lim_rdurl='';//可重定义跳转url

//引入语言包
$ref_langpackage=new gobacklp;
$lim_mess='';
$lim_message='';
if($lim_message!=''){
	 $lim_mess=$lim_message;
}else{
   $lim_mess=$ref_langpackage->ref_see_popedom;
}

if($is_login_mode=='accessLimit'){
	if(!get_sess_userid()){
		if($lim_rdurl=="login"){
			echo '<script language=javascript>alert("'.$ref_langpackage->ref_no_land.'");top.location="do.php?act=logout";</script>';
		}else if($lim_rdurl!=""){
			echo '<script language=javascript>top.location="'.$lim_rdurl.'";</script>';
		}
	}
}

$is_self='';
$userid='';
if($is_self_mode=='partLimit'){
	if(($url_uid==''&&$ses_uid!='')||($url_uid!=''&&$url_uid==$ses_uid)){
		$userid=$ses_uid;
		$is_self='Y';
	}else if($url_uid!=''){
		$userid=$url_uid;
		$is_self='N';
	}else{
		echo 'para err';
		exit;
	}
}
?>
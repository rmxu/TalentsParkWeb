<?php
require("session_check.php");
require("proxy/proxy.php");

//语言包引入
$u_langpackage=new uilp;
$er_langpackage=new errorlp;
$downloadName=get_argg('download_name');
$setup_dir=get_argg('setup_dir');
$download_mod=get_argg('download_mod');

switch($download_mod){
	case "tpl":
	$is_check=check_rights("b12");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	$serv_url=act_substitue("tpl","&folder=".$downloadName);//远程模板代理地址
	$base_dir="../templates/".$setup_dir."/";
	break;
	case "skin":
	$is_check=check_rights("b14");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	$serv_url=act_substitue("skin","&folder=".$downloadName);//远程模板代理地址
	$tpl_dir=preg_replace('/\/\w*/',"",$skinUrl);
	$base_dir="../skin/".$tpl_dir."/".$setup_dir."/";
	break;
	default:
	echo 'error';exit;
	break;
}

if($downloadName==''){
	echo '<script type="text/javascript">alert("'.$u_langpackage->u_none.'");window.history.go(-1);</script>';exit;
}
if($setup_dir==''){
	echo '<script type="text/javascript">alert("'.$u_langpackage->u_none_url.'");window.history.go(-1);</script>';exit;
}
if(file_exists($base_dir)){
	echo '<script type="text/javascript">alert("'.$base_dir.$u_langpackage->u_dir_used.'");window.history.go(-1);</script>';exit;
}
$download_info=file_get_contents($serv_url);//取得代理返回的数据
if(preg_match("/error\_\d/",$download_info)){
	echo '<script type="text/javascript">alert("'.$er_langpackage->{"er_".$download_info}.'");window.history.go(-1);</script>';exit;
}
mkdir($base_dir);//创建下载目录

//目录创建
preg_match_all("/<dir>[\w\/]+<\/dir>/",$download_info,$download_dir);
foreach($download_dir[0] as $val){
	$val=str_replace(array("<dir>","</dir>"),"",$val);
	if(!file_exists($base_dir.$val)){
		mkdir($base_dir.$val);
		echo str_replace("{dir}",$base_dir.$val,$u_langpackage->u_dir_suc);
	}
}
//文件下载
preg_match_all("/<file>[\w\/\.]+<\/file>/",$download_info,$file_name);
foreach($file_name[0] as $rs){
	$rs=str_replace(array("<file>","</file>"),"",$rs);
	$file_content=file_get_contents($serv_url."&fileName=".$rs);
	if(preg_match("/error\_\d/",$file_content)){
		echo $er_langpackage->{"er_".$file_content}."<br />";
	}else{
		$f_ref=fopen($base_dir."/".$rs,"w+");
		$write_num=fwrite($f_ref,$file_content);
		if($write_num==0){
			echo $u_langpackage->u_down_false;
		}else{
			echo str_replace("{file}",$base_dir.$rs,$u_langpackage->u_file_suc);
		}
	}
}
echo '<script>parent.Dialog.close();</script>';
?>
<?php
require("session_check.php");
$is_check=check_rights("e02");
if(!$is_check){
	echo $m_langpackage->m_no_pri;
	exit;
}
require("proxy/proxy.php");
//语言包引入
$so_softwarelp=new softwarelp;
$er_langpackage=new errorlp;
$version_url="../docs/version.txt";
$whole_version=file_get_contents($version_url);
$version_num=short_check(get_argg('version'));
$serv_url=act_substitue("software","&update_version=".$version_num."&version=".$whole_version);
$update_data=file_get_contents($serv_url);
$xmldom=new DomDocument;
$xmldom->loadXML($update_data);
$dir_array=$xmldom->getElementsByTagName('dir');//取得目录列表
$file_array=$xmldom->getElementsByTagName('file');//取得文件列表
$del_array=$xmldom->getElementsByTagName('del');//取得删除列表
$sql_str=$xmldom->getElementsByTagName('update_sql');//取得sql列表
$is_success=1;//状态
//升级sql
if($sql_str->length){
	$queries=explode(";\n",$sql_str->item(0)->nodeValue);
	$dbo = new dbex;
	dbtarget('w',$dbServs);
	foreach($queries as $query){
		$query=str_replace("isns_",$tablePreStr,$query);
		if(substr($query,-1)!=";"){
			$query.=";";
		}
		$result=$so_softwarelp->so_success;
		if(!$dbo->exeUpdate($query)){
			$result=$so_softwarelp->so_false;
			$is_success=0;
		}
		echo str_replace("{result}",$result,$so_softwarelp->so_sql_result);
	}
}
//升级目录
foreach($dir_array as $dir){
	if(!file_exists("../".$dir->nodeValue)){
		$result=$so_softwarelp->so_success;
		if(!mkdir("../".$dir->nodeValue)){
			$result=$so_softwarelp->so_false;
			$is_success=0;
		}
		echo str_replace(array("{result}","{dir}"),array($result,$dir->nodeValue),$so_softwarelp->so_dir_result);
	}
}
//升级程序
foreach($file_array as $file){
	$download_file=file_get_contents($serv_url."&fileName=".$file->nodeValue);
	if(file_exists('../'.$file->nodeValue)){
		copy('../'.$file->nodeValue,'../docs/bak/'.$file->nodeValue);
	}
	$f_ref=fopen("../".$file->nodeValue,"w+");
	$result=$so_softwarelp->so_success;
	if(!fwrite($f_ref,$download_file)){
		$result=$so_softwarelp->so_false;
		$is_success=0;
	}
	echo str_replace(array("{result}","{file}"),array($result,$file->nodeValue),$so_softwarelp->so_download_result);
}
//删除废旧文件
foreach($del_array as $del){
	if(file_exists("../".$del->nodeValue)){
		if(is_dir("../".$del->nodeValue)){
			$result=$so_softwarelp->so_false;
			if(rmdir("../".$del->nodeValue)){
				$result=$so_softwarelp->so_success;
			}
			echo str_replace(array("{result}","{dir}"),array($result,$del->nodeValue),$so_softwarelp->so_del_dir_result);
		}else{
			$result=$so_softwarelp->so_false;
			if(unlink("../".$del->nodeValue)){
				$result=$so_softwarelp->so_success;
			}
			echo str_replace(array("{result}","{file}"),array($result,$del->nodeValue),$so_softwarelp->so_del_file_result);
		}
	}
}
//更新本地版本号
if($is_success==1){
	$f_ref=fopen($version_url,"w+");
	$num=fwrite($f_ref,$version_num);
	if($num>0){
		echo $so_softwarelp->so_update_success;
	}
}else{
	echo $so_softwarelp->so_update_false;
}
echo '<script>parent.Dialog.close();</script>';
?>
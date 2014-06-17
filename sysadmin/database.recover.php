<?php 
require("session_check.php");
	$is_check=check_rights("g02");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//语言包引入
	$d_langpackage=new datalp;
	$ad_langpackage=new adminmenulp;
	
	$filedb=array();
	$handle=opendir('../docs');
	while($file = readdir($handle)){
		if(preg_match("/^isns_/i",$file) && preg_match("/\.sql$/i",$file)){
			$strlen=preg_match("/^isns_/i",$file) ? 16 + strlen("isns_") : 19;
			$fp=fopen("../docs/$file",'rb');
			$bakinfo=fread($fp,200);
			fclose($fp);
			$detail=explode("\n",$bakinfo);
	 		$bk['name']=$file;
			$bk['version']=substr($detail[1],10);
			$bk['time']=substr($detail[2],8);
			$bk['pre']=substr($file,0,$strlen);
			$bk['num']=substr($file,$strlen,strrpos($file,'.')-$strlen);
			$filedb[]=$bk;
		}
	}
	//导入备份文件
	$operation	= get_argg('operation');
	if($operation=='bakin'){
		$is_check=check_rights("g04");
		if(!$is_check){
			echo $m_langpackage->m_no_pri;
			exit;
		}
		$dbo = new dbex;
		dbtarget('w',$dbServs);
		
		$pre	=get_argg('pre');
		bakindata($dbo,'../docs/'.$pre.'_1.sql');
		echo "<script language='javascript'> alert('$d_langpackage->d_lead_suc'); location.href='database.save.php'; </script>";
	}
	//删除备份文件
	$fid=get_argg('fid');
	if($fid){
		$is_check=check_rights("g05");
		if(!$is_check){
			echo $m_langpackage->m_no_pri;
			exit;
		}
		$file_pre=$fid."_1.sql";
		delfile('../docs/'.$file_pre);
		echo "$d_langpackage->d_del_suc";exit;
	}
function delfile($filename,$check=1){
	@chmod ($filename, 0777);
	return @unlink($filename);
}
function bakindata($dbo,$filename) {
	$sql=file($filename);
	$query='';
	$num=0;
	foreach($sql as $key => $value){
		$value=trim($value);
		if(preg_match("/\;$/i",$value)){
			$query.=$value;
			$dbo->exeUpdate($query);
			$query='';
		} else{
			$query.=$value;
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
<script type='text/javascript' src='../servtools/ajax_client/ajax.js'></script>
<title></title>
</head>
<script language='javascript'>
function del_file(file_id)
{
var del_file=new Ajax();
del_file.getInfo("database.recover.php","GET","app","fid="+file_id,function(c){document.getElementById("operate_"+file_id).innerHTML=c;document.getElementById("d_operate_"+file_id).innerHTML='';}); 
}
</script>
<body>
<div id="maincontent">
    <div class="wrap">
        <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_tools;?></a> &gt;&gt; <a href="database.recover.php"><?php echo $ad_langpackage->ad_restore_data;?></a></div>
        <hr />
<div class="infobox">
    <h3><?php echo $d_langpackage->d_db_cback;?></h3>
    <div class="content">
<form action="?operation=post" method="post">
    <TABLE class="list_table">
      <thead><tr>
        <th style="width:60px;"><?php echo $d_langpackage->d_db_id?></th>
        <th><?php echo $d_langpackage->d_file_name?></th>
        <th><?php echo $d_langpackage->d_edition?></th>
        <th><?php echo $d_langpackage->d_backup_time?></th>
        <th><?php echo $d_langpackage->d_sub_volume?></th>
        <th width='15%'><?php echo $d_langpackage->d_lead?></th>
        <th style="width:60px;"><?php echo $d_langpackage->d_del?></th>
        
      </tr></thead>
      <?php foreach($filedb as $n=>$file){?>
      <tr>
      	
        <td><?php echo $n+1;?></td>
        <td><?php echo $file['name'];?></td>
        <td><?php echo $file['version'];?></td>
        <td><?php echo $file['time'];?></td>
        <td><?php echo $file['num'];?></td>
        <td>
        	<div id=operate_<?php echo $file['pre'];?>>
        		<a href="?operation=bakin&pre=<?php echo $file['pre']?>" onclick='return confirm("<?php echo $d_langpackage->d_lead_con?>");'><?php echo $d_langpackage->d_lead?></a>
       		</div>
        </td>
        <td>
        	<div id=d_operate_<?php echo $file['pre'];?>>
	        	<a href='javascript:del_file(<?php echo "\"".$file['pre']."\"";?>);' title="<?php echo $d_langpackage->d_del?>" alt="<?php echo $d_langpackage->d_del?>" onclick='return confirm("<?php echo $d_langpackage->d_del_con?>");'><img src='images/del.gif' /></a>
        	</div>
        </td>
      </tr> 
      <?php }?>
    </table>
</form>
</div>
</div>
</div>
</div>
</body>
</html>
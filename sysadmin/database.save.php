<?php 
	require("session_check.php");
	$is_check=check_rights("g01");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	date_default_timezone_set("Asia/shanghai");

	//语言包引入
	$d_langpackage=new datalp;
	$ad_langpackage=new adminmenulp;
	
	$operation	= get_argg('operation');
	$dbo = new dbex;
	dbtarget('r',$dbServs);	
	if($operation=='savebackup'){
		$is_check=check_rights("g03");
		if(!$is_check){
			echo $m_langpackage->m_no_pri;
			exit;
		}
		$tabledb	= get_argp('tabledb');
		$start		= (int)get_argg('start');
		$tableid	= (int)get_argg('tableid');
		
		$bak="/* iweb_sns Backup SQL File \n Version: v1.0.0 \n Time: ".date('Y-m-d H:i:s')."\n iweb_sns: http://www.jooyea.net*/\n\n\n\n";
		
		$bakupdata=bakupdata($dbo,$tabledb,$start);
	
		if(!$tabledb){
			echo "<script language='javascript'> alert('$d_langpackage->d_boject_no'); history.go(-1);</script>";
		}
		$step=1;
		$rand_num=num_rand(10);
		$start=0;
		$bakuptable=bakuptable($dbo,$tabledb);
		
		$f_num=ceil($step/2);
		$filename='isns_'.date('m-d').'_'.$rand_num.'_'.$f_num.'.sql';
		$step++;
		$writedata=$bakuptable ? $bakuptable.$bakupdata : $bakupdata;
	
		$c_n	= $startfrom;
		trim($writedata) && writefile('../docs/'.$filename,$bak.$writedata,true,'ab');
		if($step>1){
			for($i=1;$i<=$f_num;$i++){
				$temp=substr($filename,0,19).$i.".sql";
				if(file_exists("../docs/$temp")){
					$bakfile.='<a href="'."../docs/$temp".'">'.$temp.'</a><br>';
				}
			}
		}
		echo "<script language='javascript'> alert('$d_langpackage->d_backup_suc'); location.href='database.recover.php'; </script>";
	}else{
		$sql="show tables from $db";
		$row=$dbo->getRs($sql);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
<title></title>
</head>
<script language="JavaScript" type="text/JavaScript">
function checkAll(form, name) {
	for(var i = 0; i < form.elements.length; i++) {
		var e = form.elements[i];
		if(e.name.match(name)) {
			e.checked = form.elements['chkall'].checked;
		}
	}
}
</script>
<body>

<div id="maincontent">
	<div class="wrap">
  	<div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_tools;?></a> &gt;&gt; <a href="database.save.php"><?php echo $ad_langpackage->ad_backup_data;?></a></div><hr />
		<div class="infobox">
    	<h3><?php echo $d_langpackage->d_db_backup;?></h3>
    	<div class="content">
				<form action="?operation=savebackup" method="post">	
				    <TABLE class="list_table">
				    	<thead>
				      <tr>
				        <th width="50px"><?php echo $d_langpackage->d_choice;?></th>
				        <th width="50px"><?php echo $d_langpackage->d_db_id;?></th>
				        <th><?php echo $d_langpackage->d_db_table;?></th>
				     </tr>
				   	</thead>
				      <?php
				      foreach($row as $n => $table){
				      ?>
				      <tr>
				        <td><input type="checkbox" class="checkbox" name="tabledb[]" value="<?php echo $table[0];?>" /></td>
				        <td><?php echo $n+1;?></td>
				        <td><?php echo $table[0];?></td>
				      </tr>
				      <?php 
				      }
				      ?>
				      <tr>
				        <td><input type="checkbox" name="chkall" id="chkall" onclick="checkAll(this.form, 'tabledb')" />
				          <label for="chkall"><?php echo $d_langpackage->d_check_all?></label></td>
				        <td colspan="2">
				          <div class="fixsel"> <input type="submit" class="regular-button" name="forumlinksubmit" value="<?php echo $d_langpackage->d_refer?>"  /> </div></td>
				      </tr>
				    </table>
				</form>
			</div>
		</div>
	</div>
</div>

</body>
</html>
<?php 
function writefile($filename,$data,$check=1,$method="rb+",$iflock=1,$chmod=1){
	$check && strpos($filename,'..')!==false;
	touch($filename);
	$handle=fopen($filename,$method);
	if($iflock){
		flock($handle,LOCK_EX);
	}
	fwrite($handle,$data);
	if($method=="rb+") ftruncate($handle,strlen($data));
	fclose($handle);
	$chmod && @chmod($filename,0777);
}

function num_rand($lenth){
	$randval='';
	mt_srand((double)microtime() * 1000000);
	for($i=0;$i<$lenth;$i++){
		$randval.= mt_rand(0,9);
	}
	$randval=substr(md5($randval),mt_rand(0,32-$lenth),$lenth);
	return $randval;
}

function bakupdata($dbo,$tabledb,$start=0){
	$bakupdata='';
	global $sizelimit,$tableid,$startfrom,$stop,$rows;
	$tableid=$tableid?$tableid-1:0;
	$stop=0;
	$t_count=count($tabledb);
	for($i=$tableid;$i<$t_count;$i++){
		$sql="SELECT * FROM $tabledb[$i]";
		$rs = $dbo->getRs($sql);
		foreach($rs as $key=>$val){
			$num_F = count($val)/2;
			$start++;
			$table=$tabledb[$i];
			$bakupdata .= "INSERT INTO $table VALUES("."'".addslashes($val[0])."'";
			$tempdb='';
			for($j=1;$j<$num_F;$j++){
				$tempdb.=",'".addslashes($val[$j])."'";
			}
			$bakupdata .=$tempdb. ");\n";
			if($sizelimit && strlen($bakupdata)>$sizelimit*1000){
				break;
			}
		}

		if($start>=$rows){
			$start=0;
			$rows=0;
		}

		$bakupdata .="\n";
		if($sizelimit && strlen($bakupdata)>$sizelimit*1000){
			$start==0 && $i++;
			$stop=1;
			break;
		}
		$start=0;
	}

	if($stop==1){
		$i++;
		$tableid=$i;
		$startfrom=$start;
		$start=0;
	}
	return $bakupdata;
}

function bakuptable($dbo,$tabledb){
	global $tableid;
	$creattable='';
	$tableid=$tableid?$tableid-1:0;
	$t_count=count($tabledb);
	for($i=$tableid;$i<$t_count;$i++){
		$creattable.= "DROP TABLE IF EXISTS $tabledb[$i];\n";
		$CreatTable = $dbo->create("SHOW CREATE TABLE $tabledb[$i]");
		$creattable.=$CreatTable['Create Table'].";\n\n";
	}
	return $creattable;
}
?>
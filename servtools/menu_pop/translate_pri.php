<?php
$getpost_power=true;
$iweb_power=true;
require(dirname(__FILE__)."/../../api/base_support.php");
$dbo=new dbex;
dbtarget('r',$dbServs);

$table_name=get_argp('t_name');
$v_id=get_argp('vid');
$etem=get_argp('tem');
$cols_array=array(
	$tablePreStr."blog"=>"log_id",
	$tablePreStr."photo"=>"photo_id",
	$tablePreStr."album"=>"album_id",
);
$cols_name=$cols_array[$table_name];
$sql="update $table_name set privacy='$etem' where $cols_name=$v_id ";
if($dbo->exeUpdate($sql)){
	echo 'success';
}else{
	echo 'false';
}
?>
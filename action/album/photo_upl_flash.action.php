<?php
//引入模块公共方法文件
require("foundation/aintegral.php");
require("api/base_support.php");

//引入语言包
$a_langpackage=new albumlp;

//变量定义区
$album_id=intval(get_argg('id'));
$session_code=get_argp('sess_code');
$success = "false";
$data ='';
$data_ser='';
$affair_ser='';
$code_array=array();
$errors = array();
$album_row=array();
$fs=array();
$data_array=array();
$affair_array=array();
$session_data=array();

//数据表
$t_photo = $tablePreStr."photo";
$t_album = $tablePreStr."album";
$t_online = $tablePreStr."online";
$t_tmp_file = $tablePreStr."tmp_file";

$dbo = new dbex;
//读写分离定义函数
dbtarget('r',$dbServs);

//验证用户权限
if(empty($session_code)){
	$success = "false";
	exit;
}

$code_array=explode("|",$session_code);
$sql="select user_id,user_name,user_ico,session_code from $t_online where user_id=$code_array[1]";
$user_row=$dbo->getRow($sql);
if(empty($user_row)){
	$success = "false";exit;
}

if($user_row['session_code']!=$code_array[0]){
	$success = "false";exit;
}

$user_id=$user_row['user_id'];
$user_name=$user_row['user_name'];
$uico_url=$user_row['user_ico'];

function return_result($success,$errors,$data){
	echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
	echo '<results><success>'.$success.'</success>';
	echo_errors($errors);
	echo $data;
	echo '</results>';
}

function echo_errors($errors){
	for($i=0;$i<count($errors);$i++){
		echo "<error>$errors[$i]</error>";
	}
}

if($_REQUEST['action']=="upload"){
	$base_root="uploadfiles/album/";//图片存放目录
	$up = new upload();
	$up->set_field('file');
	$up->set_dir($base_root,'{y}/{m}/{d}');//目录设置
	$up->set_thumb(180,180); //缩略图设置
	$fs = $up->single_exe();
	$realtxt=$fs;

	if($realtxt['flag']==1){
		dbtarget('w',$dbServs);
		$fileSrcStr=str_replace(dirname(__FILE__),"",$realtxt['dir']).$realtxt['name'];
		$thumb_src=str_replace(dirname(__FILE__),"",$realtxt['dir']).$realtxt['thumb'];
		$sql = "insert into $t_photo(`user_id`,`add_time`,`photo_src`,`photo_thumb_src`,`album_id`,`user_name`,`privacy`)
			                     values($user_id,now(),'$fileSrcStr','$thumb_src',$album_id,'$user_name','');";

		if($dbo -> exeUpdate($sql)){
		  $photo_id=mysql_insert_id();
		  $sql = "update $t_album set photo_num=photo_num+1,update_time=NOW() where album_id=$album_id";
		  $dbo -> exeUpdate($sql);

		//数据清理
			$sql="delete from $t_tmp_file where mod_count=0 and affair_array=NULL";
			$dbo->exeUpdate($sql);

		//取得暂存数据
			$sql="select * from $t_tmp_file where mod_id=$album_id";
			$session_data=$dbo->getRow($sql);
			if(empty($session_data)){
				$mod_count=1;
				//完成时的数据交换
				$fs['photo_id']=$photo_id;
				$data_array[]=$fs;
				//新鲜事的数据交换
				$affair_array[$mod_count]['id']=$photo_id;
				$affair_array[$mod_count]['file']=$thumb_src;
				//序列化
				$data_ser=serialize($data_array);
				$affair_ser=serialize($affair_array);
				//插入库表
				$sql="insert into $t_tmp_file (mod_id,mod_count,affair_array,data_array) values ($album_id,$mod_count,'$affair_ser','$data_ser')";
				$dbo->exeUpdate($sql);
			}else{
				$mod_count=$session_data['mod_count']+1;
				$affair_ser=$session_data['affair_array'];
				$data_ser=$session_data['data_array'];
				//完成时的数据交换
				$data_array=unserialize($data_ser);
				$fs['photo_id']=$photo_id;
				$data_array[]=$fs;
				//新鲜事的数据交换
				$affair_array=unserialize($affair_ser);
				$affair_array[$mod_count]['id']=$photo_id;
				$affair_array[$mod_count]['file']=$thumb_src;
				//序列化
				$data_ser=serialize($data_array);
				$affair_ser=serialize($affair_array);
				//更新库表
				$sql="update $t_tmp_file set mod_count=$mod_count,affair_array='$affair_ser',data_array='$data_ser' where mod_id=$album_id";
				$dbo->exeUpdate($sql);
			}
		  increase_integral($dbo,$int_photo,$user_id);
		  $success = "true";
		}else{
			$success="false";
		}
	}else{
		$success="false";
	}
}
	return_result($success,$errors,$data);
?>

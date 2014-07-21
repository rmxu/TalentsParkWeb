<?php
	require("session_check.php");	
	require("../foundation/aintegral.php");
	$is_check=check_rights("c26");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//语言包引入
	$m_langpackage=new modulelp;
	//判断是否批量删除
	if(get_argp('checkany')){//批量
		$dbo = new dbex;
		dbtarget('r',$dbServs);
		dbtarget('w',$dbServs);
		$photo_ids = get_argp('checkany');
		foreach($photo_ids as $rs){
			//数据表定义区
			$t_album = $tablePreStr."album";
			$t_photo = $tablePreStr."photo";
			$sql = "select * from $t_photo where photo_id=$rs";
			$photo_row = $dbo -> getRow($sql);
			$album_id=$photo_row['album_id'];
			$user_id=$photo_row['user_id'];
			$sql = "select * from $t_album where album_id=$album_id";
			$album_row = $dbo -> getRow($sql);
			if($album_row['album_skin']==$photo_row['photo_thumb_src']){
				$album_skin = 'uploadfiles/album/logo.jpg';
				$sql = "update $t_album set album_skin = '$album_skin' where album_id=$album_id";
				$dbo -> exeUpdate($sql);
			}
			@unlink('../'.$photo_row['photo_src']);
			@unlink('../'.$photo_row['photo_thumb_src']);
			$sql = "delete from $t_photo where photo_id=$photo_row[photo_id]";
			if($dbo -> exeUpdate($sql)){
				increase_integral($dbo,$int_del_photo,$user_id);
			}
			$sql = "update $t_album set photo_num=photo_num-1 where album_id=$album_id";
			$dbo -> exeUpdate($sql);
		}
	}else{
		//变量取得
		if(empty($photo_id)){
			$photo_id=intval(get_argg('pid'));
			$album_id=intval(get_argg('aid'));
			$user_id=intval(get_argg('uid'));
		}
		//数据表定义区
		$t_album = $tablePreStr."album";
		$t_photo = $tablePreStr."photo";
	
		$dbo = new dbex;
		//读写分离定义函数
		dbtarget('r',$dbServs);
	
		$sql = "select * from $t_photo where photo_id=$photo_id";
		$photo_row = $dbo ->getRow($sql);
		$album_id=$photo_row['album_id'];
		$sql = "select * from $t_album where album_id=$album_id";
		$album_row = $dbo ->getRow($sql);
	
		//读写分离定义函数
		dbtarget('w',$dbServs);
	
		//删除照片
		if($album_row['album_skin']==$photo_row['photo_thumb_src']){
			$album_skin = 'uploadfiles/album/logo.jpg';
			$sql = "update $t_album set album_skin = '$album_skin' where album_id=$album_id";
			$dbo -> exeUpdate($sql);
		}
		@unlink('../'.$photo_row['photo_src']);
		@unlink('../'.$photo_row['photo_thumb_src']);
	
		$sql = "delete from $t_photo where photo_id=$photo_row[photo_id]";
		if($dbo -> exeUpdate($sql)){
			increase_integral($dbo,$int_del_photo,$user_id);
		}
	
		$sql = "update $t_album set photo_num=photo_num-1 where album_id=$album_id";
		if($dbo -> exeUpdate($sql)){
			echo $m_langpackage->m_del_suc;
		}
	}
?>
<script language="javascript" type="text/javascript">
window.location.href='photo_list.php?order_by=photo_id&order_sc=desc';
</script>
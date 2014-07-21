<?php
	require("session_check.php");	
	require("../foundation/aintegral.php");
	$is_check=check_rights("c23");
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
		$album_ids = get_argp('checkany');
		foreach($album_ids as $rs){
			$t_album = $tablePreStr."album";
			$t_photo = $tablePreStr."photo";
			$t_photo_comment = $tablePreStr."photo_comment";
			$t_album_comment = $tablePreStr."album_comment";
			$sql="select * from $t_photo where album_id=$rs";
			$photo_rs = $dbo-> getRs($sql);
			$sql="select * from $t_album where album_id=$rs";
			$user_id = $dbo-> getRow($sql);
			foreach($photo_rs as $val){
				@unlink('../'.$val['photo_src']);
				@unlink('../'.$val['photo_thumb_src']);
				increase_integral($dbo,$int_del_photo,$user_id);
				//删除照片相关评论
				$photo_id = $val['photo_id'];
				$sql = "delete from $t_photo_comment where photo_id =$photo_id";
				$dbo -> exeUpdate($sql);
			}
			//删除相册有关照片
			$sql = "delete from $t_photo where album_id=$rs";
			$dbo -> exeUpdate($sql);
			//删除相册相关评论
			$sql = "delete from $t_album_comment where album_id=$rs";
			$dbo -> exeUpdate($sql);
			//删除相册
			$sql="delete from $t_album where album_id=$rs";
			$dbo -> exeUpdate($sql);
		}
	}else{//单条
		//变量取得
		if(empty($album_id)){
			$album_id=intval(get_argg('aid'));
			$user_id=intval(get_argg('uid'));
		}
		//删除相册功能
		//数据表定义区
		$t_album = $tablePreStr."album";
		$t_photo = $tablePreStr."photo";
		$t_photo_comment = $tablePreStr."photo_comment";
		$t_album_comment = $tablePreStr."album_comment";
	
		$dbo = new dbex;
		//读写分离定义方法
		dbtarget('r',$dbServs);
		$sql="select * from $t_photo where album_id=$album_id";
		$photo_rs = $dbo -> getRs($sql);
		dbtarget('w',$dbServs);
		foreach($photo_rs as $val){
			@unlink('../'.$val['photo_src']);
			@unlink('../'.$val['photo_thumb_src']);
			increase_integral($dbo,$int_del_photo,$user_id);
			//删除照片相关评论
			$photo_id = $val['photo_id'];
			$sql = "delete from $t_photo_comment where photo_id =$photo_id";
			$dbo -> exeUpdate($sql);
		}
	
		//删除相册有关照片
		$sql = "delete from $t_photo where album_id=$album_id";
		$dbo -> exeUpdate($sql);
	
		//删除相册相关评论
		$sql = "delete from $t_album_comment where album_id=$album_id";
		$dbo -> exeUpdate($sql);
	
		//删除相册
		$sql="delete from $t_album where album_id=$album_id";
		if($dbo -> exeUpdate($sql)){
			echo $m_langpackage->m_del_suc;
		}
	}
	
?>
<script language="javascript" type="text/javascript">
window.location.href='album_list.php?order_by=album_id&order_sc=desc';
</script>
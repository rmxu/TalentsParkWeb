<?php
	//引入模块公共方法文件
	require("foundation/aintegral.php");
	require("api/base_support.php");

	//引入语言包
	$a_langpackage=new albumlp;

	//变量取得
	$album_id=short_check(get_argp('album_name'));
	$album_name=short_check(get_argp('album_ufor'));
	$user_id=get_sess_userid();
	$user_name=get_sess_username();
	$uico_url=get_sess_userico();//用户头像
	set_session('S_fs',array());

	$photos_array=array();//上传图片地址数组

	//变量定义区
	$t_photo = $tablePreStr."photo";
	$t_album = $tablePreStr."album";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);

	//第二步，执行文件上传
    $limcount=5;//限制每次上传附件数量
    $up_load_num=count($_FILES['attach']['name']);
    if($up_load_num > $limcount){
		global $a_langpackage;
       action_return(0,$a_langpackage->a_upload_maximum.$limcount.$a_langpackage->a_attachments,"-1");
    }
    //检测上传相片是否都为空
    $is_true=0;
    for($i=0;$i<count($_FILES['attach']['name']);$i++){
    	if(!empty($_FILES['attach']['name'][$i]))
    		$is_true++;
    }
	if($is_true==0){
		action_return(0,$a_langpackage->a_no_pht,"-1");
	}

		$base_root="uploadfiles/album/";//图片存放目录
    $up = new upload();
    $up->set_dir($base_root,'{y}/{m}/{d}');//目录设置
    $up->set_thumb(180,180); //缩略图设置
    $fs = $up->execute();

		$i=0;
    foreach($fs as $index=>$realtxt){
			if($realtxt['flag']==1){
		    $fileSrcStr=str_replace(dirname(__FILE__),"",$realtxt['dir']).$realtxt['name'];
		    $thumb_src=str_replace(dirname(__FILE__),"",$realtxt['dir']).$realtxt['thumb'];
		    $sql = "insert into $t_photo(`user_id`,`add_time`,`photo_src`,`photo_thumb_src`,`album_id`,`user_name`,`privacy`)
					                     values($user_id,now(),'$fileSrcStr','$thumb_src',$album_id,'$user_name','');";
		    if($dbo -> exeUpdate($sql)){
		      $photo_id=mysql_insert_id();
		      $fs[$index]['photo_id']=$photo_id;
		   	  $sql = "update $t_album set photo_num=photo_num+1,update_time=NOW() where album_id=$album_id";
		   	  if($dbo -> exeUpdate($sql)){
				  	increase_integral($dbo,$int_photo,$user_id);
		   	  }
		   	  $photos_array[$i]['id']=$photo_id;
		   	  $photos_array[$i]['file']=$thumb_src;
		 	  }
		 	  $i++;
			}else if($realtxt['flag']==-1){
				action_return(0,$a_langpackage->a_no_jpg,"-1");
			}else if($realtxt['flag']==-2){
				action_return(0,$a_langpackage->a_big,"-1");
			}
    }
	set_session('S_fs',$fs);

	//回应信息
	if($i>0){
	  action_return(1,"","modules.php?app=photo_update&album_id=$album_id");
	}else{
		action_return(0,$a_langpackage->a_upd_false,"-1");
	}
?>

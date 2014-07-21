<?php
	//引入语言包
	$a_langpackage=new albumlp;
	require("foundation/auser_mustlogin.php");
	require("api/base_support.php");
	
	//变量取得
	$album_id=intval(get_argg('album_id'));
	$user_id=get_sess_userid();
	$s_fs=get_session("S_fs");
	$fs = array();
	
	//表定义区
	$t_tmp_file = $tablePreStr."tmp_file";
	$album_row=api_proxy("album_self_by_aid","privacy,album_name",$album_id);
	
	if(empty($s_fs)){
		$dbo = new dbex;
		dbtarget('r',$dbServs);
		$sql="select data_array from $t_tmp_file where mod_id=$album_id";
		$session_data=$dbo->getRow($sql);
		$fs=unserialize($session_data['data_array']);
		$sql="delete from $t_tmp_file where mod_id=$album_id";
		$dbo->exeUpdate($sql);
	}else{
		$fs=$s_fs;
		set_session("S_fs",'');
	}
	
if($fs){
	//新鲜事
	if($album_row['privacy']==''){
		$show_limit=0;
		$content='';
		foreach($fs as $val){
			if($show_limit==4){
				break;
			}
			$show_limit++;
			$content.='<a href="home.php?h='.$user_id.'&app=photo&photo_id='.$val['photo_id'].'&album_id='.$album_id.'" target="_blank">'
			          .'<img class="photo_frame" onerror=parent.pic_error(this) src="'.$val['dir'].$val['thumb'].'"></a>';
		}
		if($show_limit==1){
			$mod_type=3;
			$mod_id=$val['photo_id'];
		}else{
			$mod_type=2;
			$mod_id=$album_id;
		}
		$title=$a_langpackage->a_in_album.'<a href="home.php?h='.$user_id.'&app=photo_list&album_id='.$album_id.'" target="_blank">'.$album_row['album_name'].'</a>'.$a_langpackage->a_upload_new_photo;
		$is_suc=api_proxy("message_set",$mod_id,$title,$content,2,$mod_type);
	}
}
	
?>
<?php
	//引入语言包
	$a_langpackage=new albumlp;
	$mn_langpackage=new menulp;

	require("foundation/module_users.php");

	//变量取得
	$photo_id = intval(get_argg('photo_id'));
	$album_id=intval(get_argg('album_id'));
	$prev_next = get_argg('prev_next');
	$url_uid= intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();

	//引入模块公共权限过程文件
	$is_login_mode='';
	$is_self_mode='partLimit';
	require("foundation/auser_validate.php");
	require("foundation/module_mypals.php");
	require("api/base_support.php");

	//数据显示控制
	$show_data="";
	$show_error="content_none";
	$show_content="content_none";

	//数据表定义区
	$t_photo = $tablePreStr."photo";
	$t_photo_comment = $tablePreStr."photo_comment";
	$t_users=$tablePreStr."users";
	$t_album=$tablePreStr."album";

	$album_info=array();
	$photo_row=array();
	$album_info=api_proxy("album_self_by_aid","album_name",$album_id);
	$a_who=($is_self=='Y') ? $a_langpackage->a_mine:str_replace('{holder}',filt_word(get_hodler_name($url_uid)),$a_langpackage->a_holder);
		
	if($album_info){
		//查找相册信息
		$album_name=$album_info['album_name'];
		if($prev_next){
			$photo_rs = api_proxy('album_photo_by_aid','photo_id',$album_id);
			$num = count($photo_rs);
			foreach($photo_rs AS $key=>$val)
			{
				if($val['photo_id'] == $photo_id)
				{
					$photo_id = $photo_rs[$prev_next === 'next' ? ($key == ($num - 1) ? 0 : $key + 1) : ($prev_next === 'prev' ? ($key == 0 ? $num - 1 : $key - 1) : 0)]['photo_id'];
					break;
				}
			}
		}
		
		$photo_row=api_proxy("album_photo_by_photoid","*",$photo_id);
		
		//查找照片信息
		if($photo_row['photo_src']){
			$img_info=getimagesize($photo_row['photo_src']);
		}
		$photo_inf=$photo_row['photo_information'] ? $photo_row['photo_information']:$a_langpackage->a_pht_inf;
		if($is_self=='Y'){
			$is_pri=1;
		}else{
			require("servtools/menu_pop/trans_pri.php");
			$is_pri=check_pri($photo_row['user_id'],$photo_row['privacy']);
		}
	}else{
		$show_data="content_none";
		$show_error="";
	}
?>
<?php  
	require("session_check.php");	
	require("../api/Check_MC.php");
	
	//语言包引入
	$f_langpackage=new foundationlp;
	$user_id=get_argp('user_id');
	$photo_src='';
	if(get_argp('u_ico_url')){
		$photo_src=get_argp('u_ico_url');
	}else{
		$photo_src='';
	}

  //表定义
  $t_recommend=$tablePreStr."recommend";

	if($photo_src!=''){
		dbtarget('w',$dbServs);
		$dbo=new dbex();
		$sql="update $t_recommend set show_ico='$photo_src' where user_id=$user_id";
		if($dbo->exeUpdate($sql)){
			$key_mt='recommend/list/rec_order/all/0_mt';
			updateCache($key_mt);
			echo '<script type="text/javascript">alert("'.$f_langpackage->f_index_suc.'");window.location.href="recommend_list.php?order_by=recommend_id&order_sc=desc";</script>';
		}else{
			echo '<script type="text/javascript">alert("'.$f_langpackage->f_handle_los.'");window.history.go(-1);</script>';
		}
	}else{
		echo '<script type="text/javascript">alert("'.$f_langpackage->f_none_photo.'");</script>';
	}

?>    


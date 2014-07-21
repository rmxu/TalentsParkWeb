<?php
	require("foundation/fcontent_format.php");
	
	//引入语言包
	$rf_langpackage=new recaffairlp;

	//变量取得
	$ra_type=intval(get_argg('t'));
	$user_id=get_sess_userid();
	$pals_id=get_sess_mypals();
	$start_num=intval(get_argg('start_num'));
	$hidden_pals_id=get_session('hidden_pals');
	$hidden_type_id=get_session('hidden_type');
	$holder_id=intval(get_argg('user_id'));
	$visitor_ico=get_sess_userico();
	$ra_type_str=$ra_type ? " and type_id=$ra_type " : "";
	$pals_str='';
	$limit_num=0;
	$ra_rs = array();
	
	//数据表定义区
	$t_rec_affair=$tablePreStr."recent_affair";
	
	//数据库读操作
	$dbo=new dbex;
	dbtarget('r',$dbServs);	
	
	if($holder_id!=''){//home新鲜事
		$limit_num=$homeAffairNum;
		$hidden_button_over="void(0)";
		$hidden_button_out="void(0)";
	  $sql = "select * from $t_rec_affair where user_id=$holder_id order by id desc limit $start_num , $limit_num";
	  $ra_rs = $dbo->getRs($sql);
	}else{//main新鲜事
		if($pals_id){
			$limit_num=$mainAffairNum;
			$ra_mod_type='';
		  $hidden_button_over="feed_menu({id},1);";
		  $hidden_button_out="feed_menu({id},0);";
		  
		  //屏蔽某人新鲜事
			if($hidden_pals_id!='' && $hidden_pals_id!=','){
				$pals_str=",".$pals_id.",";
				$hidden_pals_array=explode(",",$hidden_pals_id);
				foreach($hidden_pals_array as $rep){
					if($rep!=''){
						$pals_str=str_replace(",".$rep.",",",",$pals_str);
					}
				}
				$pals_str=preg_replace(array("/^,/","/,$/"),"",$pals_str);
				$pals_id=$pals_str;
			}
			
			//屏蔽某类新鲜事
			if($hidden_type_id!='' && $hidden_type_id!=','){
				$hidden_type_id=preg_replace(array("/^,/","/,$/"),"",$hidden_type_id);
				$ra_mod_type=" and mod_type not in ($hidden_type_id)";
			}
			
			//是否开启更多
			$is_more=intval($start_num/5);
			if($is_more){
				$pals_id_array=array_slice(explode(',',$pals_id),$is_more*$mainAffairNum,$mainAffairNum);
			}else{
				$pals_id_array=explode(',',$pals_id,$mainAffairNum+1);
			}
			if(isset($pals_id_array[0])){
				foreach($pals_id_array as $p_id){
					if(strpos($p_id,',')) break;
				  $sql = "select * from $t_rec_affair where user_id=$p_id $ra_type_str $ra_mod_type order by id desc limit 0,3";
				  $ra_rs_part=$dbo->getRs($sql);
				  $ra_rs=array_merge($ra_rs,$ra_rs_part);
				}
			}
		}
	}
?>
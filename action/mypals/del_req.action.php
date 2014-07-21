<?php 
//引入语言包
$mp_langpackage=new mypalslp;

  $user_id=get_sess_userid();
  $user_name=get_sess_username();  
  $req_id=intval(get_argg("req_id"));
  $req_uid=intval(get_argg("req_uid"));

//数据表定义区
		$t_pals_request=$tablePreStr."pals_request";
		$t_mypals=$tablePreStr."pals_mine";

//定义写操作
		dbtarget('w',$dbServs);
		$dbo=new dbex();
		$sql="delete from $t_pals_request where id=$req_id and user_id=$user_id";
		if($dbo->exeUpdate($sql)){
			$sql="delete from $t_mypals where pals_id = $user_id and user_id = $req_uid and accepted = 0";
			$dbo->exeUpdate($sql);
		}

//刷新提醒页面
        echo "<script type='text/javascript'>
  parent.frames['remind'].location.reload();
  </script>";		
		   			
		
		action_return(0,'',"");
?>  
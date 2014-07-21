<?php
	require("session_check.php");	
	require("../foundation/aintegral.php");
	require("../foundation/module_affair.php");
	
	$is_check=check_rights("c17");
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
		$blog_ids = get_argp('checkany');
		//数据表定义区
		$t_blog=$tablePreStr."blog";
		$t_uploadfile=$tablePreStr."uploadfile";
		$t_blog_comments=$tablePreStr."blog_comment";
		foreach($blog_ids as $rs){
			//删除blog内部的图片
			$sql="select * from $t_blog where log_id=$rs";
			$blog_content=$dbo->getRow($sql);
			$blog_content=$blog_content['log_content'];
			$sendor_id=$blog_content['user_id'];
			preg_match_all("/classId=\"\d\"/",$blog_content,$match);
			$match=preg_replace("/[classId=,\"]/",'',$match[0]);
			if(!empty($match)){
				$match=join(",",$match);
				$sql="select file_src from $t_uploadfile where id in ($match)";
				$file_src=$dbo->getRs($sql);
				foreach($file_src as $rs){
					unlink($rs['file_src']);
				}
			}
			if(!empty($match)){
			$sql="delete from $t_uploadfile where id in ($match)";
			$dbo->exeUpdate($sql);
			}
			$sql = "delete from $t_blog where log_id=$rs";
			if($dbo->exeUpdate($sql)){
				$sql="delete from $t_blog_comments where log_id=$rs";
				$dbo->exeUpdate($sql);
				increase_integral($dbo,$int_del_blog,$sendor_id);
				del_affair($dbo,0,$rs);	
			}
		}
	}else{//单条
		
		//变量区
		if(empty($blog_id)){
			$blog_id=intval(get_argg('blog_id'));
			$sendor_id=intval(get_argg('sendor_id'));
		}
		$dbo = new dbex;
		dbtarget('w',$dbServs);
		
		//数据表定义区
		$t_blog=$tablePreStr."blog";
		$t_uploadfile=$tablePreStr."uploadfile";
		$t_blog_comments=$tablePreStr."blog_comment";
		
		//删除blog内部的图片
		$sql="select log_content from $t_blog where log_id=$blog_id";
		$blog_content=$dbo->getRow($sql);
		$blog_content=$blog_content['log_content'];
		preg_match_all("/classId=\"\d\"/",$blog_content,$match);
		$match=preg_replace("/[classId=,\"]/",'',$match[0]);
		if(!empty($match)){
			$match=join(",",$match);
			$sql="select file_src from $t_uploadfile where id in ($match)";
			$file_src=$dbo->getRs($sql);
			foreach($file_src as $rs){
				unlink($rs['file_src']);
			}
		}
	
		if(!empty($match)){
		$sql="delete from $t_uploadfile where id in ($match)";
		$dbo->exeUpdate($sql);
		}
	
		$sql = "delete from $t_blog where log_id=$blog_id";
	
		if($dbo->exeUpdate($sql)){
			$sql="delete from $t_blog_comments where log_id=$blog_id";
			$dbo->exeUpdate($sql);
			increase_integral($dbo,$int_del_blog,$sendor_id);
			del_affair($dbo,0,$blog_id);
			//回应信息
			echo $m_langpackage->m_del_suc;		
		}else{
			echo $m_langpackage->m_del_lose;
		}
	}
?>
<script language="javascript" type="text/javascript">
window.location.href='blog_list.php?order_by=log_id&order_sc=desc';
</script>
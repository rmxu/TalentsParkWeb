<?php
/*
mod值分配:
0:日志;
1:相册;
2:群组;
3:分享;
4:话题;
5:投票;
*/

//标签添加
function tag_add($tag_data){
	if($tag_data!==''){
		global $tablePreStr;
		global $dbo;
		$table=$tablePreStr."tag";
		$dbo=new dbex;
		dbplugin('w');
		$tag_data=preg_replace(array('/\|/','/，/','/\s+/'),',',trim($tag_data));
		$tag_array=explode(',',$tag_data);
		$tag_id_str='';
		foreach($tag_array as $rs){
			if($rs!==''){
				$sql="select id from $table where name='$rs'";
				$tag_info=$dbo->getRow($sql);
				$tag_id_str.=$tag_id_str ? ',':'';
				if($tag_info){
					$tag_id=$tag_info['id'];
					$sql="update $table set count=count+1 where id='$tag_id'";
					$dbo->exeUpdate($sql);
					$tag_id_str.=$tag_id;
				}else{
					$sql="insert into $table (`name`,`count`) values ('$rs',1)";
					$dbo->exeUpdate($sql);
					$tag_id=mysql_insert_id();
					$tag_id_str.=$tag_id;
				}
			}
		}
		return $tag_id_str;
	}
}

//标签删除
function tag_del($tag_data){
	if($tag_data){
		global $tablePreStr;
		global $dbo;
		$table=$tablePreStr."tag";
		$dbo=new dbex;
		dbplugin('w');
		foreach($tag_data as $rs){
			$sql="select count from $table where id=$rs";
			$tag_info=$dbo->getRow($sql);
			if($tag_info['count']==1){
				$sql="delete from $table where id=$rs";
				$dbo->exeUpdate($sql);
			}else{
				$sql="update $table set count=count-1 where id=$rs";
				$dbo->exeUpdate($sql);
			}
		}
	}
}

//资源与标签的关系表
function tag_relation($mod_id,$tag_id,$content_id,$type='add'){
	global $tablePreStr;
	global $dbo;
	$dbo=new dbex;
	dbplugin('w');
	$table=$tablePreStr."tag_relation";
	$tag_id=explode(',',$tag_id);
	foreach($tag_id as $rs){
		if($rs!=''){
			if($type=='add') $sql="insert into $table (`id`,`mod_id`,`content_id`) values ($rs,$mod_id,$content_id)";
			else $sql="delete from $table where content_id=$content_id and id=$tag_id and mod_id=$mod_id";
			if(!$dbo->exeUpdate($sql)){
				return 'error';break;
			}
		}
	}
}
?>
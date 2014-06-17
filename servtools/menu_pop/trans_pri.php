<?php
function check_pri($holder,$exp=''){
	$sess_uid=get_sess_userid();
	$is_admin=get_sess_admin();
	if($sess_uid!=$holder && !$is_admin){
		if($exp){
			if(!$sess_uid){
				return false;
			}
			if($exp=='!all'){//全否定
				return false;
			}
			if(strpos(",$exp","{")){//限定人
				$per_str=preg_replace("/{([,\d]+)}/","$1",$exp);
				if(strpos(",$per_str",",$sess_uid,")){
					return true;
				}
			}
			if(strpos(",$exp","[")){//限定组
				$sort_str=preg_replace("/\[([,\d]+)\]/","$1",$exp);
				global $dbo;
				global $tablePreStr;
				global $dbServs;
				if(!$dbo){
					$dbo=new dbex;
				  dbplugin('r');
				}
				$table=$tablePreStr."pals_mine";
				$sql="select pals_sort_id from $table where pals_id=$sess_uid and user_id=$holder";
				$sort_id=$dbo->getRow($sql);
				$sess_sort_id=$sort_id['pals_sort_id'];
				if(strpos(",$sort_str",",$sess_sort_id,")){
					return true;
				}
			}
		}else{
			return true;
		}
	}else{
		return true;
	}
}

function show_pri($exp=''){
	if($exp==''){
		echo '公开';
	}else if($exp=='!all'){
		echo '仅自己';
	}else{
		echo '部分好友';
	}
}
?>
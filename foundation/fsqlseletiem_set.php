<?php
function get_select_item_sql($select_items){
$si_sql_part='';
if($select_items!='*'){
	if(is_array($select_items)){
		$i=1;
		foreach($select_items as $sel_item){
			$cStr='';
			if($i>1){ $cStr=','; }
			$si_sql_part=$si_sql_part.$cStr.$sel_item;
			$i++;
		}
	}else{
		$si_sql_part=$select_items;
	}
}else{
	 $si_sql_part='*';
}
return $si_sql_part;
}

function spell_sql($table,$eq_array,$like_array,$date_array,$num_array,$order_col,$order,$join_cond=''){
	if($join_cond!='' && strpos($table,",")){
		$table_array=explode(",",$table);
		$table=$table_array[0];
		$sql="select * from $table_array[0] join $table_array[1] on ($table_array[0].$join_cond=$table_array[1].$join_cond) where 1=1 ";
	}else{
		$sql="select * from $table where 1=1 ";
	}
	foreach($_GET as $key => $search_value){
		if($search_value!=''||is_numeric($search_value)){
			if(in_array($key,$like_array)){
				$search_value="%$search_value%";
			}
			if(!is_numeric($search_value)){
				$search_value="'$search_value'";
			}
			if(in_array($key,$eq_array)){
				$sql.=" and $table.$key = $search_value ";
			}
			if(in_array($key,$like_array)){
				$sql.=" and $table.$key like $search_value ";
			}

			if(in_array($col=substr($key,0,-1),$date_array)){
				$is_max=substr($key,-1);
				if($is_max=='1'){
					$sql.=" and date($table.$col) >= $search_value ";
				}else if($is_max=='2'){
					$sql.=" and date($table.$col) <= $search_value ";
				}
			}

			if(in_array($col=substr($key,0,-1),$num_array)){
				$is_max=substr($key,-1);
				if($is_max=='1'){
					$sql.=" and $table.$col >= $search_value ";
				}else if($is_max=='2'){
					$sql.=" and $table.$col <= $search_value ";
				}
			}
		}
	}
	if(!empty($order_col)){
		$sql.=" order by $table.$order_col $order ";
	}
	return $sql;
}
?>
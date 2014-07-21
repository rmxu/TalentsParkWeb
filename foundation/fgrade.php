<?php
//引入语言包
$fd_langpackage=new foundationlp;
//等级换算
function grade($integral){
	global $skinUrl;
	global $int_convert;
	global $int_upgrade;
	if( $int_convert > $integral){
		echo "<img src='skin/$skinUrl/images/star.gif' />";
	}else{
		up_level($integral);
	}
}

function up_level($integral){
	global $int_convert;
	global $int_upgrade;
	global $skinUrl;
	if($integral >= $int_convert*$int_upgrade*$int_upgrade ){
		echo "<img src='skin/$skinUrl/images/sun.gif' />";
		up_level($integral-$int_convert*$int_upgrade*$int_upgrade);
	}else{
		if($integral >= $int_convert*$int_upgrade){
			echo "<img src='skin/$skinUrl/images/moon.gif' />";
			up_level($integral - $int_convert*$int_upgrade);
		}else{
			if( $integral >= $int_convert ){
				echo "<img src='skin/$skinUrl/images/star.gif' />";
				up_level($integral - $int_convert);
			}else{
				return NULL;
			}
		}
	}
}

function count_level($integral){
	global $int_convert;
	global $fd_langpackage;
	if($integral < $int_convert){
		$level=ceil($integral/$int_convert);
	}else{
		$level=floor($integral/$int_convert);
	}
	return $fd_langpackage->fd_grade."：".$level;
}

?>
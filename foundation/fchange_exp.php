<?php
function change_exp($content,$c_array=0){
	foreach($_REQUEST as $key => $value){
		if(!is_array($value)){
			if(preg_match("/\n/",$value)&&$c_array==1){
				$tmp_array=explode("\n",$value);
				$value_array[]=spell_array($tmp_array);
			}else{
				if(is_numeric($value)){
					$value_array[]=$value;
				}else{
					$value_array[]="\"".$value."\"";
				}
			}
			$key_array[]=$key;
		}
	}
	$array=array_combine($key_array,$value_array);
	foreach($array as $key => $value){
		$content=preg_replace("/(\\$$key)=([^;]+)/","\$1=$value",$content);
	}
	return $content;
}
?>
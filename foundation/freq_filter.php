<?php
//post,get对象过滤通用函数
function login_check($post){
   $MaxSlen=30;//限制登陆验证输入项最多20个字符
   if (!get_magic_quotes_gpc())    // 判断magic_quotes_gpc是否为打开
   {
      $post=addslashes($post);    // 进行magic_quotes_gpc没有打开的情况对提交数据的过滤
   }
   $post = LenLimit($post,$MaxSlen);
   $post=preg_replace("/　+/","",trim(str_replace(" ","",$post)));
   $post=cleanHex($post);
   if (strpos($post,"=")||strpos($post,"'")||strpos($post,"\\")||strpos($post,"*")||strpos($post,"#")){
       return false;
   }else{
       return true;
   }
}

function long_check($post)
{
   $MaxSlen=3000;//限制较长输入项最多3000个字符
   if (!get_magic_quotes_gpc())    // 判断magic_quotes_gpc是否为打开
   {
      $post = addslashes($post);    // 进行magic_quotes_gpc没有打开的情况对提交数据的过滤
   }
   $post = LenLimit($post,$MaxSlen);
   $post = str_replace("\'", "’", $post);
   $post= htmlspecialchars($post);    // 将html标记转换为可以显示在网页上的html
   $post = nl2br($post);    // 回车
   return $post;
}

function big_check($post){
	$MaxSlen=30000;//限制大输入项最多30000个字符
	if (!get_magic_quotes_gpc())    // 判断magic_quotes_gpc是否为打开
	{
	$post = addslashes($post);    // 进行magic_quotes_gpc没有打开的情况对提交数据的过滤
	}
	$post = LenLimit($post,$MaxSlen);
	$post = str_replace("\'", "’", $post);
	$post = str_replace("<script ", "", $post);
	$post = str_replace("</script ", "", $post);
	return $post;
}

function short_check($str)
{
   $MaxSlen=500;//限制短输入项最多300个字符
   if (!get_magic_quotes_gpc())    // 判断magic_quotes_gpc是否打开
   {
      $str = addslashes($str);    // 进行过滤
   }
		$str = LenLimit($str,$MaxSlen);
		$str = str_replace(array("\'","\\","#"),"",$str);
		$str= htmlspecialchars($str);
		return preg_replace("/　+/","",trim($str));
}

//过滤16进制
function cleanHex($input){
     $clean = preg_replace("![\][xX]([A-Fa-f0-9]{1,3})!", "",$input);
     return $clean;
}

//限制输入字符长度，防止缓冲区溢出攻击
function LenLimit($Str,$MaxSlen){
    if(isset($Str{$MaxSlen})){
        return " ";
    }else{
        return $Str;
    }
}

//过滤敏感词语
function filt_word($Content){
	global $wordFilt;
	$is_admin=get_sess_admin();
	if($wordFilt==1 && $is_admin==''){
		global $filtrateStr;
		$f_array=explode(",",$filtrateStr);
		$repl="*";
		foreach($f_array as $v){
			$Content=str_replace($v,$repl,$Content);
		}
	}
	return get_face($Content);
}

//过滤数字
function filt_num_array($id_str){
	if($id_str!=''){
		if(!is_array($id_str)){
			$id_str=explode(",",$id_str);
		}
		$id_array=array_map("intval",$id_str);
		$id_str=join(",",$id_array);
		return $id_str;
	}else{
		return 0;
	}
}
//过滤字符
function filt_str_array($str){
	$gstr="";
	if(!is_array($str)){
		$str=explode(",",$str);
	}
	$str_array=array_map("sql_filter",$str);
	foreach($str_array as $val){
		if($val!=''){
			$gstr.="'".$val."',";
		}
	}
	$gstr=preg_replace("/,$/","",$gstr);
	return $gstr;
}
//排序过滤
function filt_order($order){
	if($order!='desc' && $order!='asc'){
		$order='desc';
	}
	return $order;
}
//sql语句过滤
function sql_filter($str){
	return str_replace(array("/","\\","'","#"," ","  ","%","&","\(","\)"),"",$str);
}
//字段过滤
function filt_fields($fields){
	if(!is_array($fields)){
		$fields=explode(",",$fields);
	}
	$fields_array=array_map("sql_filter",$fields);
	$fields_str=join(",",$fields_array);
	$fields_str=preg_replace('/^,|,$/','',$fields_str);
	return $fields_str;
}
//取出允许获取的数据
function check_item($check_str,$match_array){
	$result_str='';
	if(!is_array($check_str)){
		$check_str=explode(",",$check_str);
	}
	foreach($check_str as $rs){
		if(in_array($rs,$match_array)){
			$result_str.=$rs.",";
		}
	}
	return preg_replace("/,$/","",$result_str);
}
//日期过滤
function date_filter(&$date){
	$date=sql_filter($date);
	$date_str='';
	if($date!=''){
		if(strstr($date,"~")){
			$date_array=explode("~",$date);
			if(!empty($date_array[0])){
				$date_str.=" and date({date}) >= '$date_array[0]' ";
			}
			if(!empty($date_array[1])){
				$date_str.=" and date({date}) <= '$date_array[1]' ";
			}
		}else{
			$date_str.=" and {date} = '$date' ";
		}
	}
	return $date_str;
}

//拼接数组
function spell_array($array){
	$time_str="array(";
	foreach($array as $rs){
		if($time_str!="array("){
			$time_str.=",";
		}
		if(!empty($rs)){
			$time_str.="\"".$rs."\"";
		}
	}
	$time_str.=")";
	return $time_str;
}

//取得字符串中的表情
function get_face($str){
	global $skinUrl;
	global $siteDomain;
	preg_match_all("/\[em_\d+\]/",$str,$match_array);
	$match_none=str_replace(array("[","]"," "),"",$match_array[0]);
	$rep_str=array();
	foreach($match_none as $rs){
		$rs_num = substr($rs,3);
		$rep_str[] = ($rs_num>=1 && $rs_num<=49) ? '<img class="face_em" src="'.$siteDomain.'skin/'.$skinUrl.'/images/em/'.$rs.'.gif" />' : '['.$rs.']';
	}
	return str_replace($match_array[0],$rep_str,$str);
}

function sub_str($str, $length = 0, $append = true, $charset='utf8') {
	$str = trim($str);
	$strlength = strlen($str);
	$charset = strtolower($charset);
	if ($charset == 'utf8') {
		$l = 0;
		$i = 0;
		while ($i < $strlength) {
			if (ord($str{$i}) < 0x80) {
				$l++; $i++;
			} else if (ord($str{$i}) < 0xe0) {
				$l++; $i += 2;
			} else if (ord($str{$i}) < 0xf0) {
				$l += 2; $i += 3;
			} else if (ord($str{$i}) < 0xf8) {
				$l += 1; $i += 4;
			} else if (ord($str{$i}) < 0xfc) {
				$l += 1; $i += 5;
			} else if (ord($str{$i}) < 0xfe) {
				$l += 1; $i += 6;
			}
			if ($l >= $length) {
				$newstr = substr($str, 0, $i);
				break;
			}
		}
		if($l < $length) {
			return $str;
		}
	} elseif($charset == 'gbk') {
		if ($length == 0 || $length >= $strlength) {
			return $str;
		}
		while ($i <= $strlength) {
			if (ord($str{$i}) > 0xa0) {
				$l += 2; $i += 2;
			} else {
				$l++; $i++;
			}
			if ($l >= $length) {
				$newstr = substr($str, 0, $i);
				break;
			}
		}
	}
	if ($append && $str != $newstr) {
		$newstr .= '..';
	}
	return $newstr;
}

?>
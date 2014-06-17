<?php
/*
支持直接输入网址便可以采集出视频地址和缩略图地址，支持的视频网站为数组$web_site中的，可以动态添加。
各个数组的功能和作用依次为：支持的视频网站url；视频地址采集；缩略图地址采集；视频和缩略图褪壳；
*/
$web_site=array(
"http://v.youku.com/" => array("/value=\".*(.swf)\"/","/\|[^%\s]*\|\">/",array("value=\"","\""),array("|","\"",">")),
"http://v.ku6.com/" => array("/value=\".*(.swf)\"/","/class=\"s_pic\">.*(.jpg)/",array("value=\"","\""),array("class=\"s_pic\">")),
"http://video.sina.com.cn/"=> array("/value=\".*\"\sstyle/","",array("value=\""," style","\""),array("")),
"http://sports.joy.cn/"=> array("/<embed src=\".*(.swf)\"/","/http:\/\/.*(.jpg)/",array("<embed src=","\""),array("\"")),
"http://news.joy.cn/"=> array("/<embed src=\".*(.swf)\"/","/http:\/\/.*(.jpg)/",array("<embed src=","\""),array("\"")),
"http://ent.joy.cn/"=> array("/<embed src=\".*(.swf)\"/","/http:\/\/.*(.jpg)/",array("<embed src=","\""),array("\"")),
"http://finance.joy.cn/"=> array("/<embed src=\".*(.swf)\"/","/http:\/\/.*(.jpg)/",array("<embed src=","\""),array("\"")),
"http://tvplay.joy.cn/"=> array("/<embed src=\".*(.swf)\"/","/http:\/\/.*(.jpg)/",array("<embed src=","\""),array("\"")),
"http://games.joy.cn/"=> array("/<embed src=\".*(.swf)\"/","/http:\/\/.*(.jpg)/",array("<embed src=","\""),array("\"")),
"http://auto.joy.cn/"=> array("/<embed src=\".*(.swf)\"/","/http:\/\/.*(.jpg)/",array("<embed src=","\""),array("\"")),
"http://you.joy.cn/"=> array("/<embed src=\".*(.swf)\"/","/http:\/\/.*(.jpg)/",array("<embed src=","\""),array("\"")),
"http://movie.joy.cn/"=> array("/<embed src=\".*(.swf)\"/","/http:\/\/.*(.jpg)/",array("<embed src=","\""),array("\"")),
);

function info_collect($outer_link){
	set_session('m_link','');
	set_session('m_thumb','');
	$re_title='';
	$movie_link='';
	$movie_thumb='';
	$content='';
	$movie_type='';
	$website_type=array();
	global $web_site;
	preg_match("/http:\/\/[\w\.]+\//",$outer_link,$website_type);
	if($website_type){
		$movie_type=$website_type[0];
	}
	$reg_music="/\.(mp3)$|\.(wma)$/i";
	$reg_movie="/\.(mp4)$|\.(flv)$|\.(swf)$/i";
	if(preg_match($reg_music,$outer_link)){
		$share_type=6;//音乐匹配
	}elseif(preg_match($reg_movie,$outer_link)){
		$share_type=7;//视频匹配
	}elseif(in_array($movie_type,array_keys($web_site))){
		for($get_num=0;$get_num <= 5;$get_num++){
			$content=@file_get_contents($outer_link);
			if(!empty($content)){
				break;
			}
		}
		preg_match($web_site[$movie_type][0],$content,$movie_link);
		$movie_link=str_replace($web_site[$movie_type][2],'',$movie_link[0]);
		if($web_site[$movie_type][1]!=''){
			preg_match($web_site[$movie_type][1],$content,$movie_thumb);
			$movie_thumb=str_replace($web_site[$movie_type][3],'',$movie_thumb[0]);
		}
		if($movie_link==''){
			$share_type=5;
		}else{
			set_session('m_link',$movie_link);
			set_session('m_thumb',$movie_thumb);
			$share_type=7;
		}
	}else{
		$share_type=5;
	}
	if($share_type!=6){
		if($content==''){
			for($get_num=0;$get_num <= 5;$get_num++){
				$content=@file_get_contents($outer_link);
				if(!empty($content)){
					break;
				}
			}
		}
		preg_match("/<title>[\s\S.]*<\/title>/",$content,$re_title);
		if(!empty($re_title)){
			$re_title=str_replace(array("<title>","</title>"),"",$re_title[0]);
			if(!is_utf8($re_title)){
				$re_title=iconv("gb2312","utf-8",$re_title);
			}
		}else{
			$re_title='';
		}
	}
	echo $share_type."^".$re_title;
}

?>
<?php
function getAppId(){
  if(isset($_GET['app'])){
  	  return $_GET['app'];
  }else{
  	  return 'start';
  }
}

function getActId(){
  if(isset($_GET['act'])){
  	  return $_GET['act'];
  }else{
  	  echo 'para err';
  	  exit;
  }
}

function getRequest(){
	global $siteDomain;
	global $urlRewrite;
	$server_url=str_replace(array(".html",".htm"),"",$_SERVER['REQUEST_URI']);
	if($urlRewrite==2 && !strpos($server_url,'?')){
		$request_str=strstr($server_url,'.php');
		$request_arr=explode('/',$request_str);
		array_shift($request_arr);
		return $request_arr;
	}else{
		return '?'.$_SERVER['QUERY_STRING'];
	}
}

function getReUrl(){
	$urlParaStr='';
	$request_arr=getRequest();
	if(is_string($request_arr)){//没有伪静态
		$urlParaStr=str_replace('h=','user_id=',$request_arr);
		if(!strpos(",".$urlParaStr,'app')){
			$urlParaStr=$urlParaStr.'&app=hstart';
		}
	}else{//伪静态
		if(isset($request_arr[1])){
			foreach($request_arr as $val){
				$urlParaStr.='/'.$val;
			}
		}else if(isset($request_arr[0])){
			$urlParaStr='/'.$request_arr[0].'/hstart';
		}
	}
	return $urlParaStr;
}

function transRewrite(){
	$rewrite_array=array("blog","photo");
	$script_name = basename($_SERVER['SCRIPT_NAME']);
	$request_str=getReUrl();
		
	if(strpos(','.$request_str,'/')){
		$request_arr=explode('/',$request_str);
		array_shift($request_arr);

		if($script_name=='home.php'||intval($request_arr[0])){
			isset($request_arr[0]) && $_GET['user_id']=$_GET['h']=$request_arr[0];
			isset($request_arr[1]) && $_GET['app']=$request_arr[1];
			$app=$request_arr[1];
		}else if($script_name=='modules.php' && in_array($request_arr[0],$rewrite_array)){
			isset($request_arr[0]) && $_GET['app']=$request_arr[0];
			$app=$request_arr[0];
		}
		if(isset($app)){
			switch($app){
				case "hstart":
				isset($request_arr[0]) && $_GET['user_id']=$request_arr[0];
				isset($request_arr[1]) && $_GET['app']=$request_arr[1];
				break;
				case "blog":
				if($script_name=='home.php'||intval($request_arr[0])){
					isset($request_arr[0]) && $_GET['user_id']=$_GET['h']=$request_arr[0];
					isset($request_arr[1]) && $_GET['app']=$request_arr[1];
					isset($request_arr[2]) && $_GET['id']=$request_arr[2];				
				}else if($script_name=='modules.php'){
					isset($request_arr[0]) && $_GET['app']=$request_arr[0];
					isset($request_arr[1]) && $_GET['id']=$request_arr[1];
					isset($request_arr[2]) && $_GET['user_id']=$_GET['h']=$request_arr[2];
				}
				break;
				case "photo":
				if($script_name=='home.php'||intval($request_arr[0])){
					isset($request_arr[0]) && $_GET['user_id']=$_GET['h']=$request_arr[0];
					isset($request_arr[1]) && $_GET['app']=$request_arr[1];
					isset($request_arr[2]) && $_GET['photo_id']=$request_arr[2];
					isset($request_arr[3]) && $_GET['album_id']=$request_arr[3];
				}else if($script_name=='modules.php'){
					isset($request_arr[0]) && $_GET['app']=$request_arr[0];
					isset($request_arr[1]) && $_GET['photo_id']=$request_arr[1];
					isset($request_arr[2]) && $_GET['album_id']=$request_arr[2];
					isset($request_arr[3]) && $_GET['user_id']=$_GET['h']=$request_arr[3];
				}
				break;
			}
		}
	}
}

function rewrite_fun($url){
	global $urlRewrite;
	if($urlRewrite){
		$url=str_replace(array('?h=','?app=','&app=','&id=','&user_id=','&photo_id=','&album_id=','&p_id='),'/',$url);
		if($urlRewrite==1){
			$url=str_replace('.php','',$url);
		}
	}
	return $url;
}

if($urlRewrite==2){
	transRewrite();
}
?>
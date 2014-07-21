<?php
//缓存
global $mc;
$mc=null;
if($baseLibsPath=='iweb_si_lib/')
{
	$mc=new memcached($options);
}
else
{
	$mc=new IWebDBCache();
}
function  updateCache($key_mt,$list=NULL)
{
	global $mc;
	if($mc)
	{
		updateCacheEvent($mc,$key_mt,$list);
	}
}
function model_cache($key,$key_mt,&$dbo,&$sql,$get_type='getRs')
{
	global $mc;
	global $cache_update_delay_time;

	if(!is_null($mc))
	{
		$listkey=updateCacheStatus($mc,$key_mt,$cache_update_delay_time);
		if($listkey)
		{
			if(is_array($listkey))
			{
				$basekey=$listkey[0];
				$len=count($listkey);
				$mc->delete($basekey);
				for($i=1;$i<$len;$i++)
				{
					$mc->delete($basekey.$listkey[$i]);
				}
			}
			else
			{
				$mc->delete($key);
			}
		}
		$result_rs='';
		$contents=$mc->get($key);
		if($contents){
			$result_rs=$contents;
		}else{
			$result_rs=$dbo->{$get_type}($sql);
			$mc->set($key,$result_rs);
		}
		return $result_rs;
	}
}
?>
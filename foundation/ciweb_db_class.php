<?php
interface IWebCache
{
	public function get($key);
	public function set($key,$content);
	public function delete($key);
}
class IWebDBCache implements IWebCache
{
	public function set($key,$content)
	{
		$key=$this->hashkey($key);
		$content=serialize($content);
		if(!file_exists($key))$this->mkdirs(dirname($key));
		$handle=fopen($key,"w");
		if(fwrite($handle,$content)=== true)
		{
			$flag=true;
		}
		else
		{
			$flag=false;
		}
		fclose($handle);
		return $flag;
	}
	public function get($key)
	{
		$key=$this->hashkey($key);
		if(file_exists($key))
		{
			$content =file_get_contents($key);
			$content=unserialize($content);
		}
		else
		{
			$content=NULL;
		}
		return $content;
	}
	public function delete($key)
	{
		$key=$this->hashkey($key);
		if(file_exists($key))unlink($key);
	}
	private function hashkey($key)
	{
		global $cache_path;
		$key=str_replace(" ","",$key);
		$index=basename($key);
		$base=dirname($key);
		if(is_numeric($index))
		{
			$index=($index>>10)."/".$index.".php";
		}
		else if(is_string($index))
		{
			if($index=="")
			{
				$index="1024/0.php";
			}
			else
			{
				$tem=explode("_",$index);
				if(is_numeric($tem[0]))
				{
					$index=($tem[0]>>10)."/".$index.".php";
				}
				else
				{
					$num=crc32($tem[0]);
					$index=($num%1024)."/".($num>>10)."/".$index.".php";
				}
			}
		}
		return $cache_path.$base."/".$index;
	}
	private function mkdirs($dir)
	{
		return is_dir($dir) || ($this->mkdirs(dirname($dir)) && mkdir($dir, 0777));
	}
}

?>

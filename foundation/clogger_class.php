<?php
class Logger
{
	private $begin_time;
	private $dirpath;
	public function __construct($dirpath="")
	{
		$this->begin_time=$this->getmicrotime();
		$this->dirpath=$dirpath;
	}
	public function setLog($log)
	{
		if(!file_exists($this->dirpath))$this->mkdirs($this->dirpath);
		
		$filename=$this->dirpath."/".date("Y-m-d").".log";
		$handle=fopen($filename,"a+");
		$end_time=$this->getmicrotime();
		$spend_time=$end_time-$this->begin_time;
		if(!fwrite($handle,"[".date('H:i:s')."] use ".sprintf("%f",$spend_time)." ms run $log \n"));
		fclose($handle);
	}
	function getmicrotime()
	{
		list($usec, $sec) = explode(" ",microtime());
		return ((float)$usec + (float)$sec); 
	}
	private function mkdirs($dir)
	{
		return is_dir($dir) || ($this->mkdirs(dirname($dir)) && mkdir($dir, 0777));
	}
}
?>
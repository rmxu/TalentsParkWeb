<?php
function plugin_submit_form()
{
	$WebRoot=$_SERVER['DOCUMENT_ROOT'];
	if(isset($_POST["plugin_submit_file"]))
	{
		$plugin_submit_files=$_POST["plugin_submit_file"];
		foreach($plugin_submit_files as $file)
		{
			if(strpos($file,'?')){//开始构造$_GET数组
				$query=strstr($file,'?');
				$query_array=explode('&',substr($query,1));
				foreach($query_array as $rs){
					$get_array=explode('=',$rs);
					$get_index=$get_array[0];
					$_GET[$get_index] = isset($get_array[1]) ? $get_array[1]:'';
				}
				$file=str_replace($query,'',$file);
			}
			if(file_exists($WebRoot.$file)){
				include($WebRoot.$file);
			}
		}
	}
}
?>
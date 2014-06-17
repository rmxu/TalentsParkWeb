<?php
	//引入语言包
	$blo_langpackage=new bloglp;
	$lp_def_sort=$blo_langpackage->b_default_sort;

function blog_sort_list($blog_sort_rs,$selectedId){
	global $lp_def_sort;
	$str = '';
	$str .= '<select name="blog_sort_list" id="blog_sort_list" onchange=document.getElementById("blog_sort_name").value=this.options[selectedIndex].text;>';
	$str .= '<option '.($selectedId ? '' : 'selected="selected"') .' value="">'.$lp_def_sort.'</option>';
		foreach($blog_sort_rs as $rs)
		{
			$str .= '<option '.($selectedId == $rs['id'] ? 'selected="selected"' : '') . ' value="'.$rs['id'].'">'.$rs['name'].'</option>';
		}
	$str .= '</select>';
	echo $str;
}

function get_blog_sort($log_sort_name){
	global $lp_def_sort;
	if($log_sort_name=="-1"){
		return NULL;
	}else{
			return empty($log_sort_name)? $lp_def_sort:$log_sort_name;
	}
}
?>
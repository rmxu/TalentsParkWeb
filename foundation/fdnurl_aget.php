<?php
//domain_name url 自动获得
function get_site_domain(){
global $siteDomain;
	if(trim($siteDomain)==''){
	  return 'http://'.$_SERVER['HTTP_HOST'].'/';
	}else{
		if(strrpos($siteDomain,'/')!=strlen($siteDomain)-1){
			echo strrpos($siteDomain,'/'),'||',strlen($siteDomain);
		  return $siteDomain.'/';
		}else{
			return $siteDomain;
		}
	}
}


function get_uhome_url($user_id){
	return get_site_domain().'home.php?h='.$user_id;
}

function get_uinvite_url($user_id){
	global $indexFile;
	return get_site_domain().$indexFile.'?tg=invite&uid='.$user_id;
}
?>
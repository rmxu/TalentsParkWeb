<?php
function check_rights($code){
	$is_pass=1;
	if(get_session('admin_group')!='superadmin'){
		$local_rights=get_session('rights');
		if(!stripos(",,{$local_rights},",",{$code},")){
			$is_pass=0;
		}
	}
	return $is_pass;
}
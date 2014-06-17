<?php
function updateCacheEvent(&$mc,$key_mt,$key_list=NULL){
   $mt=time();
   /*
    if(!$mc->get($key_mt)){
	      $mc->set($key_mt,$mt);
    }
    */
    //修改测试

    if(!$mc->get($key_mt)){
	      $mc->set($key_mt,array($mt,$key_list));
    }
}

function  updateCacheStatus(&$mc,$key_mt,$cache_update_delay_time){
  $mt=time();
  $mt_content=$mc->get($key_mt);
  if(($mt_content)&& isset($mt_content[0]) && $mt-$mt_content[0]>$cache_update_delay_time){

 	     if(isset($mt_content[1]) && $mt_content[1]!=NULL)$list= $mt_content[1];
 	     else $list=true;
 	     $mc->delete($key_mt);
 	     return $list;
  }
}
?>
<?php
	//引入公共方法
	require("foundation/fcontent_format.php");
	require("api/base_support.php");
	//语言包引入
	$pr_langpackage=new privacylp;

	//变量获得
	$user_id=get_sess_userid();

	$user_privacy=api_proxy("user_self_by_uid","access_limit,access_questions,access_answers",$user_id);

	$access_qa_array=array(
		array("q"=>'',"a"=>''),array("q"=>'',"a"=>''),array("q"=>'',"a"=>'')
	);

	$arr_qs=explode(',',$user_privacy['access_questions']);
	$arr_as=explode(',',$user_privacy['access_answers']);

	$i=0;
	foreach($arr_qs as $str_q){
		if($str_q!=''){
		$access_qa_array[$i]['q']=$str_q;
		$access_qa_array[$i]['a']=$arr_as[$i];
		}
		$i++;
	}


?>

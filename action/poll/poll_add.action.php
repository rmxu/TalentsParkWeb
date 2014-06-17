<?php
//引入模块公共方法文件
require("api/base_support.php");
require("foundation/aintegral.php");
require("foundation/ftag.php");

//引入语言包
$pol_langpackage=new polllp;

//权限验证
if(!get_argp('action')){
	action_return(0,"$pol_langpackage->pol_error",-1);
}

//变量声明区
	$user_id=get_sess_userid();
	$user_name=get_sess_username();
	$userico=get_sess_userico();
  $subject=short_check(get_argp('subject'));
  $message=short_check(get_argp('message'));
  $sex=short_check(get_argp('sex'));
  $noreply=short_check(get_argp('noreply'));
  $expiration=short_check(get_argp('expiration'));
  $reward=short_check(get_argp('reward'));
  $credit=short_check(get_argp('credit'));
  $percredit=short_check(get_argp('percredit'));
  $makefeed=short_check(get_argp('makefeed'));
  $maxchoice=short_check(get_argp('maxchoice'));
  $cho=array_unique(get_argp('option'));
  $tag=short_check(get_argp('tag'));

//数据表定义区
	$t_poll=$tablePreStr."poll";
	$t_polloption=$tablePreStr."polloption";

//定义写操作
  dbtarget('w',$dbServs);
  $dbo=new dbex();

   foreach($cho as $value){
  	if(short_check($value)!=''){
  		$cho_array[]=short_check($value);
  	}
  }

  if($maxchoice==1){
  	$input_type='radio';
  }else{
  	$input_type='checkbox';
  }

  $poll_option="<input type=\"".$input_type."\" disabled />".$cho_array[0]."<br />";
  $poll_option.="<input type=\"".$input_type."\" disabled />".$cho_array[1]."<br />";
  $poll_option.="......";

  $cho_ser=serialize(array($cho_array[0],$cho_array[1]));

  $multiple=($maxchoice==1)? 0 : 1;
  $cre_value=empty($credit) ? 0 : intval($credit);
  $per_value=empty($percredit) ? 0 : intval($percredit);

  $sql="insert into $t_poll (`user_id`,`username`,`user_ico`,`subject`,`multiple`,`maxchoice`,`sex`,`noreply`,`dateline`,`credit`,`percredit`,`expiration`,`message`,`option`) values ($user_id,'$user_name','$userico','$subject',$multiple,$maxchoice,$sex,$noreply,NOW(),$cre_value,$per_value,'$expiration','$message','$cho_ser')";
  $dbo->exeUpdate($sql);

  $last_pid=mysql_insert_id();

  foreach($cho_array as $val){
		$sql="insert into $t_polloption (`pid` ,`option`) values ($last_pid,'$val')";
		$dbo->exeUpdate($sql);
  }

	if($makefeed==1){
		//纪录新鲜事
		$title=$pol_langpackage->pol_launch_vote.'<a href="home.php?h='.$user_id.'&app=poll&p_id='.$last_pid.'" target="_blank">'.$subject.'</a>';
		$content='<a href="home.php?h='.$user_id.'&app=poll&p_id='.$last_pid.'" target="_blank">'.$subject.'</a>';
		$is_suc=api_proxy("message_set",$last_pid,$title,$content,0,4);
	}

	//标签功能
	$tag_id=tag_add($tag);
	$tag_state=tag_relation(5,$tag_id,$last_pid);

	increase_integral($dbo,$int_poll,$user_id);
	//回应信息
	action_return(0,"","");
?>
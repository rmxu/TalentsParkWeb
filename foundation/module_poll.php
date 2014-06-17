<?php
	//引入语言包
	$pol_langpackage=new polllp;
	$lp_pol_select=$pol_langpackage->pol_select;
	$lp_pol_sing=$pol_langpackage->pol_sing;
	$lp_pol_b_choi=$pol_langpackage->pol_b_choi;
	$lp_pol_anon=$pol_langpackage->pol_anon_str;

	function poll_item($total_num){
		global $lp_pol_b_choi;
		for($i=$total_num;$i<=$total_num+9;$i++){
			echo "<tr><th>".$lp_pol_b_choi.$i."</th><td><input class='regular-text' type='text' style='width:80%;' name='option[]' value='' maxlength='25'></td></tr>";
		}
	}

	function get_poll_row($dbo,$table,$pid){
		$result=array();
		$sql="select * from $table where p_id=$pid";
		$result=$dbo->getRow($sql);
		return $result;
	}

	function poll_select(){
		global $lp_pol_sing;
		global $lp_pol_select;
		echo "<select name='maxchoice' class='text'>";
		echo "<option value='1'>".$lp_pol_sing."</option>";
		for($i=2;$i<=20;$i++){
			echo "<option value='".$i."'>".str_replace("{s_num}",$i,$lp_pol_select)."</option>";
		}
		echo "</select>";
	}

	function choo_option($c_option,$c_index){
		$choo_opt=unserialize($c_option);
		return $choo_opt[$c_index];
	}

	function choo_ellip($c_option){
		$c_ellipsis='';
		$choo_opt=unserialize($c_option);
		if(count($choo_opt)>2){
			$c_ellipsis='......';
		}
		return $c_ellipsis;
	}

	function choo_type($type){
		$choo_t="radio";
		if($type==1){
			$choo_t="checkbox";
		}
		return $choo_t;

	}

	function option_per($sin_s,$sum_s){
		if(empty($sum_s)){
			$per=0;
		}else{
			$per=($sin_s/$sum_s)*100;
			if(strpos($per,'.')){
				$per=number_format(($sin_s/$sum_s)*100,2);
			}
		}
		return $per;
	}

	function anon_poll($is_anon,$uid,$uname){
		global $lp_pol_anon;
		$anon_str=$lp_pol_anon;
		if($is_anon==0){
			$anon_str="<a href='home.php?h=".$uid."' target='_blank'>".$uname."</a>";
		}
		return $anon_str;
	}
?>
<?php
require("session_check.php");	
	$user_id=get_argg('user_id');
	$dbo = new dbex;
	dbtarget('w',$dbServs);
	//引入语言包
	$m_langpackage=new modulelp;
	$ad_langpackage=new adminmenulp;
	//表定义区
	$t_users=$tablePreStr."users";
	$sql="select user_name,user_ico,user_marry,user_qq,user_blood,birth_province,birth_year, birth_city ,reside_province , reside_city,user_email from $t_users where user_id='$user_id'";
	$member_info=$dbo->getRow($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
</head>

<body>
<div id="maincontent">
    <div class="wrap">
        <div class="infobox">
            <h3><?php echo $member_info['user_name'];?><?php echo $m_langpackage->m_information;?></h3>
            <div class="content">
 <div class='container' style='padding-top:5px;margin-top:0px'>
      </div>
           <table class='list_table'>
             <tr>
               <th><?php echo $m_langpackage->m_names;?></th>
               <td><?php echo $member_info['user_name'];?></td>
               <td rowspan="3"><img src="../<?php echo $member_info['user_ico'];?>" /></td>
             </tr>
             <tr>
             	<th><?php echo $m_langpackage->m_email;?></th>
             	<td><?php echo $member_info['user_email'];?></td>
            </tr>
             <tr>
               <th><?php echo $m_langpackage->m_marriage_status;?></th>
               <td>
               	<?php if($member_info['user_marry']==0) echo $m_langpackage->m_secrecy;
               				if($member_info['user_marry']==1) echo $m_langpackage->m_single;
               				if($member_info['user_marry']==2) echo $m_langpackage->m_married;
               	?>
			        </td>
             </tr>
             <tr>
               <th><?php echo $m_langpackage->m_birthday;?></th>
               <td>
               <?php	
               if($member_info['birth_year']&&$member_info['birth_month']&&$member_info['birth_day']){
               	echo $member_info['birth_year'].$m_langpackage->m_years.$member_info['birth_month'].$m_langpackage->m_month.$member_info['birth_day'].$m_langpackage->m_day;
               	}else echo $m_langpackage->m_not_filled;       	
               ?>  	   
						 </td>
             </tr>
             <tr>
               <th><?php echo $m_langpackage->m_blood_type;?></th>
               <td>
               <?php echo $member_info['user_blood'];?>
				</td>
             </tr>
             <tr>
               <th>QQ</th>
               <td>
               	<?php echo $member_info['user_qq'];?>
               	</td>
             </tr>
             <tr>
               <th><?php echo $m_langpackage->m_hometown;?></th>
               <td>
               <?php echo $member_info['birth_province'];?>&nbsp&nbsp<?php echo $member_info['birth_city'];?>
               	</td>
             </tr>
             <tr>
               <th><?php echo $m_langpackage->m_location;?></th>
               <td>
               	<?php echo $member_info['reside_province'];?>&nbsp&nbsp<?php echo $member_info['reside_city'];?>
               	</td>
             </tr>
</table>
			</div>
		</div>
	</div>
</div>
</body>
</html>

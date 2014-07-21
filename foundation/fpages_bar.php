<?php
	//引入语言包
	$bar_langpackage=new publiclp;

	$page_up=$bar_langpackage->pu_page_u;
	$page_down=$bar_langpackage->pu_page_d;
	$curPstyle='';
function page_show($mod,$page_num,$page_total){
	if($mod==0){
		pagesbar($page_num,$page_total,'page');
	}
}

function page_show_parameter($mod,$page_num,$page_total,$page){
	if($mod==0){
		pagesbar($page_num,$page_total,$page);
	}
}
//分页显示函数
function pagesbar($page_num,$page_total,$page)
{
	global $page_up;
	global $page_down;
	$get_para=$_SERVER['REQUEST_URI'];
	$para_arr=explode('&'.$page,$get_para);
	$get_para=$para_arr[0];
	$sign=strpos($para_arr[0],'?') ? '&':'?';
	if(empty($page_num)){ $page_num=1; }
	if($page_num>1)
	{
		$prePageUrl=$get_para.$sign.$page.'='.($page_num-1);
	}
	else
	{
		$prePageUrl='javascript:void(0);"';
	}

	if($page_total>$page_num)
	{
		$nextPageUrl=$get_para.$sign.$page.'='.($page_num+1);
	}
	else
	{
		$nextPageUrl='javascript:void(0);"';
	}
	?>

	<div class="pages_bar">
		<a href="<?php echo $prePageUrl;?>"><?php echo $page_up;?></a>
		<?php
			if($page_total>10)
			{
				if($page_num<=5)
				{
					for($i=1;$i<=10;$i++)
					{
						echo '<a href="'.$get_para.$sign.$page.'='.$i.'" '.($page_num==$i ? 'class="current_page"' : '').'>'.$i.'</a>';
					}
				}
				else if($page_num>=6&&$page_num+5<$page_total)
				{
					for($b=$page_num-4;$b<$page_num;$b++)
					{
						echo '<a href="'.$get_para.$sign.$page.'='.$b.'">'.$b.'</a>';
					}

					echo '<a href="'.$get_para.$sign.$page.'='.$page_num.'" class="current_page">'.$page_num.'</a>';

					for($h=$page_num+1;$h <= $page_num+5 ;$h++)
					{
						echo '<a href="'.$get_para.$sign.$page.'='.$h.'">'.$h.'</a>';
					}
				}
				else if($page_num+5>=$page_total)
				{
					for($i=$page_total-9;$i<=$page_total;$i++){
						echo '<a href="'.$get_para.$sign.$page.'='.$i.'" '.($page_num==$i ? 'class="current_page"' : '').'>'.$i.'</a>';
					}
				}
			}else
			{
				for($i=1;$i<=$page_total;$i++)
				{
					echo '<a href="'.$get_para.$sign.$page.'='.$i.'"'.($page_num==$i ? 'class="current_page"' : '').'>'.$i.'</a>';
				}
			}
		?>
		<a href="<?php echo $nextPageUrl;?>"><?php echo $page_down;?></a>
	</div><div class="clear"></div>
<?php
}
?>
<?php
class dbex{

	var $rowCount;
	var $perPage;
	var $currPage;
	var $totalPage;

	public function setPages($perPage,$currPage=1){
		if(empty($currPage)){ $currPage=1; }
		$this->perPage=$perPage;
		$this->currPage=$currPage;
	}

	public function setNopages(){
		$this->perPage='';
	}

	public function getRs($sql){
		$rs=array();
		if($this->perPage){
			$sql_count="select count(*) as total_count ".strstr($sql,"from");//查询总数
			$result_count=mysql_query($sql_count);
			$data_count=mysql_fetch_assoc($result_count);
			$this->rowCount=$data_count['total_count'];
			$sql.=" limit ".($this->currPage-1)*$this->perPage.",".$this->perPage;
		}
 	 $result=mysql_query($sql) or die('<script type="text/javascript">location.href="servtools/error.php?error_type=dberr";</script>');

   if($this->perPage){
		$total_rs=$this->rowCount;
		$per_page=$this->perPage;
		$curr_page=$this->currPage;
		$total_page=floor(abs($total_rs-1)/$per_page)+1;//总页数
		$this->totalPage=$total_page;

 	   //限制超页错误
 	   if($curr_page > $total_page){
 	   	  echo '<script type="text/javascript">history.go(-1);</script>';
 	   	  exit;
 	   }
	 }
		while($rsRow=mysql_fetch_array($result)){
			$rs[]=$rsRow;
		}
		return $rs;
	}

   public function getALL($sql){
   	$this->setNopages();
   	return $this->getRs($sql);
  }
   public function getRow($sql)
   {
   	 $result=mysql_query($sql) or die('<script type="text/javascript">location.href="servtools/error.php?error_type=dberr";</script>');

     return mysql_fetch_array($result);
   }
   public function exeUpdate($sql)
   {
      if(mysql_query($sql)){
      	return mysql_affected_rows();
      }else{
      	return false;
      }
   }
   public function create($sql){
    $result=mysql_query($sql);
    $create=mysql_fetch_array($result);
    return $create;
   }
   public function affected_rows($sql){
   	return mysql_affected_rows();
  }
}
?>
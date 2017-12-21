<?php 

  include("../Setting/Configifx.php");
  include("../Setting/Connection.php");
	
  if(!isset($_GET['c']) || !$method = $_GET['c']) exit; 
  $sordno = $_GET['c'];
  $smthyr = $_GET['d'];

  $query  = "SELECT count(*) FROM csalesmas ";
  $query .= " WHERE sordno='$sordno'";
  $query .= " AND smthyr='$smthyr'";
  $result = mysql_query($query) or die(mysql_error());
  $row2 = mysql_fetch_array($result);
  $cnt = $row2[0];
    
  if ($cnt > 0){
   echo '1';
  }else{
   echo '0';	
  }			
?>           


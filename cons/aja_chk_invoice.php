<?php 

  include("../Setting/Configifx.php");
  include("../Setting/Connection.php");

 // if(!isset($_GET['c']) || !$method = $_GET['c']) exit; 
  $counter = $_GET['c'];
  $smthyr = $_GET['d'];

  $query  = "SELECT count(*) FROM cinvoicemas ";
  $query .= " WHERE custcd='$counter'";
  $query .= " AND mthyr='$smthyr'";
  $query .= " AND stat = 'A'";

  $result = mysql_query($query) or die(mysql_error());
  $row2 = mysql_fetch_array($result);
  $cnt = $row2[0];


  if ($cnt > 0){
   echo '1';
  }else{
   echo '0';	
  }			
?>           


<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	
    if(!isset($_GET['procd']) || !$method = $_GET['procd']) exit; 
	$procd=$_GET['procd'];

    if ($procd <> "") {

      $var_sql = " SELECT count(*) as cnt from product";
      $var_sql .= " WHERE productcode = '$procd'";
      $result=mysql_query($var_sql);
      $row = mysql_fetch_array($result);
	  if ($row[0] == 0){
    	echo "0";
      }else{
        echo "1";
      }    	
    }  
?> 
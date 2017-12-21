<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	
    if(!isset($_GET['rawmatcdg']) || !$method = $_GET['rawmatcdg']) exit; 
	$rawmatcdg=$_GET['rawmatcdg'];

    if ($rawmatcdg <> "") {

      $var_sql = " SELECT count(*) as cnt from salestype_master";
      $var_sql .= " WHERE salestype_code = '$rawmatcdg'";
      $result=mysql_query($var_sql);
      $row = mysql_fetch_array($result);
	  if ($row[0] == 0){
    	echo "0";
      }else{
        echo "1";
      }    	
    }  
?> 
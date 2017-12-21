<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	
    if(!isset($_GET['userlog']) || !$method = $_GET['userlog']) exit; 
	$userlogcd=$_GET['userlog'];

    if ($userlogcd <> "") {

      $var_sql = " SELECT count(*) as cnt from user_account ";
        $var_sql .= " WHERE username = '$userlogcd'";

      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);
     
      if ($res_id->cnt > 0 ) {
     
	   echo "<font color=red> This Login User Name Has Been Use</font>";
	  }else {
	  
        echo "<font color=green>This Login User Name Is Valid</font>";
      } 
    }  
?> 
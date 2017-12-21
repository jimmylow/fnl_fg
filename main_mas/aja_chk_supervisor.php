<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
		
    if(!isset($_GET['currcd']) || !$method = $_GET['currcd']) exit; 
	$loccdcd=htmlentities(mysql_real_escape_string($_GET['currcd']));

    if ($loccdcd <> "") {

      $var_sql = " SELECT count(*) as cnt from supervisor_master ";
      $var_sql .= " WHERE supervisor_code = '$loccdcd'";
      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);
     
      if ($res_id->cnt > 0 ) {
     
	   echo "<font color=red> This Supervisor Code Has Been Use</font>";
	  }else {
	  
        echo "<font color=green>This Supervisor Code Is Valid</font>";
      } 
    }  
?> 
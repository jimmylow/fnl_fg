<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
		
    if(!isset($_GET['zonecd']) || !$method = $_GET['zonecd']) exit; 
	$zonecdcd=htmlentities(mysql_real_escape_string($_GET['zonecd']));

    if ($zonecdcd <> "") {

      $var_sql = " SELECT count(*) as cnt from zone_master ";
      $var_sql .= " WHERE zone_code = '$zonecdcd'";
      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);
     
      if ($res_id->cnt > 0 ) {
     
	   echo "<font color=red> This Zone ID Has Been Use</font>";
	  }else {
	  
        echo "<font color=green>This Zone ID Is Valid</font>";
      } 
    }  
?> 
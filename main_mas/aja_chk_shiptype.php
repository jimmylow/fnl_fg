<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
		
    if(!isset($_GET['shipcd']) || !$method = $_GET['shipcd']) exit; 
	$shipcdcd=htmlentities(mysql_real_escape_string($_GET['shipcd']));

    if ($salescdcd <> "") {

      $var_sql = " SELECT count(*) as cnt from shiptype_master ";
      $var_sql .= " WHERE shiptype_code = '$shipcdcd'";
      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);
     
      if ($res_id->cnt > 0 ) {
     
	   echo "<font color=red> This Ship Type Has Been Use</font>";
	  }else {
	  
        echo "<font color=green>This Ship Type Is Valid</font>";
      } 
    }  
?> 
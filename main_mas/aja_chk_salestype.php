<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
		
    if(!isset($_GET['salescd']) || !$method = $_GET['salescd']) exit; 
	$salescdcd=htmlentities(mysql_real_escape_string($_GET['salescd']));

    if ($salescdcd <> "") {

      $var_sql = " SELECT count(*) as cnt from salestype_master ";
      $var_sql .= " WHERE salestype_code = '$salescdcd'";
      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);
     
      if ($res_id->cnt > 0 ) {
     
	   echo "<font color=red> This Sales Type Has Been Use</font>";
	  }else {
	  
        echo "<font color=green>This Sales Type Is Valid</font>";
      } 
    }  
?> 
<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
		
    if(!isset($_GET['currcd']) || !$method = $_GET['currcd']) exit; 
	$catcdcd=htmlentities(mysql_real_escape_string($_GET['currcd']));

    if ($catcdcd <> "") {

      $var_sql = " SELECT count(*) as cnt from category_master ";
      $var_sql .= " WHERE category_code = '$catcdcd'";
      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);
     
      if ($res_id->cnt > 0 ) {
     
	   echo "<font color=red> This Category Code Has Been Use</font>";
	  }else {
	  
        echo "<font color=green>This Category Code Is Valid</font>";
      } 
    }  
?> 
<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
		
    if(!isset($_GET['uomcd']) || !$method = $_GET['uomcd']) exit; 
	$uomrcd=$_GET['uomcd'];

    if ($uomrcd <> "") {

      $var_sql = " SELECT count(*) as cnt from prod_uommas ";
      $var_sql .= " WHERE uom_code = '$uomrcd'";

      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);
     
      if ($res_id->cnt > 0 ) {
     
	   echo "<font color=red> This UOM Code Has Been Created</font>";
	  }else {
	  
        echo "<font color=green>This UOM Code Is Valid</font>";
      } 
    }  
?> 
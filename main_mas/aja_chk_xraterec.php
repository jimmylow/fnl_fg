<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");			
      
	$selmth = $_GET['mth'];
	$selyr = $_GET['yr'];

    if ($selmth != "" || $selyr != ""){
		$var_sql = " SELECT count(*) as cnt from curr_xrate ";
    	$var_sql .= " WHERE xmth = '$selmth' and xyr = '$selyr'";
    	$query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
    	$res_id = mysql_fetch_object($query_id);

    	if ($res_id->cnt > 0 ) {
    		$rtnval = 1;
		}else{
			$rtnval = 0;
		}
		echo $rtnval;
	}	
?>
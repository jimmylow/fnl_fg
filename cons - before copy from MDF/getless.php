<?php
$s=htmlentities($_GET['s']);

 if ($s <> "s") {
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
  
     $sql = " select pro_less from counter ";
     $sql .= " where counter = '$s'";
     $result = mysql_query($sql) or die ("Error proless : ".mysql_error());
     $data = mysql_fetch_object($result); 
     
     $var_less = trim($data->pro_less);     
     if($var_less == "") { $var_less = "N"; }
           
     	echo $var_less; 
       //echo $sql;                 
		mysql_close($db_link);
  	} else {
    	echo "N";
  	}
?> 
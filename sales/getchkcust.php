<?php
$s=htmlentities($_GET['s']);
$i=htmlentities($_GET['i']);

 if ($s <> "s") {
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
  
     $sql = " select * from salesshipmas ";
     $sql .= " where scustcd = '$s'";
     $sql .= " and shipno = '$i'";
     $sql .= " and invflg = 'N'";
     $result = mysql_query($sql) or die ("Error ship info : ".mysql_error());
     
     if(mysql_numrows($result) > 0) { echo "Y"; }
     else { echo "N"; }

		mysql_close($db_link);
  	} else {
    	echo "N";
  	}
?> 
<?php
$q=htmlentities($_GET['q']);

 if ($q <> "s") {
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

     $sql="SELECT y.zone_desc FROM customer_master x,  zone_master y ";
     $sql .= " where x.custno = '".$q."'";
     $sql .= " and y.zone_code = x.zone ";

     $result = mysql_query($sql) or die ("Error : ".mysql_error());
     $data = mysql_fetch_object($result);

     echo $data->zone_desc;                  
   
mysql_close($db_link);

  } else {
  
    echo "";
  }

?> 
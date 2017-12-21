<?php
$q=htmlentities($_GET['q']);

 if ($q <> "s") {
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

     $sql="SELECT *  FROM counter ";
     $sql .= " where counter = '".$q."'";

     $tmp = mysql_query($sql) or die ("Error : ".mysql_error());

      if(mysql_numrows($tmp) > 0) {
         $var_data = "Y";  
       } else {
         $var_data = "N";
       }
           
      echo $var_data;         
   
mysql_close($db_link);

  } else {
  
    echo "";
  }

?> 
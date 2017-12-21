<?php
$q=htmlentities($_GET['q']);

 if ($q <> "s") {
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

     $sql="SELECT supervisor_code, supervisor_name  FROM supervisor_master ";
     $sql .= " where counter_code = '".$q."'";

     $tmp = mysql_query($sql) or die ("Error : ".mysql_error());


     echo '<select name = "sup_id" id = "sup_id">';
      if(mysql_numrows($tmp) > 0) {
        while ($row = mysql_fetch_array($tmp)) {
         echo "<option ";
         echo " value = '".$row['supervisor_code']."'>";
         echo $row['supervisor_code']." - ".$row['supervisor_name']."</option>";
        
       }     
     }
     echo '</select>';                 
   
mysql_close($db_link);

  } else {
  
    echo "";
  }

?> 
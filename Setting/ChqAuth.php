<?php
  include("../Setting/Configifx.php");
  include("../Setting/Connection.php");
 
  $var_accadd = 0;
  $var_accdel = 0;
  $var_accvie = 0;
  $var_accacc = 0;
  $var_accupd = 0;

  $sql = "select insertr, deleter, viewr, accessr, updater from progauth ";
  $sql .= " where program_name ='".$var_menucode."'";
  $sql .= " and username ='".$var_loginid."'";

  $sql_result = mysql_query($sql);
  $row = mysql_fetch_array($sql_result);
  $var_accadd = $row[0];
  $var_accdel = $row[1];
  $var_accvie = $row[2];
  $var_accacc = $row[3];
  $var_accupd = $row[4];

  if ($var_accacc == 0){
     echo "<script>";  
     echo "alert('You Are Not Authorise To Access This Program!!');"; 
     echo "</script>"; 

     echo "<script>";
     echo 'top.location.href = "../index.php"';
     echo "</script>";
  }


?>

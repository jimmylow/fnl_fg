<?php
    include("../Setting/Configifx.php");
    include("../Setting/Connection.php");
	
	if(!isset($_GET['ps']) || !$method = $_GET['ps']) exit; 
	if(!isset($_GET['un']) || !$method = $_GET['un']) exit;
	$psvar=$_GET['ps'];
	$unvar=$_GET['un'];

    if ($psvar <> "" && $unvar <> "") {
   
	  $enc_userpass =  mysql_real_escape_string(md5($psvar));
      $var_sql = " SELECT count(*) as cnt from user_account ";
      $var_sql .= " WHERE username = '$unvar' AND userpass = '$enc_userpass' ";

      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);
     
      if ($res_id->cnt == 0 ) {
     
	  		 echo "<font color=red> This Password Does Belong To This Username</font>";
	  }else {
	  
     	  echo "<font color=green></font>";
      } 
    }  
?> 
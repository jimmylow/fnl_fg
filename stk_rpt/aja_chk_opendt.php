<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	
    if(!isset($_GET['fd']) || !$method = $_GET['fd']) exit; 
	if(!isset($_GET['td']) || !$method = $_GET['td']) exit; 
	
	$dtef=$_GET['fd'];
	$dtet=$_GET['td'];
	
	$dtef= date("Y-m-d", strtotime($dtef));
	$dtet= date("Y-m-d", strtotime($dtet));

    if ($dtef <> "" and $dtet <> "") {

      $var_sql = " SELECT count(*) as cnt from invtopen";
      $var_sql .= " WHERE openingdate between '$dtef' and '$dtet'";
      $result=mysql_query($var_sql);
      $row = mysql_fetch_array($result);
	  if ($row[0] == 0){
    	echo "0";
      }else{
        echo "1";
      }    	
    }  
?> 
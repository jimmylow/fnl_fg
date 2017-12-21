<?php
	include("Configifx.php");

	$db_link  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
    mysql_select_db("$var_db_name")or die("cannot select DB");
	
	 mysql_query("SET NAMES 'utf8'", $db_link)or die(mysql_error()); 	//this will take effect both retrieve, update or insert data of utf-8 
	 // 	mysql_query("SET character_set_results=utf8", $db_link) or die(mysql_error());
	//mb_language('uni');
	//mb_internal_encoding('UTF-8');
	//mysql_select_db("$var_db_name", $db_link);
	//mysql_query("set names 'utf8'",$db_link);

 

?>

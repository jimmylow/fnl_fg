<?php
  // - to get price of each group

	$var_server = '127.0.0.1:3306';  
	$var_userid = 'admin';
	$var_password = '123456';
	//$var_db_name='nl_fgood2'; 
	//$var_db_name='mdf_fgood'; 
  $var_db_name='nl_fgood';

	$db_link  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
    mysql_select_db("$var_db_name")or die("cannot select DB ".mysql_error());
	
	 mysql_query("SET NAMES 'utf8'", $db_link)or die(mysql_error()); 	//this will take effect both retrieve, update or insert data of utf-8 
  

 //------------ Price 1
 
  $sql = " select prod from delprod";
  $sql .= " order by prod";
  
  $tmp = mysql_query($sql) or die ("P1 : ".mysql_error());  
    
  while ($row = mysql_fetch_array($tmp)) {
     $var_prod = $row['prod'];
      
     $sql2 = "update product ";
     $sql2 .= " set status = 'D'";
		 $sql2 .= " where productcode = '".$var_prod."'";
                    
		 mysql_query($sql2) or die ("Upd master : ".mysql_error()); 
     
     //$sql2 = "update delprod ";
     //$sql2 .= " set upd = 'Y'";
		 //$sql2 .= " where prod = '".$var_prod."'";
                    
		 //mysql_query($sql2) or die ("Upd flg : ".mysql_error());         
  
     echo "<br />Prod : ".$var_prod;
  }

//SELECT *  FROM product WHERE exunit = ""

?>
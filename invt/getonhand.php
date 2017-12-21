<?php
$var_prodcd = htmlentities($_GET['i']);

 if ($var_prodcd <> "") {
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
  
  //---- for opening qty : OP -----//
  $sql = " select sum(qtyin) as cnt from invthist";
  $sql .= " where reftype = 'OP'";
  $sql .= " and prodcode = '".$var_prodcd."'";
  
  $tmp = mysql_query ($sql) or die ("Cant get 0 : ".mysql_error());
  
  if (mysql_numrows($tmp) > 0) {
      $rst = mysql_fetch_object($tmp);
      $var_begbal = $rst->cnt;
      
      if ($var_begbal == "") { $var_begbal = 0; }
  } else { $var_begbal = 0; }    
  
  //---- for rec qty : RC -----//
  $sql = " select sum(qtyin) as cnt from invthist";
  $sql .= " where reftype = 'RC'";
  $sql .= " and prodcode = '".$var_prodcd."'";
  
  
  $tmp = mysql_query ($sql) or die ("Cant get 1 : ".mysql_error());
  
  if (mysql_numrows($tmp) > 0) {
      $rst = mysql_fetch_object($tmp);
      $var_rec = $rst->cnt;
      
      if ($var_rec == "") { $var_rec = 0; }
  } else { $var_rec = 0; }
  
  //---- for transfer in qty : TI -----//
  $sql = " select sum(qtyin) as cnt from invthist";
  $sql .= " where reftype = 'TI'";
  $sql .= " and prodcode = '".$var_prodcd."'";
  
  $tmp = mysql_query ($sql) or die ("Cant get 2 : ".mysql_error());
  
  if (mysql_numrows($tmp) > 0) {
      $rst = mysql_fetch_object($tmp);
      $var_trfin = $rst->cnt;
      
      if ($var_trfin == "") { $var_trfin = 0; }
  } else { $var_trfin = 0; } 
  
  //---- for transfer out qty : TO -----//
  $sql = " select sum(qtyout) as cnt from invthist";
  $sql .= " where reftype = 'TO'";
  $sql .= " and prodcode = '".$var_prodcd."'";
  
  $tmp = mysql_query ($sql) or die ("Cant get 3 : ".mysql_error());
  
  if (mysql_numrows($tmp) > 0) {
      $rst = mysql_fetch_object($tmp);
      $var_trfout = $rst->cnt;
      
      if ($var_trfout == "") { $var_trfout = 0; }
  } else { $var_trfout = 0; } 
  
  //---- for ret from : RN -----//
  $sql = " select sum(qtyin) as cnt from invthist";
  $sql .= " where reftype = 'RN'";
  $sql .= " and prodcode = '".$var_prodcd."'";
  
  $tmp = mysql_query ($sql) or die ("Cant get 4 : ".mysql_error());
  
  if (mysql_numrows($tmp) > 0) {
      $rst = mysql_fetch_object($tmp);
      $var_rtnfrm = $rst->cnt;
      
      if ($var_rtnfrm == "") { $var_rtnfrm = 0; }
  } else { $var_rtnfrm = 0; } 
  
     
  //---- for ship qty : SA -----//
  $sql = " select sum(qtyout) as cnt from invthist";
  $sql .= " where reftype = 'SA'";
  $sql .= " and prodcode = '".$var_prodcd."'";
  
  $tmp = mysql_query ($sql) or die ("Cant get 5 : ".mysql_error());
  
  if (mysql_numrows($tmp) > 0) {
      $rst = mysql_fetch_object($tmp);
      $var_ship = $rst->cnt;
      
      if ($var_ship == "") { $var_ship = 0; }
  } else { $var_ship = 0; }

  //---- for adj qty : AD -----//
  $sql = " select sum(qtyin - qtyout) as cnt from invthist";
  $sql .= " where reftype = 'AD'";
  $sql .= " and prodcode = '".$var_prodcd."'";
  
  $tmp = mysql_query ($sql) or die ("Cant get 6 : ".mysql_error());
  
  if (mysql_numrows($tmp) > 0) {
      $rst = mysql_fetch_object($tmp);
      $var_adj = $rst->cnt;
      
      if ($var_adj == "") { $var_adj = 0; }
  } else { $var_adj = 0; } 
  
  //---- for return to qty : RS -----//
  $sql = " select sum(qtyout) as cnt from invthist";
  $sql .= " where reftype = 'RS'";
  $sql .= " and prodcode = '".$var_prodcd."'";
  
  $tmp = mysql_query ($sql) or die ("Cant get 7 : ".mysql_error());
  
  if (mysql_numrows($tmp) > 0) {
      $rst = mysql_fetch_object($tmp);
      $var_rtnto = $rst->cnt;
      
      if ($var_rtnto == "") { $var_rtnto = 0; }
  } else { $var_rtnto = 0; }   
    
  //$var_begbal = 0;
  $var_rtnall = $var_rtnfrm - $var_rtnto;
  $var_onhand = $var_begbal + $var_rec + $var_trfin - $var_trfout + $var_rtnfrm - $var_ship + $var_adj - $var_rtnto;

  if($var_onhand == "") { $var_onhand = 0; }
  
  echo $var_onhand;
	
  	mysql_close($db_link);
    
  	} else {
    	echo "0";
  	}
?> 
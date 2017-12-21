<?php
$s=htmlentities($_GET['s']);
$i=htmlentities($_GET['i']);

 if ($s <> "s") {
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
  
     $sql = " select pricegroup from customer_master ";
     $sql .= " where custno = '$s'";
     $result = mysql_query($sql) or die ("Error price group : ".mysql_error());
     $data = mysql_fetch_object($result); 
     
     $var_pricegroup = $data->pricegroup;

     $sql= " SELECT exunit, selltype ";
     $sql .= " FROM product ";
     $sql .= " where productcode = '".$i."'";

     $result = mysql_query($sql) or die ("Error product : ".mysql_error());
     $data = mysql_fetch_object($result);

     $var_type = "";         
     $var_type = $data->selltype;
     $u = $data->exunit;

     $sql= " SELECT uprice ";
     $sql .= " FROM prodprice ";
     $sql .= " where productcode = '".$i."'";
     $sql .= " and pricecode = '$var_pricegroup'";


     $result = mysql_query($sql) or die ("Error price : ".mysql_error());
     $data = mysql_fetch_object($result);     
     
     $var_price = 0; 
     if (!empty($data->uprice)) { $var_price = $data->uprice;  }  
     
     $sql = " select uom_pack from prod_uommas";
     $sql .= " where uom_code = '".$u."'";

     $result = mysql_query($sql) or die ("Error uom : ".mysql_error());
     
     if(mysql_numrows($result) > 0) {
       $data = mysql_fetch_object($result);
       $var_uqty = $data->uom_pack;
       if ($var_uqty == "") { $var_uqty = 1; }         
      }  else { $var_uqty = 1; }
      
     	echo $var_type."k".$var_price."k".$var_uqty."k".$u; 
       //echo $sql;                 
		mysql_close($db_link);
  	} else {
    	echo "Xk0.00k0k0";
  	}
?> 
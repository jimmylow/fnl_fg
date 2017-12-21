<?php
$s=htmlentities($_GET['s']);
$i=htmlentities($_GET['i']);
$d=$_GET['d'];

 if ($s <> "s") {
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
  
     $sql = " select pricegroup from customer_master ";
     $sql .= " where custno = '$s'";
     $result = mysql_query($sql) or die ("Error price group : ".mysql_error());
     $data = mysql_fetch_object($result); 
     
     $var_pricegroup = $data->pricegroup;

     $sql= " SELECT selltype ";
     $sql .= " FROM product ";
     $sql .= " where productcode = '".$i."'";

     $result = mysql_query($sql) or die ("Error product : ".mysql_error());
     $data = mysql_fetch_object($result);

     $var_type = "";         
     $var_type = $data->selltype;

     $sql= " SELECT uprice ";
     $sql .= " FROM prodprice ";
     $sql .= " where productcode = '".$i."'";
     $sql .= " and pricecode = '$var_pricegroup'";

     $result = mysql_query($sql) or die ("Error price : ".mysql_error());
     $data = mysql_fetch_object($result);     
     
     $var_price = 0; 
     if (!empty($data->uprice)) { $var_price = $data->uprice;  } 
     
     if($d <> "") {
        $tmpmth = explode("/", $d);
        $domth = intval($tmpmth[0]);
        $doyear = $tmpmth[1]; 
        
        if($domth == 12) {
           $prevmth = 1;
           $prevyr = intval($doyear) - 1;
         }  else {
           $prevmth = $domth - 1;
           $prevyr = $doyear;
           $prevmthyr =  vsprintf("%02d",$prevmth)."/".$prevyr; 
         }
        
        
        $sql2 = " SELECT SUM(x.sproqty) as tot FROM salesentrydet x, salesentry y";
        $sql2 .= " WHERE y.scustcd = '".$s."'";
        $sql2 .= " AND x.sordno = y.sordno";
        $sql2 .= " AND y.sordno IN (SELECT sordno FROM salesdo WHERE ";
        $sql2 .= " MONTH(delorddte) = ".$domth;
        $sql2 .= " AND YEAR (delorddte) = ".$doyear.")";
        $sql2 .= " AND x.sprocd = '".$i."'";
                
        //echo $sql2;
        $tmp2 = mysql_query($sql2) or die ("cant get do qty : ".mysql_error());
                
        if(mysql_numrows($tmp2) >0) {
          $rst2 = mysql_fetch_object($tmp2);
          $doqty = $rst2->tot; 
          if($doqty =="") { $doqty = 0; }
         } 
         
         $sql2 = " SELECT endbal FROM csalesdet x, csalesmas y ";
         $sql2 .= " WHERE y.scustcd = '".$s."'";
         $sql2 .= " AND y.smthyr = '".$prevmthyr."'";
         $sql2 .= " AND x.sordno = y.sordno";
         $sql2 .= " AND x.sprocd = '".$i."'";
                
         $tmp2 = mysql_query($sql2) or die ("cant get do qty : ".mysql_error());
                
         if(mysql_numrows($tmp2) >0) {
             $rst2 = mysql_fetch_object($tmp2);
             $begbal = $rst2->endbal; 
             if($begbal =="") { $begbal = 0; }
         }  else { $begbal = 0; }               

     } else { $doqty = 0; $begbal = 0; }
      
      
     	echo $var_type."k".$var_price."k".$doqty."k".$begbal; 
       //echo $sql;                 
		mysql_close($db_link);
  	} else {
    	echo "Xk0.00k0k0";
  	}
?> 
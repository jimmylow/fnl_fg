<?php
$q=htmlentities($_GET['q']);

 if ($q <> "s") {
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

     $sql="SELECT add1, add2, add3, add4, contact, tel, fax, mobile, terms, currency FROM supplier_master ";
     $sql .= " where suppno = '".$q."'";

     $result = mysql_query($sql) or die ("Error : ".mysql_error());

     $data = mysql_fetch_object($result);

     $var_add = "";
     
     $var_add .= $data->add1." \n"; 
     $var_add .= $data->add2." \n";
     $var_add .= $data->add3." \n";
     $var_add .= $data->add4." \n";
     $var_add .= "\nTel : "; 
     if (!empty($data->tel)) { $var_add .= $data->tel.","; }  
     if (!empty($data->mobile)) { $var_add .= $data->mobile; }   
     $var_add .= "\nFax : "; 
     if (!empty($data->fax1)) { $var_add .= $data->fax; }  
     
     $var_terms = $data->terms;
     $var_currency = $data->currency;
     
     echo $var_add."~".$var_terms."~".$var_currency;                  
   
mysql_close($db_link);

  } else {
  
    echo "";
  }

?> 
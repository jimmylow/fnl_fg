<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

    $return_arr = array();
    $param = $_GET["term"];

    $fetch = mysql_query("SELECT productcode, exunit, description, exfacprice FROM product WHERE productcode REGEXP '^$param' and status = 'A' LIMIT 10");

    /* Retrieve and store in array the results of the query.*/
    while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {
    
        $row_array['prcode'] = $row['productcode']; 
        $row_array['pruom'] = $row['exunit'];
        $row_array['prdesc'] = $row['description'];
        $row_array['openingcost'] = $row['exfacprice'];
        
        $sql = " select uom_pack from prod_uommas";
        $sql .= " where uom_code = '".$row['exunit']."'";

        $result = mysql_query($sql) or die ("Error uom : ".mysql_error());
     
        if(mysql_numrows($result) > 0) {
          $data = mysql_fetch_object($result);
          $var_uqty = $data->uom_pack;
          if ($var_uqty == "") { $var_uqty = 1; }         
         }  else { $var_uqty = 1; }
        
        $row_array['uqty'] = $var_uqty;
        
        array_push( $return_arr, $row_array );
    }

   
    /* Toss back results as json encoded array. */
    echo json_encode($return_arr);
?>

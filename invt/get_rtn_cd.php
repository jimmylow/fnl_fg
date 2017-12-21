<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

    $return_arr = array();
    $param = $_GET["term"];

    $fetch = mysql_query("SELECT productcode, exunit, description, exfacprice FROM product WHERE productcode REGEXP '^$param' and status = 'A' LIMIT 10");
    //$fetch = mysql_query("SELECT x.productcode, x.exunit, x.description, x.exfacprice FROM product x, po_trans y WHERE x.productcode REGEXP '^$param' and x.productcode = y.itemcode LIMIT 10");

    /* Retrieve and store in array the results of the query.*/
    while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {
    
        $row_array['prod_code'] = $row['productcode'];
        
        $row_array['pruom'] = $row['exunit'];
        $row_array['prdesc'] = $row['description'];
        
        $var_pri = $row['exfacprice'];
        if($var_pri == "" || empty($var_pri)) { $var_pri = 0; } 
        $row_array['prpri'] = $var_pri;
                
        array_push( $return_arr, $row_array );
    }

   
    /* Toss back results as json encoded array. */
    echo json_encode($return_arr);
?>

<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

    $return_arr = array();
    $param = $_GET["term"];

    $fetch = mysql_query("SELECT productcode, description FROM product WHERE productcode REGEXP '^$param' and status = 'A' order by productcode LIMIT 10");

    /* Retrieve and store in array the results of the query.*/
    while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {
    
        $row_array['prcode'] = $row['productcode']; 
        $row_array['prdesc'] = $row['description'];       
        
        array_push( $return_arr, $row_array );
    }

   
    /* Toss back results as json encoded array. */
    echo json_encode($return_arr);
?>

<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

    $return_arr = array();
    $param = $_GET["term"];

    //$fetch = mysql_query("SELECT shipno, shipdte FROM salesshipmas WHERE shipno REGEXP '^$param' and invflg = 'N' and stat = 'A' and stype = '' LIMIT 10");
    $fetch = mysql_query("SELECT shipno, shipdte FROM salesshipmas WHERE shipno REGEXP '^$param' and invflg = 'N' and stat = 'A' LIMIT 10");

    /* Retrieve and store in array the results of the query.*/
    while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {
        
        $row_array['rm_code'] = $row['shipno'];
        
        $row_array['uom'] = $row['shipdte'];
        if (empty($row['description'])) { $row_array['desc'] = ""; } else { $row_array['desc'] = htmlspecialchars_decode($row['description']); }
        
        array_push( $return_arr, $row_array );  
    }

   
    /* Toss back results as json encoded array. */
    echo json_encode($return_arr);
?>

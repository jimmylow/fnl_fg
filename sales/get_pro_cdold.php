<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

    $return_arr = array();
    $param = $_GET["term"];

    $fetch = mysql_query("SELECT * FROM product WHERE ProductCode REGEXP '^$param' LIMIT 10");

    /* Retrieve and store in array the results of the query.*/
    while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {
    
        $row_array['prod_code'] = $row['ProductCode'];
        	
        if ($row[Color] == ""){
        	$row_array['colour'] = " ";
        }else{	
        	$row_array['colour'] = $row[Color];
        }
        
        if ($row['Size'] == ""){
          	$row_array['size'] = " ";
        }else{
        	$row_array['size'] = $row['Size'];
        }
        if ($row['ExUnit'] == ""){	
        	 $row_array['pruom'] = " ";
	    	}else{
        	$row_array['pruom'] = $row['ExUnit'];
        }
        if ($row['Description'] == ""){
        	$row_array['prdesc'] = " ";
        }else{	
        	$row_array['prdesc'] = $row['Description'];
        }        

        $row_array['prtype'] = " ";
                
        array_push( $return_arr, $row_array );
    }

   
    /* Toss back results as json encoded array. */
    echo json_encode($return_arr);
?>
